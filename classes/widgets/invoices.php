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

class invoices {
    public function get_invoices() {
        global $OUTPUT, $DB, $USER;
        $student = $DB->get_record("local_moyclass_students", ['email' => $USER->email]);
        $invoices = $DB->get_records('local_moyclass_invoices', ['userid' => $student->studentid, 'payed' => 0]);
        $invoices_with_render = '';

        if (!$invoices) {
            return null;
        }

        foreach ($invoices as $invoice) {
            if (!$invoice->usersubscriptionid) {
                return null;
            } else {
                $invoices_with_render .= $this->get_invoice($invoice);
            }
        }

        if (!$invoices_with_render) {
            return null;
        }

        $templatecontext = (object) ['invoices' => $invoices_with_render];

        return $OUTPUT->render_from_template('local_moyclass/widgets/invoices', $templatecontext);
    }

    public function get_invoice($invoice) {
        global $OUTPUT, $DB, $USER;
        $student = $DB->get_record("local_moyclass_students", ['email' => $USER->email]);
        $user_subscription =
            $DB->get_record('local_moyclass_usersubscript', ["usersubscriptionid" => $invoice->usersubscriptionid]);
        $subscription = $DB->get_record('local_moyclass_subscriptions', ["subscriptionid" => $user_subscription->subscriptionid]);

        if (!$user_subscription || !$subscription) {
            return null;
        }

        $templatecontext = (object) [
            'invoice' => $invoice,
            'name' => $subscription->name,
            'payLinkKey' => $student->paylinkkey
        ];

        return $OUTPUT->render_from_template('local_moyclass/widgets/invoice', $templatecontext);
    }

}
