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

use local_moyclass\api_service;
use local_moyclass\manager_db;
use local_moyclass\sync_users;

global $DB;

require_once(__DIR__ . '/../../config.php');

$pluginname = 'moyclass';
include($CFG->dirroot.'/local/' . $pluginname . '/widgets/test.php');

$PAGE->set_url(new moodle_url('/local/moyclass/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('moyclass_managepage', "local_moyclass"));
$PAGE->set_heading(get_string('moyclass_managepage', "local_moyclass"));

//$manager = new manager_db();
//$result = $manager->set_lesson_records();
//
//$sync = new sync_users();
//
//$api = new api_service();
//$result = $api->get_payments();

echo $OUTPUT->header();

//echo test();
//echo "<pre>";
//print_r($result);
//echo "</pre>";

echo $OUTPUT->footer();
