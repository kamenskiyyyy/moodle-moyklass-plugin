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

if ($hassiteconfig) {

    $ADMIN->add('localplugins', new admin_category('local_moyclass_category', get_string('pluginname', 'local_moyclass')));

    $settings = new admin_settingpage('local_moyclass', get_string('moyclass_settings', 'local_moyclass'));
    $ADMIN->add('local_moyclass_category', $settings);

    $settings->add(new admin_setting_configtext('local_moyclass/apikey', get_string('moyclass_settings_apikey', 'local_moyclass'),
        get_string('moyclass_settings_apikey_desc', 'local_moyclass'), ''));

}
