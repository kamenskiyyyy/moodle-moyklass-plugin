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

global $PAGE, $OUTPUT;
require_once(__DIR__ . '/../../config.php');

use local_moyclass\pages\subscriptions_page;

$PAGE->set_url(new moodle_url("/local/moyclass/subscriptions.php"));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('moyclass_page_subscriptions_title', "local_moyclass"));
$PAGE->set_heading(get_string('moyclass_page_subscriptions_title', "local_moyclass"));
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();

$subscriptions_page = new subscriptions_page();

echo $subscriptions_page->render();

echo $OUTPUT->footer();
