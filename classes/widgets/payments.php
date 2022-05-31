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

class payments {
    public function get_payments() {
        global $OUTPUT, $DB, $USER;
        // TODO: в будущем дожен быть email от $USER->email
        $student = $DB->get_record("local_moyclass_students", ['email' => "pavlyshin96@mail.ru"]);
        $payments = $DB->get_records('local_moyclass_payments', ['userid' => $student->studentid]);

        $error = new pages();
        if (!$payments) {
            return $error->error_alert("Платежи отсутствуют");
        }

        $templatecontext = (object) [
            'payments' => array_values($payments),
        ];

        return $OUTPUT->render_from_template('local_moyclass/payment', $templatecontext);
    }

    public function get_invoices() {
        global $OUTPUT, $DB, $USER;
        // TODO: в будущем дожен быть email от $USER->email
        $student = $DB->get_record("local_moyclass_students", ['email' => "pavlyshin96@mail.ru"]);
        $payments = $DB->get_records('local_moyclass_invoices', ['userid' => $student->studentid]);

        $error = new pages();
        if (!$payments) {
            return $error->error_alert("Платежи отсутствуют");
        }
    }
}
