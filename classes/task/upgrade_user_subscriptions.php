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

namespace local_moyclass\task;

use dml_exception;
use local_moyclass\manager_db;

defined('MOODLE_INTERNAL') || die();

/**
 * Автоматическая синхронизация купленных абонементы учеников из CRM Мой класс в LMS Moodle
 */
class upgrade_user_subscriptions extends \core\task\scheduled_task {
    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return "Обновить купленные абонементы учеников школы";
    }

    /**
     * Execute the task.
     */
    public function execute() {
        $manager = new manager_db();
        try {
            $manager->set_user_subscriptions();
        } catch (dml_exception $e) {
        }
    }
}
