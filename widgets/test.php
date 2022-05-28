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

function test(): string {
    global $DB, $USER;
    $template = get_styles();

    $template .= get_lessons();

    return $template;
}

function get_lessons() {
    global $OUTPUT, $DB, $USER;
    $lessons = $DB->get_records('local_moyclass_lessons');
    $templatecontext = (object)[
        'test' => 'test',
        'lessons' => []
    ];

    return $OUTPUT->render_from_template('local_moyclass/lessons', $templatecontext);
}

function get_styles(): string {
    return '<script
                type="text/javascript" defer
                src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.1.0/mdb.min.js"
                ></script>
                <style>
                @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css");
                @import url("https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap");
                @import url("https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.1.0/mdb.min.css");
            </style>';
}
