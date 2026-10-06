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
 * @module     local_kopere_dashboard/button_icon
 * @copyright  Eduardo Kraus
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Notification from 'core/notification';

export const action = (buttonId, link) => {
    const button = document.getElementById(`btn-${buttonId}`);
    const dialog = document.getElementById(`confirm-${buttonId}`);

    if (!button || !dialog) {
        return;
    }

    button.style.display = '';

    button.addEventListener('click', (event) => {
        event.preventDefault();

        const title = dialog.getAttribute('title') || '';
        const message = dialog.querySelector('p')?.textContent || '';

        Notification.confirm(
            title,
            message,
            'Yes',
            'No',
            () => {
                window.location.href = link;
            }
        );
    });
};