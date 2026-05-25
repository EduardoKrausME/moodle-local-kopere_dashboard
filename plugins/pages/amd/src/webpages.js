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
 * webpages.js
 *
 * @package   koperedashboard_pages
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(["jquery", "jqueryui"], function ($) {
    "use strict";

    function bindAutoUrl(action) {
        var title = $("#title");
        var link = $("#link");
        var id = $("#id");

        title.on("focusout", function () {
            if (!title.val() || link.val()) {
                return;
            }

            $.ajax({
                method: "POST",
                url: M.cfg.wwwroot + "/local/kopere_dashboard/plugins/pages/ajax.php",
                data: {
                    action: action,
                    title: title.val(),
                    id: id.val()
                },
                dataType: "text"
            }).done(function (data) {
                link.val(data);
            }).fail(function () {
                if (window.console) {
                    window.console.warn(M.util.get_string("ajax_error", "koperedashboard_pages"));
                }
            });
        });
    }

    return {
        pageUrl: function () {
            bindAutoUrl("pageurl");
        },

        menuUrl: function () {
            bindAutoUrl("menuurl");
        },

        view_page: function () {
            $(".jquery-ui-tabs").tabs();
            $(".jquery-ui-accordion").accordion({
                heightStyle: "content"
            });
        }
    };
});
