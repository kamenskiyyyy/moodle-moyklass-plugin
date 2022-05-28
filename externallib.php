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

defined('MOODLE_INTERNAL') || die();

use local_moyclass\actions;

require_once($CFG->libdir . '/externallib.php');

class local_moyclass_external extends external_api {
    public static function delete_record($recordid): string {
        $params = self::validate_parameters(self::delete_record_parameters(), ['recordid' => $recordid]);
        $actions = new actions();
        return $actions->cancel_lesson($recordid);
    }

    public static function delete_record_parameters() {
        return new external_function_parameters(['recordid' => new external_value(PARAM_INT, 'id of record')],);
    }

    public static function delete_record_returns() {
        return new external_value(PARAM_BOOL, 'Вы отменили урок');
    }
}
