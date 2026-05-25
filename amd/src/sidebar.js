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
 * sidebar.js
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(["jquery"], function($) {
    /**
     * Return whether the viewport is using the mobile sidebar mode.
     *
     * @return {boolean}
     */
    function isMobileViewport() {
        return window.matchMedia("(max-width: 991.98px)").matches;
    }

    /**
     * Update aria-expanded for all open buttons in the shell.
     *
     * @param {HTMLElement} shell
     * @param {boolean} isOpen
     */
    function syncButtons(shell, isOpen) {
        shell.querySelectorAll("[data-kopere_dashboard-sidebar-open]").forEach(function(button) {
            button.setAttribute("aria-expanded", isOpen ? "true" : "false");
        });
    }

    /**
     * Close the sidebar.
     *
     * @param {HTMLElement} shell
     */
    function closeSidebar(shell) {
        shell.classList.remove("is-sidebar-open");
        document.body.classList.remove("has-kopere_dashboard-sidebar-open");
        syncButtons(shell, false);
    }

    /**
     * Open the sidebar.
     *
     * @param {HTMLElement} shell
     */
    function openSidebar(shell) {
        shell.classList.add("is-sidebar-open");
        document.body.classList.add("has-kopere_dashboard-sidebar-open");
        syncButtons(shell, true);
    }

    /**
     * Bind a shell instance.
     *
     * @param {HTMLElement} shell
     */
    function bindShell(shell) {
        if (!shell) {
            return;
        }

        let openButtons = shell.querySelectorAll("[data-kopere_dashboard-sidebar-open]");
        let closeButtons = shell.querySelectorAll("[data-kopere_dashboard-sidebar-close]");
        let backdrop = shell.querySelector("[data-kopere_dashboard-sidebar-backdrop]");

        openButtons.forEach(function(button) {
            button.addEventListener("click", function() {
                if (shell.classList.contains("is-sidebar-open")) {
                    closeSidebar(shell);
                    return;
                }

                openSidebar(shell);
            });
        });

        closeButtons.forEach(function(button) {
            button.addEventListener("click", function() {
                closeSidebar(shell);
            });
        });

        if (backdrop) {
            backdrop.addEventListener("click", function() {
                closeSidebar(shell);
            });
        }

        shell.querySelectorAll(".kopere_dashboard-card-sidenav a").forEach(function(link) {
            link.addEventListener("click", function() {
                if (isMobileViewport()) {
                    closeSidebar(shell);
                }
            });
        });

        syncButtons(shell, false);
    }

    /**
     * Initialise the responsive sidebar.
     */
    function init() {
        let shells = Array.prototype.slice.call(document.querySelectorAll("[data-kopere_dashboard-shell]"));
        if (!shells.length) {
            return;
        }

        shells.forEach(function(shell) {
            bindShell(shell);
        });

        document.addEventListener("keydown", function(event) {
            if (event.key !== "Escape") {
                return;
            }

            shells.forEach(function(shell) {
                closeSidebar(shell);
            });
        });

        $(window).on("resize", function() {
            if (isMobileViewport()) {
                return;
            }

            shells.forEach(function(shell) {
                closeSidebar(shell);
            });
        });
    }

    return {
        init: init
    };
});
