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
 * ${PLUGINNAME} file description here.
 *
 * @package    ${PLUGINNAME}
 * @copyright  2022 mac <${USEREMAIL}>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_moyclass\api_service, local_moyclass\manager_db, local_moyclass\actions;

global $DB, $USER;
$pluginname = 'moyclass';
require_once(__DIR__ . '/../../config.php');
include($CFG->dirroot . '/local/' . $pluginname . '/widgets/dashboard.php');

$PAGE->set_url(new moodle_url("/local/moyclass/index.php"));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('moyclass_managepage', "local_moyclass"));
$PAGE->set_heading(get_string('moyclass_managepage', "local_moyclass"));
$PAGE->requires->js_call_amd('local_moyclass/confirm');

//$manager = new manager_db();
//$manager->set_lesson_records();
//$manager->set_lessons();
//
//$sync = new sync_users();
//
//$api = new api_service();

echo $OUTPUT->header();

//echo $USER->email;

echo dashboard();
//echo "<pre>";
//print_r($result);
//echo "</pre>";

echo $OUTPUT->footer();
