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

use context_user;
use core\message\message;
use core_user;
use local_moyclass\notifications\emails;
use stdClass;

class manager {
    /**
     * @throws \file_exception
     * @throws \stored_file_creation_exception
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function send_welcome_email() {
        global $USER;
        $user = $USER;

        $email_templates = new emails();
        $message = new message();
        $message->component = 'local_moyclass';
        $message->name = 'info';
        $message->userfrom = core_user::get_noreply_user(); // If the message is 'from' a specific user you can set them here
        $message->userto = $user;
        $message->subject = 'message subject 1';
        $message->fullmessage = 'message body';
        $message->fullmessageformat = FORMAT_MARKDOWN;
        $message->fullmessagehtml = $email_templates->get_welcome_email();
        $message->notification = 1; // Because this is a notification generated from Moodle, not a user-to-user message
        return message_send($message);
    }
}
