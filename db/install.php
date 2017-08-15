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

function xmldb_local_kopere_dashboard_install() {
    set_config('enablegravatar', 1);
    set_config('gravatardefaulturl', 'mm');
    // set_config('timezone', 'America/Sao_Paulo');
    // set_config ( 'autolang', '0' );
    // set_config ( 'langmenu', '0' );
    // set_config ( 'authloginviaemail', '1' );

    set_config('webpages_theme', 'standard', 'local_kopere_dashboard');
    set_config('notificacao-template', 'Cinza.html', 'local_kopere_dashboard');

    return true;
}

function changeConfigTable($name, $newVale) {
    global $DB;

    $config = $DB->get_record('config', array('name' => $name));
    if ($config) {
        $config->value = $newVale;
        $DB->update_record('config', $config);
    }
}

\local_kopere_dashboard\install\ReportInstall::createCategores();
\local_kopere_dashboard\install\ReportInstall::createReports();

\local_kopere_dashboard\install\UsersImportInstall::installOrUpdate();