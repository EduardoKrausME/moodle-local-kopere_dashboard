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
 * search.js
 *
 * @package   koperedashboard_users
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(["jquery", "core/str"], function ($, Str) {
    var timer = null;

    function escapeHtml(text) {
        return $("<div>").text(text).html();
    }

    function normalizeUrl(url) {
        if (typeof url === "string") {
            return url;
        }

        if (url && typeof url === "object") {
            if (typeof url.url === "string") {
                return url.url;
            }
            if (typeof url.href === "string") {
                return url.href;
            }
            if (typeof url.out === "string") {
                return url.out;
            }
        }

        return M.cfg.wwwroot + "/local/kopere_dashboard/plugins/users/";
    }

    function renderSuggest($box, users) {
        if (!users.length) {
            $box.hide().empty();
            return;
        }

        var html = "";
        users.forEach(function (u) {
            html += '<a href="' + escapeHtml(normalizeUrl(u.url)) + '" class="list-group-item list-group-item-action">';
            html += '<div style="display:flex;justify-content:space-between;gap:8px;">';
            html += '<div><strong>' + escapeHtml(u.name) + '</strong><br><small style="opacity:.8;">' +
                escapeHtml(u.email) + "</small></div>";
            html += '<div style="text-align:right;"><small style="opacity:.8;">' + escapeHtml(u.username) + "</small></div>";
            html += "</div>";
            html += "</a>";
        });

        $box.html(html).show();
    }

    function doSearch(query) {
        var $box = $("#kopere_dashboard-users-suggest");

        if (!query || query.length < 2) {
            $box.hide().empty();
            return;
        }

        $.ajax({
            url: M.cfg.wwwroot + "/local/kopere_dashboard/plugins/users/ajax.php",
            method: "GET",
            dataType: "json",
            data: {
                q: query,
                sesskey: M.cfg.sesskey
            }
        }).done(function (data) {
            renderSuggest($box, (data && data.users) ? data.users : []);
        }).fail(function () {
            $box.hide().empty();
        });
    }

    function init() {
        var $input = $("#kopere_dashboard-users-search");
        var $btn = $("#kopere_dashboard-users-search-btn");
        var $box = $("#kopere_dashboard-users-suggest");

        if (!$input.length) {
            return;
        }

        function submitForm() {
            var q = ($input.val() || "").trim();
            window.location = M.cfg.wwwroot + "/local/kopere_dashboard/plugins/users/?q=" + encodeURIComponent(q);
        }

        $btn.on("click", function (e) {
            e.preventDefault();
            submitForm();
        });

        $input.on("keydown", function (e) {
            if (e.key == "Enter") {
                e.preventDefault();
                submitForm();
            }
            if (e.key == "Escape") {
                $box.hide().empty();
            }
        });

        $input.on("keyup", function () {
            var q = ($input.val() || "").trim();
            clearTimeout(timer);
            timer = setTimeout(function () {
                doSearch(q);
            }, 250);
        });

        $(document).on("click", function (e) {
            if (!$(e.target).closest("#kopere_dashboard-users-suggest, #kopere_dashboard-users-search").length) {
                $box.hide().empty();
            }
        });
    }

    return {
        init: init
    };
});
