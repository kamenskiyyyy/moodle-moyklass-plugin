// This file is part of Moodle Course Rollover Plugin
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
 * @package
 * @author      Nikolay
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/modal_factory', 'core/str', "core/modal_events", 'core/ajax', 'core/notification'],
    function($, ModalFactory,
             String, ModalEvents,
             Ajax, Notification) {
        const trigger = $('.local_moyclass_delete_button');
        ModalFactory.create({
            type: ModalFactory.types.SAVE_CANCEL,
            title: String.get_string('moyclass_delete_record_title', 'local_moyclass'),
            body: String.get_string('moyclass_delete_record_desc', 'local_moyclass'),
            preShowCallback: function(triggerElement, modal) {
                triggerElement = $(triggerElement);
                let classString = triggerElement[0].classList[0];
                let recordid = classString.substr(classString.lastIndexOf('local_recordid') + 'local_recordid'.length);

                modal.params = {'recordid': recordid};
                modal.setSaveButtonText(String.get_string('moyclass_delete_record_title', 'local_moyclass'));
            },
            large: true,
        }, trigger)
            .done(function(modal) {
                // Do what you want with your new modal.
                modal.getRoot().on(ModalEvents.save, function(e) {
                    e.preventDefault();

                    let footer = Y.one('.modal-footer');
                    footer.setContent(String.get_string('moyclass_delete_record_loading', "local_moyclass"));
                    let spinner = M.util.add_spinner(Y, footer);
                    spinner.show();

                    let request = {
                        methodname: "local_moyclass_delete_record",
                        args: modal.params,
                    };

                    Ajax.call([request])[0]
                        .done(function(data) {
                            if (data === true) {
                                window.location.reload();
                            } else {
                                Notification.addNotification({
                                    message: String.get_string("moyclass_delete_record_fail", "local_moyclass"),
                                    type: "error",
                                });
                            }
                        }).fail(Notification.exception);
                });
            });
    });
