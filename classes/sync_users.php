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

use core_reportbuilder\local\filters\number;
use dml_exception;
use stdClass;

/**
 * Сервис для синхронизации пользователей между CRM и Moodle
 */
class sync_users {
    public function set_managers_in_moodle() {
        global $DB;
        $managers = $DB->get_records('local_moyclass_managers');
        $transaction = $DB->start_delegated_transaction();
        foreach ($managers as $manager) {
            $is_user = $this->check_user_in_moodle($manager->email);
            if ($is_user) {
                $user = $this->update_user($manager, $is_user->id);
                $user->suspended = $this->check_is_work($manager->iswork);
                $DB->update_record('user', $user);
            } else {
                $user = $this->create_new_user($manager);
                $user->suspended = $this->check_is_work($manager->iswork);
                $DB->insert_record('user', $user);
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
     * @return stdClass
     */
    private function update_user($student, $user_id) {
        $user = new stdClass();
        $user->id = $user_id;
        $user->lang = $student->lang ?: 'ru';
        $user->email = strtolower($student->email);
        $names = explode(' ', $student->name);
        $user->lastname = $names[1] ?: "-";
        $user->firstname = $names[0];
        $user->phone1 = $student->phone ?: " ";
        $user->city = $student->city ?: " ";
        $user->institution = $student->company ?: " ";
        $user->department = $student->position ?: " ";
        return $user;
    }

    /**
     * Устанивливаем или обновляем пользователей
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_students_in_moodle() {
        global $DB;
        $sql = "SELECT * FROM {local_moyclass_students} WHERE `email` IS NOT NULL ORDER BY `email` DESC";
        $students = $DB->get_records_sql($sql);
        $transaction = $DB->start_delegated_transaction();
        foreach ($students as $student) {
            $is_user = $this->check_user_in_moodle($student->email);
            if ($is_user) {
                $user = $this->update_user($student, $is_user->id);
                $user->suspended = $this->check_status_client($student->clientstateid);
                $DB->update_record('user', $user);
            } else {
                $user = $this->create_new_user($student);
                $user->suspended = $this->check_status_client($student->clientstateid);
                $DB->insert_record('user', $user);
            }
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Проверяем статус клиента. Если клиент активный, вернет 1, если нет, вернет 0
     *
     * @param number $clientstateid
     * @return int
     */
    private function check_status_client($clientstateid): int {
        if ($clientstateid === "121696") {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * Создаем объект нового пользователя
     *
     * @param $student
     * @return stdClass
     */
    private function create_new_user($student) {
        $user = new StdClass();
        $user->auth = 'manual';
        $user->lang = $student->lang ?: 'ru';
        $user->confirmed = 1;
        $user->mnethostid = 1;
        $user->email = strtolower($student->email);
        $user->username = strtolower(strstr($student->email, '@', true));
        $new_password = password_hash(uniqid(), PASSWORD_DEFAULT);
        $user->password = $new_password;
        $names = explode(' ', $student->name);
        $user->lastname = $names[1] ?: "-";
        $user->firstname = $names[0];
        $user->phone1 = $student->phone ?: " ";
        $user->city = $student->city ?: " ";
        $user->institution = $student->company ?: " ";
        $user->department = $student->position ?: " ";
        return $user;
    }

    /**
     * Проверяем статус работника. Если работает - вернет 1, если нет - вернет 0
     *
     * @param $isWork
     * @return int
     */
    private function check_is_work($isWork): int {
        if ($isWork === "1") {
            return 0;
        } else {
            return 1;
        }
    }
}
