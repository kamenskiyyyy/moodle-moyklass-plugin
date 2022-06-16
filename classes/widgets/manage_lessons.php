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

namespace local_moyclass\widgets;

class manage_lessons {
    public function get_lessons() {
        global $OUTPUT, $DB, $CFG, $USER;
        $date_today = date('Y-m-d');
        $teacher = $DB->get_record("local_moyclass_managers", ['email' => $USER->email]);
        $lessons = $DB->get_records('local_moyclass_lessons', ['date' => $date_today], 'begintime');
        $lessons_with_data = '';

        foreach ($lessons as $lesson) {
            $teachers_id = json_decode($lesson->teacherids);
            if (in_array($teacher->managerid, $teachers_id)) {
                $lessons_with_data .= $this->get_lesson($lesson);
            }
        }

        $templatecontext = (object)['lessons' => $lessons_with_data,
        ];

        return $OUTPUT->render_from_template('local_moyclass/widgets/lessons', $templatecontext);
    }

    private function get_lesson($lesson) {
        global $OUTPUT, $DB, $CFG;

        $name_group = $DB->get_record('local_moyclass_classes', ['classid' => "$lesson->classid"]);
        $students_list = '';

        $lesson_records = $DB->get_records('local_moyclass_lessonsrecord', ['lessonid' => $lesson->lessonid]);
        foreach ($lesson_records as $record) {
            $student = $DB->get_record('local_moyclass_students', ['studentid' => $record->userid]);
            $students_list .= $this->get_student($student);
        }

        $originalDate = $lesson->date;
        $newDate = date('d.m.Y', strtotime($originalDate));

        $templatecontext = (object)[
            'lesson' => $lesson,
            'newDate' => $newDate,
            'name_group' => $name_group->name,
            'students' => $students_list,
        ];

        return $OUTPUT->render_from_template('local_moyclass/widgets/manage_lesson', $templatecontext);
    }

    private function get_student($student) {
        global $OUTPUT, $DB, $CFG;

        $templatecontext = (object)[
            'student' => $student
        ];

        return $OUTPUT->render_from_template('local_moyclass/pages/teacher/student_lesson', $templatecontext);
    }
}
