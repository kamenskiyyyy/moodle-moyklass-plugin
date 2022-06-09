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

namespace local_moyclass\notifications;

use core\message\message;
use core_user;

class manager {
    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function send_email($userEmail, $subject, $body) {
        $message = new message();
        $message->component = 'local_moyclass';
        $message->name = 'info';
        $message->userfrom = core_user::get_noreply_user();
        $message->userto = core_user::get_user_by_email($userEmail);
        $message->subject = $subject;
        $message->fullmessage = 'message body';
        $message->fullmessageformat = FORMAT_HTML;
        $message->fullmessagehtml = $body;
        $message->notification = 1;
        return message_send($message);
    }
}
