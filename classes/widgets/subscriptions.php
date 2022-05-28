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

namespace local_moyclass\widgets;

use local_moyclass\pages;

class subscriptions {
    public function get_subscriptions() {
        global $OUTPUT, $DB, $USER;
        // TODO: в будущем дожен быть email от $USER->email
        $student = $DB->get_record("local_moyclass_students", ['email' => "pavlyshin96@mail.ru"]);
        $user_subscriptions = $DB->get_records('local_moyclass_usersubscript', ['userid' => $student->studentid]);
        $subscriptions = '';

        $error = new pages();
        if (!$user_subscriptions) {
            return $error->error_alert("Абонементы не найдены");
        }

        foreach ($user_subscriptions as $user_subscription) {
            $subscriptions .= $this->get_subscription($user_subscription);
        }

        $templatecontext = (object) ['subscriptions' => $subscriptions,
        ];

        return $OUTPUT->render_from_template('local_moyclass/subscriptions', $templatecontext);
    }

    public function get_subscription($user_subscription) {
        global $OUTPUT, $DB;
        $subscription = $DB->get_record('local_moyclass_subscriptions', ['subscriptionid' => $user_subscription->subscriptionid]);

        $error = new pages();
        if (!$subscription) {
            return $error->error_alert("Абонемент не найден");
        }

        $classes_array = json_decode($user_subscription->classids);
        $classes_string = '';
        foreach ($classes_array as $class) {
            $result = $DB->get_record('local_moyclass_classes', ['classid'=>$class]);
            $classes_string .= "$result->name ";
        }

        $originalSellDate = $user_subscription->selldate;
        $newSellDate = date('d.m.Y', strtotime($originalSellDate));

        $templatecontext = (object) [
            'subscription' => $user_subscription,
            'name' => $subscription->name,
            'name_group'=>$classes_string,
            'newSellDate' => $newSellDate
        ];

        return $OUTPUT->render_from_template('local_moyclass/subscription', $templatecontext);
    }
}
