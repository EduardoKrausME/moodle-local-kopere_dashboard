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
 * Show the selected helpdesk category description below the dropdown.
 *
 * @module    koperedashboard_requests/category_description
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {
    "use strict";

    function init(descriptions) {
        var select = document.getElementById("id_categoryid");
        var description = document.getElementById("kopere_dashboard-requests-category-description");

        if (!select || !description) {
            return;
        }

        // Keep the description within the select's form field in all Moodle form layouts.
        select.insertAdjacentElement("afterend", description);

        function updateDescription() {
            var text = Object.prototype.hasOwnProperty.call(descriptions, select.value) ?
                descriptions[select.value] : "";
            description.textContent = text || "";
        }

        select.addEventListener("change", updateDescription);
        updateDescription();
    }

    return {init: init};
});
