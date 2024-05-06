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
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_local_kopere_dashboard_install() {
    set_config('enablegravatar', 1);
    set_config('gravatardefaulturl', 'mm');

    set_config('webpages_theme', 'standard', 'local_kopere_dashboard');
    set_config('notificacao-template', 'Cinza.html', 'local_kopere_dashboard');

    $fonts = "<style>\n@import url('https://fonts.googleapis.com/css2?family=Acme" .
        "&family=Almendra:ital,wght@0,400;0,700;1,400;1,700" .
        "&family=Bad+Script" .
        "&family=Dancing+Script:wght@400..700" .
        "&family=Great+Vibes" .
        "&family=Marck+Script" .
        "&family=Nanum+Pen+Script" .
        "&family=Orbitron:wght@400..900" .
        "&family=Ubuntu+Condensed" .
        "&family=Ubuntu+Mono:ital,wght@0,400;0,700;1,400;1,700" .
        "&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap');\n</style>";
    set_config('kopere_dashboard_pagefonts', $fonts);

    return true;
}

\local_kopere_dashboard\install\report_install::create_categores();
\local_kopere_dashboard\install\report_install::create_reports();

\local_kopere_dashboard\install\users_import_install::install_or_update();
