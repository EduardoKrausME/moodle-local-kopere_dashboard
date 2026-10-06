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
 * Confirmation button handling.
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['core/notification'], function(Notification) {
    /**
     * Initialise confirmation action.
     *
     * @param {String} buttonId
     * @param {String} link
     */
    function action(buttonId, link) {
        var button = document.getElementById('btn-' + buttonId);
        var dialog = document.getElementById('confirm-' + buttonId);

        if (!button || !dialog) {
            return;
        }

        button.style.display = '';

        button.addEventListener('click', function(event) {
            event.preventDefault();

            var title = dialog.getAttribute('title') || '';
            var message = '';
            var paragraph = dialog.querySelector('p');

            if (paragraph) {
                message = paragraph.textContent;
            }

            Notification.confirm(
                title,
                message,
                'Yes',
                'No',
                function() {
                    window.location.href = link;
                }
            );
        });
    }

    return {
        action: action
    };
});