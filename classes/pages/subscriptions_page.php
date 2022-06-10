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

namespace local_moyclass\pages;

use local_moyclass\pages;
use local_moyclass\widgets\subscriptions;

class subscriptions_page {
    public function render() {
        global $OUTPUT;

        $templatecontext = (object) [
            'subscriptions' => $this->get_full_subscriptions(),
        ];

        return $OUTPUT->render_from_template('local_moyclass/pages/subscriptions/container', $templatecontext);
    }

    private function get_full_subscriptions() {
        global $OUTPUT, $DB, $USER;
        $student = $DB->get_record("local_moyclass_students", ['email' => $USER->email]);
        $user_subscriptions = $DB->get_records('local_moyclass_usersubscript', ['userid' => $student->studentid]);
        $subscriptions_active = '';
        $subscriptions_inactive = '';

        $error = new pages();
        if (!$user_subscriptions) {
            return $error->error_alert("Абонементы не найдены");
        }

        $widget_subscription = new subscriptions();

        foreach ($user_subscriptions as $user_subscription) {
            switch ($user_subscription->statusid) {
                case 2:
                    $subscriptions_active .= $widget_subscription->get_subscription($user_subscription);
                    break;
                case 4:
                default:
                    $subscriptions_inactive .= $widget_subscription->get_subscription($user_subscription);
                    break;
            }
        }

        $templatecontext = (object) [
            'subscriptions_active' => $subscriptions_active,
            'subscriptions_inactive' => $subscriptions_inactive,
        ];

        return $OUTPUT->render_from_template('local_moyclass/pages/subscriptions/accordion', $templatecontext);
    }
}
