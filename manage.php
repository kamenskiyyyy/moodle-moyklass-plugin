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

$PAGE->set_url(new moodle_url('/local/moyclass/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('moyclass_managepage', "local_moyclass"));
$PAGE->set_heading(get_string('moyclass_managepage', "local_moyclass"));

$manager = new manager_db();
//$result = $manager->set_students();

//$result = $DB->get_record('local_moyclass_students', ['id'=>1]);

$sync = new sync_users();
$result = $sync->set_managers_in_moodle();
//$check = $sync->check_status_client($result->clientstateid);

$api = new api_service();
//$results = $api->get_students();

echo $OUTPUT->header();

echo "<pre>";
//print_r('status ' . $check);
print_r($result);
//foreach ($results as $result) {
//    $attributes = $result['attributes'];
//    foreach ($attributes as $index => $alias) {
//        if ($alias['attributeAlias'] === 'city') {
//            //print_r($attributes[$index]['value']);
//            print_r($alias);
//        }
//    }
//}
echo "</pre>";

echo $OUTPUT->footer();
