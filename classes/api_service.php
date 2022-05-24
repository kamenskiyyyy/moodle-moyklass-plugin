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

require_once("{$CFG->libdir}/filelib.php");

class api_service {
    private static $host_url = "https://api.moyklass.com/v1/company/";

    /**
     * Авторизация. Получение токена для работы с API.
     * @param string $api_key
     * @return bool
     */
    public function getAuthToken(string $api_key): bool {
        global $DB;
        $url = self::$host_url . "auth/getToken";
        $response = $this->api()->post($url, json_encode(['apiKey' => $api_key]));
        $result = json_decode($response);
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

    /**
     * Config api for backend CRM
     * @return \curl
     */
    private function api(): \curl {
        global $DB;
        $x_access_token = $DB->get_record('local_moyclass_auth', ['active'=>'1']);
        $header = [];
        if ($x_access_token) {
            $header =
                ['Content-Type: application/json', 'Accept: application/json', "x-access-token:{$x_access_token->accesstoken}"];
        } else {
            $header =
                ['Content-Type: application/json', 'Accept: application/json', 'x-access-token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9'];
        }

        $curl = new \curl();
        $curl->setHeader($header);
        return $curl;
    }
}
