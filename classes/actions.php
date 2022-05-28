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

use local_moyclass\api_service;

class actions {
    public function cancel_lesson($recordId) {
        global $DB;
        $transaction = $DB->start_delegated_transaction();
        $removeDB = $DB->delete_records('local_moyclass_lessonsrecord', ['recordid' => "$recordId"]);
        $api = new api_service();
        $removeApi = $api->cancel_lesson($recordId);
        if ($removeDB && $removeApi) {
            $DB->commit_delegated_transaction($transaction);
            \core\notification::add("Урок успешно отменен", \core\notification::SUCCESS);
        }
        return true;
    }
}
