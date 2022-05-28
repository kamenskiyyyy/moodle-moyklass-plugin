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

defined('MOODLE_INTERNAL') || die();

function xmldb_local_moyclass_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2022052802) {

        // Define table solib to be created
        $table = new xmldb_table('local_moyclass_lessonsrecord');

        // Adding fields to table solib
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, false);
        $table->add_field('lessonid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, false);
        $table->add_field('free', XMLDB_TYPE_INTEGER, '2', null, false, false, null);
        $table->add_field('visit', XMLDB_TYPE_INTEGER, '2', null, false, false, null);
        $table->add_field('goodreason', XMLDB_TYPE_INTEGER, '2', null, false, false, null);
        $table->add_field('test', XMLDB_TYPE_INTEGER, '2', null, false, false, null);

        // Adding keys to table solib
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for solib
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // solib savepoint reached
        upgrade_plugin_savepoint(true, 2022052802, 'local', 'moyclass');
    }

    return true;
}
