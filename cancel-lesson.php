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

use local_moyclass\actions;

global $PAGE, $OUTPUT, $CFG;
require_once(__DIR__ . '/../../config.php');

require_login();

$PAGE->set_url(new moodle_url("/local/moyclass/cancel-lesson.php"));
$PAGE->set_context(\context_system::instance());

$recordid = optional_param('lessonid', null, PARAM_INT);

$actions = new actions();
$actions->cancel_lesson($recordid);

redirect($CFG->wwwroot . '/my', get_string('moyclass_delete_record_success', 'local_moyclass'));
