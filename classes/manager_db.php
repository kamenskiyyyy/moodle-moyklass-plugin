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

/**
 * Сервис для записи в базу данных результат получения данных по api
 */
class manager_db {
    /**
     * Устанавливаем новый токен авторизации.
     *
     * @return bool
     * @throws dml_exception
     */
    public function set_auth_token(): bool {
        global $DB;
        $DB->delete_records('local_moyclass_auth');
        $api_service = new api_service();
        $result = $api_service->get_auth_token();
        $dataobject = new stdClass();
        $dataobject->accesstoken = $result->accessToken;
        $dataobject->expiresat = $result->expiresAt;
        $dataobject->active = true;
        try {
            return $DB->insert_record('local_moyclass_auth', $dataobject, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /**
     * Устанавливаем работников школы
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_managers() {
        global $DB;
        $DB->delete_records('local_moyclass_managers');
        $api_service = new api_service();
        $results = $api_service->get_managers();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->managerid = $result['id'];
            $dataobject->name = $result['name'];
            $dataobject->phone = $result['phone'];
            $dataobject->email = $result['email'];
            $dataobject->iswork = $result['isWork'];
            $DB->insert_record('local_moyclass_managers', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Устанавливаем студентов школы
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_students() {
        global $DB;
        $DB->delete_records('local_moyclass_students');
        $api_service = new api_service();
        $results = $api_service->get_students();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->studentid = $result['id'];
            $dataobject->clientstateid = $result['clientStateId'];
            $dataobject->name = $result['name'];
            $dataobject->email = $result['email'];
            $dataobject->phone = $result['phone'];
            $dataobject->balans = $result['balans'];
            $dataobject->paylinkkey = $result['payLinkKey'];
            $attributes = $result['attributes'];
            foreach ($attributes as $index => $alias) {
                if ($alias['attributeAlias'] === 'city') {
                    $dataobject->city = $attributes[$index]['value'];
                }
                if ($alias['attributeAlias'] === 'company') {
                    $dataobject->company = $attributes[$index]['value'];
                }
                if ($alias['attributeAlias'] === 'position') {
                    $dataobject->position = $attributes[$index]['value'];
                }
                if ($alias['attributeAlias'] === 'yazik_interfeisa') {
                    $dataobject->lang = $attributes[$index]['value'];
                } else {
                    $dataobject->lang = "ru";
                }
            }
            $DB->insert_record('local_moyclass_students', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Устанавливаем информацию о группах студентов.
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_joins() {
        global $DB;
        $DB->delete_records('local_moyclass_joins');
        $api_service = new api_service();
        $results = $api_service->get_joins();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->joinid = $result['id'];
            $dataobject->userid = $result['userId'];
            $dataobject->classid = $result['classId'];
            $dataobject->price = $result['price'];
            $dataobject->statusid = $result['statusId'];
            $dataobject->reminddate = $result['remindDate'];
            $dataobject->remindsum = $result['remindSum'];
            $dataobject->visits = $result['stats']['visits'];
            $dataobject->nextrecord = $result['stats']['nextRecord'];
            $dataobject->nonpayedlessons = $result['stats']['nonPayedLessons'];
            $DB->insert_record('local_moyclass_joins', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Устанавливаем информацию о всех группах школы.
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_classes() {
        global $DB;
        $DB->delete_records('local_moyclass_classes');
        $api_service = new api_service();
        $results = $api_service->get_classes();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->classid = $result['id'];
            $dataobject->name = $result['name'];
            $dataobject->begindate = $result['beginDate'];
            $dataobject->maxstudents = $result['maxStudents'];
            $dataobject->status = $result['status'];
            $dataobject->price = $result['price'];
            $dataobject->pricecomment = $result['priceComment'];
            $dataobject->managerids = json_encode($result['managerIds']);
            $DB->insert_record('local_moyclass_classes', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Устанавливаем информацию о всех уроках школы.
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_lessons() {
        global $DB;
        $DB->delete_records('local_moyclass_lessons');
        $api_service = new api_service();
        $results = $api_service->get_lessons();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->lessonid = $result['id'];
            $dataobject->date = $result['date'];
            $dataobject->begintime = $result['beginTime'];
            $dataobject->endtime = $result['endTime'];
            $dataobject->createdat = $result['createdAt'];
            $dataobject->classid = $result['classId'];
            $dataobject->status = $result['status'];
            $dataobject->comment = $result['comment'];
            $dataobject->maxstudents = $result['maxStudents'];
            $dataobject->teacherids = json_encode($result['teacherIds']);
            $DB->insert_record('local_moyclass_lessons', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Устанавливаем информацию о записях на все уроки школы
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_lesson_records() {
        global $DB;
        $DB->delete_records('local_moyclass_lessonsrecord');
        $api_service = new api_service();
        $results = $api_service->get_lesson_records();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->recordid = $result['id'];
            $dataobject->userid = $result['userId'];
            $dataobject->lessonid = $result['lessonId'];
            $dataobject->free = $result['free'];
            $dataobject->visit = $result['visit'];
            $dataobject->goodreason = $result['goodReason'];
            $dataobject->test = $result['test'];
            $DB->insert_record('local_moyclass_lessonsrecord', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     *
     * Устанавливаем статусы клиентов
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_client_statuses() {
        global $DB;
        $DB->delete_records('local_moyclass_clientstatuse');
        $api_service = new api_service();
        $results = $api_service->get_client_statuses();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->clientstatusid = $result['id'];
            $dataobject->name = $result['name'];
            $DB->insert_record('local_moyclass_clientstatuse', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Устанавливаем виды абонементов школы
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_subscriptions() {
        global $DB;
        $DB->delete_records('local_moyclass_subscriptions');
        $api_service = new api_service();
        $results = $api_service->get_subscriptions();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->subscriptionid = $result['id'];
            $dataobject->name = $result['name'];
            $dataobject->visitcount = $result['visitCount'];
            $dataobject->price = $result['price'];
            $dataobject->period = $result['period'];
            $DB->insert_record('local_moyclass_subscriptions', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Устанавливаем купленные абонементы учеников школы
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_user_subscriptions() {
        global $DB;
        $DB->delete_records('local_moyclass_usersubscript');
        $api_service = new api_service();
        $results = $api_service->get_user_subscriptions();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->externalid = $result['externalId'];
            $dataobject->usersubscriptionid = $result['id'];
            $dataobject->userid = $result['userId'];
            $dataobject->subscriptionid = $result['subscriptionId'];
            $dataobject->price = $result['price'];
            $dataobject->selldate = $result['sellDate'];
            $dataobject->begindate = $result['beginDate'];
            $dataobject->enddate = $result['endDate'];
            $dataobject->remindsumm = $result['remindSumm'];
            $dataobject->reminddate = $result['remindDate'];
            $dataobject->classids = $result['mainClassId'];
            $dataobject->courseids = json_encode($result['courseIds']);
            $dataobject->period = $result['period'];
            $dataobject->visitcount = $result['visitCount'];
            $dataobject->statusid = $result['statusId'];
            $dataobject->totalbilled = $result['stats']['totalBilled'];
            $dataobject->totalvisited = $result['stats']['totalVisited'];
            $dataobject->totalburned = $result['stats']['totalBurned'];
            $DB->insert_record('local_moyclass_usersubscript', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Устанавливаем успешные платежи учеников
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_payments() {
        global $DB;
        $DB->delete_records('local_moyclass_payments');
        $api_service = new api_service();
        $results = $api_service->get_payments();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->paymentid = $result['id'];
            $dataobject->userid = $result['userId'];
            $dataobject->date = $result['date'];
            $dataobject->summa = $result['summa'];
            $dataobject->comment = $result['comment'];
            $dataobject->optype = $result['optype'];
            $dataobject->paymenttypeid = $result['paymentTypeId'];
            $DB->insert_record('local_moyclass_payments', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }

    /**
     * Устанавливаем счета на оплату для учеников школы
     *
     * @return void
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function set_invoices() {
        global $DB;
        $DB->delete_records('local_moyclass_invoices');
        $api_service = new api_service();
        $results = $api_service->get_invoices();
        $transaction = $DB->start_delegated_transaction();
        foreach ($results as $result) {
            $dataobject = new stdClass();
            $dataobject->invoiceid = $result['id'];
            $dataobject->userid = $result['userId'];
            $dataobject->createdat = $result['createdAt'];
            $dataobject->price = $result['price'];
            $dataobject->payuntil = $result['payUntil'];
            $dataobject->usersubscriptionid = $result['userSubscriptionId'];
            $dataobject->payed = $result['payed'];
            $DB->insert_record('local_moyclass_invoices', $dataobject, false);
        }
        $DB->commit_delegated_transaction($transaction);
    }
}
