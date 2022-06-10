<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * local_moyclass file description here.
 *
 * @package    local_moyclass
 * @copyright  2022 mac <kamenik1@icloud.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_moyclass;

use core_user;
use dml_exception;
use local_moyclass\notifications\emails;
use local_moyclass\notifications\manager;
use moodle_exception;
use stdClass;

/**
 * Сервис для синхронизации пользователей между CRM и Moodle
 */
class sync_users {
    /** Регистранция работников школы
     *
     * @throws \coding_exception
     * @throws dml_exception
     * @throws \dml_transaction_exception
     * @throws moodle_exception
     */
    public function check_managers_in_moodle() {
        global $DB;
        $managers = $DB->get_records('local_moyclass_managers');
        $this->set_users($managers);
    }

    /**
     * Создаем пользователей в Moodle
     *
     * @throws \coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     * @throws \dml_transaction_exception
     */
    private function set_users($people) {
        global $DB;
        $transaction = $DB->start_delegated_transaction();
        foreach ($people as $person) {
            $is_user = $this->check_user_in_moodle($person->email);
            if ($is_user) {
                $this->update_user($person, $is_user->id);
            } else if ($person->isactive) {
                $password = generate_password();
                $new_password = hash_internal_user_password($password);
                $user = $this->create_new_user($person, $new_password);
                $this->send_email_new_user($user, $password);
            }
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Проверяем есть ли пользователь в DB
     *
     * @param string $email
     * @return false|mixed|\stdClass
     */
    private function check_user_in_moodle(string $email) {
        global $DB;
        try {
            return $DB->get_record('user', ['email' => $email]);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Обновляем пользователя по id
     *
     * @param $student
     * @param $user_id
     * @return void
     * @throws moodle_exception
     */
    private function update_user($student, $user_id) {
        $user = new stdClass();
        $user->id = $user_id;
        $user->suspended = $student->isactive;
        $user->email = strtolower($student->email);
        $names = explode(' ', $student->name);
        $user->lastname = $names[1] ?: "-";
        $user->firstname = $names[0];
        $user->lang = $student->lang ?: core_user::get_property_default('lang');
        $user->phone1 = $student->phone ?: "";
        $user->city = $student->city ?: core_user::get_property_default('city');
        $user->institution = $student->company ?: "";
        $user->department = $student->position ?: "";

        return user_update_user($user, false, false);
    }

    /**
     * Устанивливаем или обновляем пользователей
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     * @throws \coding_exception
     * @throws moodle_exception
     */
    public function check_students_in_moodle() {
        global $DB;
        $sql = "SELECT * FROM {local_moyclass_students} WHERE `email` IS NOT NULL ORDER BY `email` DESC";
        $students = $DB->get_records_sql($sql);
        $this->set_users($students);
    }

    /**
     * Создаем объект нового пользователя
     *
     * @param $student
     * @param $password
     * @return int
     * @throws moodle_exception
     */
    private function create_new_user($student, $password): int {
        $user = new StdClass();
        $user->auth = 'manual';
        $user->lang = $student->lang ?: core_user::get_property_default('lang');
        $user->confirmed = 1;
        $user->mnethostid = 1;
        $user->suspended = $student->isactive;
        $user->email = strtolower($student->email);
        $user->username = strtolower(strstr($student->email, '@', true));
        $names = explode(' ', $student->name);
        $user->lastname = $names[1] ?: "-";
        $user->firstname = $names[0];
        $user->password = $password;
        $user->phone1 = $student->phone ?: "";
        $user->city = $student->city ?: core_user::get_property_default('city');
        $user->institution = $student->company ?: "";
        $user->department = $student->position ?: "";

        return user_create_user($user, false, false);
    }

    /**
     * Отправляем новому пользователю письмо после регистрации
     *
     * @throws \coding_exception
     * @throws dml_exception
     */
    private function send_email_new_user($userid, $password) {
        $notification = new manager();
        $emails = new emails();

        $user = core_user::get_user($userid);

        $message = $emails->get_welcome_email($user->firstname, $user->username, $password);
        $notification->send_email($userid, 'Welcome to Just Study', $message);
    }
}
