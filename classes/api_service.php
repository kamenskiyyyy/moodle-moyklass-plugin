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

require_once("{$CFG->libdir}/filelib.php");

/**
 * Сервис для получения данных по api Мой класс
 */
class api_service {
    private static $host_url = "https://api.moyklass.com/v1/company/";

    /**
     * Авторизация. Получение токена для работы с API.
     *
     * @return bool
     */
    public function get_auth_token() {
        $api_key = get_config('local_moyclass', 'apikey');
        $url = self::$host_url . "auth/getToken";
        try {
            $response = $this->api()->post($url, json_encode(['apiKey' => $api_key]));
            return json_decode($response);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Получение информации о работниках школы
     *
     * @return void
     */
    public function get_managers(): array {
        $url = self::$host_url . "managers";
        try {
            $response = $this->api()->get($url);
            return json_decode($response, true);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Получаем информацию об учениках школы
     *
     * @return false|mixed
     */
    public function get_students(): array {
        $url = self::$host_url . "users";
        try {
            $response = $this->api()->get($url, ['includePayLink' => 'true', 'limit' => 500]);
            return json_decode($response, true)['users'];
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Информация о группах студентов
     *
     * @return false|mixed
     */
    public function get_joins() {
        $url = self::$host_url . "joins";
        try {
            $response = $this->api()->get($url);
            return json_decode($response, true)['joins'];
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Информация о группах школы
     *
     * @return false|mixed
     */
    public function get_classes(): array {
        $url = self::$host_url . "classes";
        try {
            $response = $this->api()->get($url);
            return json_decode($response, true);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Информация о занятиях учеников школы
     *
     * @return false|mixed
     */
    public function get_lessons(): array {
        $first_date = date("Y-m-d", strtotime("-30 days"));
        $second_date = date("Y-m-d", strtotime("30 days"));
        $url = self::$host_url . "lessons?date={$first_date}&date={$second_date}&limit=500";
        try {
            $response = $this->api()->get($url);
            return json_decode($response, true)['lessons'];
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Получаем список записей учеников на занятия
     * @return array|false
     */
    public function get_lesson_records(): array {
        $first_date = date("Y-m-d", strtotime("-30 days"));
        $second_date = date("Y-m-d", strtotime("30 days"));
        $url = self::$host_url . "lessonRecords?date={$first_date}&date={$second_date}&limit=500";
        try {
            $response = $this->api()->get($url);
            return json_decode($response, true)['lessonRecords'];
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Получаем статусы клиентов
     *
     * @return void
     */
    public function get_client_statuses(): array {
        $url = self::$host_url . "clientStatuses";
        try {
            $response = $this->api()->get($url);
            return json_decode($response, true);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Получаем виды абонементов
     *
     * @return void
     */
    public function get_subscriptions(): array {
        $url = self::$host_url . "subscriptions";
        try {
            $response = $this->api()->get($url);
            return json_decode($response, true)['subscriptions'];
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Получаем абонементы учеников
     *
     * @return void
     */
    public function get_user_subscriptions(): array {
        $url = self::$host_url . "userSubscriptions";
        try {
            $response = $this->api()->get($url);
            return json_decode($response, true)['subscriptions'];
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Получаем успешные платежи учеников
     *
     * @return void
     */
    public function get_payments(): array {
        $url = self::$host_url . "payments";
        try {
            $response = $this->api()->get($url, ['optype'=>'income']);
            return json_decode($response, true)['payments'];
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Получаем счета на оплату для учеников
     *
     * @return void
     */
    public function get_invoices() {
        $url = self::$host_url . "invoices";
        try {
            $response = $this->api()->get($url);
            return json_decode($response, true)['invoices'];
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Отменяем урок
     * @param $recordId
     * @return bool
     */
    public function cancel_lesson($recordId): bool {
        $url = self::$host_url . "lessonRecords/" . $recordId;
        try {
            $this->api()->delete($url);
            return true;
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Config api for backend CRM
     *
     * @return \curl
     * @throws dml_exception*@throws dml_exception
     */
    private function api(): \curl {
        global $DB;
        $x_access_token = $DB->get_record('local_moyclass_auth', ['active' => '1']);
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
