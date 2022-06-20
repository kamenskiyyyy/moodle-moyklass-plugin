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

use core_reportbuilder\local\filters\date;
use local_moyclass\pages;

class lessons {
    /**
     *  Ищем все записи на уроки
     * @return mixed
     * @throws \dml_exception
     */
    public function get_lessons() {
        global $OUTPUT, $DB, $CFG, $USER;
        $student = $DB->get_record("local_moyclass_students", ['email' => $USER->email]);
        $sql = "SELECT * FROM {local_moyclass_lessonsrecord} WHERE `timestamp` > :now AND `userid` = :userid AND `visit` = 0 ORDER BY `timestamp` ASC LIMIT 3";
        $records = $DB->get_records_sql($sql, ['now'=>strtotime('now'), 'userid'=>$student->studentid]);
        $lessons_with_data = '';

        foreach ($records as $record) {
            $lessons_with_data .= $this->get_lesson($record);
        }

        $templatecontext = (object) [
            'lessons' => $lessons_with_data,
            'linkPageLessons' => $CFG->wwwroot . '/local/moyclass/lessons.php'
        ];

        return $OUTPUT->render_from_template('local_moyclass/widgets/lessons', $templatecontext);
    }

    public function get_full_lessons() {
        global $OUTPUT, $DB, $USER;
        $student = $DB->get_record("local_moyclass_students", ['email' => $USER->email]);
        $records = $DB->get_records("local_moyclass_lessonsrecord", ['userid' => $student->studentid], "timestamp");
        $lessons_with_data = '';
        $last_lessons_with_data = '';

        $error = new pages();
        if (!$records) {
            return $error->error_alert("Записи на занятия не найдены");
        }

        foreach ($records as $record) {
            if ($record->visit == 1) {
                $last_lessons_with_data .= $this->get_lesson($record);
            } else {
                $lessons_with_data .= $this->get_lesson($record);
            }
        }

        $templatecontext = (object) [
            'lessons_active' => $lessons_with_data,
            'lessons_inactive' => $last_lessons_with_data,
        ];

        return $OUTPUT->render_from_template('local_moyclass/pages/lessons/accordion', $templatecontext);
    }

    /**
     * Отображаем информацию по каждому уроку
     *
     * @param $lesson_id
     * @param $record
     * @return mixed
     * @throws \dml_exception
     */
    private function get_lesson($record) {
        global $OUTPUT, $DB, $CFG;
        $lesson = $DB->get_record('local_moyclass_lessons', ["lessonid" => $record->lessonid]);

        if (!$lesson) {
            return null;
        }

        $name_group = $DB->get_record('local_moyclass_classes', ['classid' => "$lesson->classid"]);
        $teachers_array = json_decode($lesson->teacherids);
        $teachers_string = '';
        foreach ($teachers_array as $teacher) {
            $result = $DB->get_record('local_moyclass_managers', ['managerid' => $teacher]);
            $teachers_string .= "$result->name ";
        }

        $originalDate = $lesson->date;
        $newDate = date('d.m.Y', strtotime($originalDate));

        $block_cancel = $record->timestamp - strtotime('now') <= 3600;

        $templatecontext = (object) [
            'lesson' => $lesson,
            'newDate' => $newDate,
            'name_group' => $name_group->name,
            'teachers' => $teachers_string,
            'recordId' => $record->recordid,
            'visited' => $record->visit,
            'block_cancel' => $block_cancel,
            'cancelUrl' => $CFG->wwwroot . '/local/moyclass/cancel-lesson.php',
            'dayOfWeek' => date('l', strtotime($originalDate)),
        ];

        return $OUTPUT->render_from_template('local_moyclass/widgets/lesson', $templatecontext);
    }
}
