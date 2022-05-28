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

function dashboard(): string {
    global $DB, $USER;
    $template = get_styles();

    $template .= render_dashboard();

    return $template;
}

function render_dashboard() {
    global $OUTPUT, $DB, $USER;

    $templatecontext = (object) ['lessons' => get_lessons(),
    ];

    return $OUTPUT->render_from_template('local_moyclass/dashboard', $templatecontext);
}

function get_lessons() {
    global $OUTPUT, $DB, $USER;
    // TODO: в будущем дожен быть email от $USER->email
    $student = $DB->get_record("local_moyclass_students", ['email' => "79217821386@mail.ru"]);
    $records = $DB->get_records("local_moyclass_lessonsrecord", ['userid' => $student->studentid], "id DESC", "*", 0, 3);
    $lessons_with_data = '';

    if (!$records) {
        return "Записи на занятия не найдены";
    }

    foreach ($records as $record) {
       $lessons_with_data .= get_lesson($record->lessonid, $record);
    }

    $templatecontext = (object) [
        'lessons' => $lessons_with_data,
    ];

    return $OUTPUT->render_from_template('local_moyclass/lessons', $templatecontext);
}

function get_lesson($lessonid, $record) {
    global $OUTPUT, $DB;
    $lesson = $DB->get_record('local_moyclass_lessons', ["lessonid" => "$lessonid"]);

    if (!$lesson) {
       return error_alert('Урок не найден');
    }

    $name_group = $DB->get_record('local_moyclass_classes', ['classid' => "$lesson->classid"]);
    $teachers_array = json_decode($lesson->teacherids);
    $teachers_string = '';
    foreach ($teachers_array as $teacher) {
        $result = $DB->get_record('local_moyclass_managers', ['managerid' => $teacher]);
        $teachers_string .= "$result->name ";
    }

    $templatecontext = (object) [
        'lesson' => $lesson,
        'name_group' => $name_group->name,
        'teachers' => $teachers_string,
        'recordId' => $record->recordid
    ];

    return $OUTPUT->render_from_template('local_moyclass/lesson', $templatecontext);
}

function get_groups() {

}

function error_alert($message) {
    global $OUTPUT;
    $templatecontext = (object) [
        'message' => $message,
    ];
    return $OUTPUT->render_from_template('local_moyclass/error_alert', $templatecontext);
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
