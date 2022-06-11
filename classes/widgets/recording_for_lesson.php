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

class recording_for_lesson {
    public function render() {
        global $OUTPUT;

        $templatecontext = (object) [];

        // TODO: не забыть включить запись на занятия по email в CRM
        $this->set_user_email();

        return $OUTPUT->render_from_template('local_moyclass/widgets/recording_for_lesson', $templatecontext);
    }

    private function set_user_email() {
        global $USER;

        $email = $USER->email;

        echo "<script defer>
                console.log('$email');
                const allInputEmails = ['wdgMoyklass57360Formemail', 'wdgMoyklass57194Formemail', 'wdgMoyklass57363Formemail'];
                
                function set_email_value(input) {
                    const element = document.getElementById(input);
                    if (element) {
                       element.value = '$email';
                       element.style.display = 'none';
                    }
                }
                
                setInterval(() => {
                    set_email_value(allInputEmails[0]);
                }, 2000);
                
                setInterval(() => {
                    set_email_value(allInputEmails[1]);
                }, 2000);
                
                setInterval(() => {
                    set_email_value(allInputEmails[2]);
                }, 2000);

              </script>";
    }
}
