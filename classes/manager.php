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

use dml_exception;
use stdClass;

class manager {
    /**
     * Авторизация на бекенде, получение или обновление токена.
     * @return bool
     * @throws dml_exception
     */
    public function set_auth_token(): bool {
        global $DB;
        $api_service = new api_service();
        $result = $api_service->getAuthToken();
        $read_record = new stdClass();
        $read_record->accesstoken = $result->accessToken;
        $read_record->expiresat = $result->expiresAt;
        $read_record->active = true;
        try {
            return $DB->insert_record('local_moyclass_auth', $read_record, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    public function set_managers() {
        global $DB;
        $api_service = new api_service();
        $result = $api_service->getManagers();
        return $result;
    }
}
