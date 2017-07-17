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
 * @created    13/05/17 13:28
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\Button;
use local_kopere_dashboard\html\Table;
use local_kopere_dashboard\util\BytesUtil;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Mensagem;
use local_kopere_dashboard\util\ServerUtil;
use local_kopere_dashboard\util\TitleUtil;

class Backup {
    public function dashboard() {
        DashboardUtil::startPage('Backup');

        echo '<div class="element-box">';

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            Mensagem::printWarning(get_string_kopere('backup_windows'));
        } else {
            if (ServerUtil::isFunctionEnabled('shell_exec')) {
                Mensagem::printInfo(get_string_kopere('backup_hours'));
                echo '<div style="text-align: center;">
                          <p>'.get_string_kopere('backup_sleep').'</p>';
                Button::add(get_string_kopere('backup_newnow'), 'Backup::execute');
                echo '</div>';
                echo '</div>';
            } else {
                Mensagem::printDanger(get_string_kopere('backup_noshell'));
            }

            $backups = glob(self::getBackupPath(false).'backup_*');
            $backupsLista = array();
            foreach ($backups as $backup) {
                preg_match("/backup_(\d+)-(\d+)-(\d+)-(\d+)-(\d+).tar.gz/", $backup, $p);
                $backupsLista[] = array(
                    'file' => $p[0],
                    'data' => $p[3].'/'.$p[2].'/'.$p[1].' às '.$p[4].':'.$p[5],
                    'size' => BytesUtil::sizeToByte(filesize($backup)),
                    'acoes' => "<div class=\"text-center\">
                                    ".Button::icon('download', "Backup::download&file={$p[0]}")."&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp; ".Button::icon('delete', "Backup::delete&file={$p[0]}")."
                                </div>"
                );
            }

            // echo '<div class="element-box">
            // <h3>Agendamento de backups</h3>';
            // Button::add ( 'Agendar Backup', 'Backup::scheduling' );
            // echo '</div>';

            echo '<div class="element-box">';
            TitleUtil::printH3('backup_list');

            if (isset($backupsLista[0])) {
                $table = new Table();
                $table->addHeader(get_string_kopere('backup_list_file'), 'file');
                $table->addHeader(get_string_kopere('backup_list_created'), 'data');
                $table->addHeader(get_string_kopere('backup_list_size'), 'size');
                $table->addHeader(get_string_kopere('backup_list_action'), 'acoes');

                $table->setRow($backupsLista);
                $table->close(true);
            } else {
                Mensagem::printWarning(get_string_kopere('backup_none'));
            }
        }

        echo '</div>';

        DashboardUtil::endPage();
    }

    public function execute() {
        global $CFG;

        if (!ServerUtil::isFunctionEnabled('shell_exec')) {
            Mensagem::agendaMensagemDanger(get_string_kopere('backup_noshell'));
            Header::location('Backup::dashboard');
        }

        $sqlFullPath = $this->getBackupPath().'backup_'.date('Y-m-d-H-i');

        $comando = "/usr/bin/mysqldump -h {$CFG->dbhost} -u {$CFG->dbuser} -p{$CFG->dbpass} {$CFG->dbname} > {$sqlFullPath}.sql";
        shell_exec($comando);

        $comando = "tar -zcvf {$sqlFullPath}.tar.gz {$CFG->dataroot}/filedir {$CFG->dataroot}/kopere* {$sqlFullPath}.sql";
        shell_exec($comando);

        unlink("{$sqlFullPath}.sql");

        Mensagem::agendaMensagemSuccess(get_string_kopere('backup_execute_success'));
        Header::location('Backup::dashboard');
    }

    public function execute_DumpSql() {
        global $DB, $CFG;

        DashboardUtil::startPage(array(
            array('Backup::dashboard', 'Backup'),
            get_string_kopere('backup_execute_exec')
        ));
        echo '<div class="element-box">';
        echo "<h3>Executando Backup: {$CFG->dbname}</h3>";

        $backupfile = $this->getBackupPath().'backup_'.date('Y-m-d-H-i').'.sql';

        $dumpStart = "-- Kopere Dashboard SQL Dump\n-- Host: {$CFG->dbhost}\n-- ".get_string_kopere('backup_execute_date')." ".date('d/m/Y \à\s H-i')."\n\n";
        file_put_contents($backupfile, $dumpStart);

        $dbName = "--\n-- ".get_string_kopere('backup_execute_database')." `{$CFG->dbname}`\n--\n\n-- --------------------------------------------------------\n\n";
        file_put_contents($backupfile, $dbName, FILE_APPEND);

        $tables = $DB->get_records_sql('SHOW TABLES');

        foreach ($tables as $table => $val) {

            echo "<p id='tabela-dump-$table'>".get_string_kopere('backup_execute_table')." <strong>$table</strong></p>
                      <script>
                          jQuery( 'html,body' ).animate ( { scrollTop: $( '#tabela-dump-$table' ).offset().top }, 0 );
                      </script>";

            $dbStart = "--\n-- ".get_string_kopere('backup_execute_structure')." `$table`\n--\n\n";
            file_put_contents($backupfile, $dbStart, FILE_APPEND);

            $schema = $DB->get_record_sql("SHOW CREATE TABLE `{$table}`");
            if (isset($schema->{'create table'})) {
                $tableSql = $schema->{'create table'}.";\n\n";
                file_put_contents($backupfile, $tableSql, FILE_APPEND);

                $dbDumpStart = "--\n-- ".get_string_kopere('backup_execute_dump')." `$table`\n--\n\n";
                file_put_contents($backupfile, $dbDumpStart, FILE_APPEND);
            } else {
                $tableSql = "-- ".get_string_kopere('backup_execute_dump_error')."\n\n";
                file_put_contents($backupfile, $tableSql, FILE_APPEND);
            }
        }

        Mensagem::printSuccess(get_string_kopere('backup_execute_complete'));

        echo '<div id="end-page-to" style="text-align: center;">';
        Button::add(get_string_kopere('backup_returnlist'), 'Backup::dashboard');
        echo '<script>
                      jQuery("html,body").animate ( { scrollTop: $( "#end-page-to" ).offset().top }, "slow" );
                  </script>
              </div>';

        echo '</div>';

        DashboardUtil::endPage();
    }

    public function delete() {
        $backupfile = $this->getBackupPath().$_GET['file'];

        if (file_exists($backupfile)) {
            preg_match("/backup_(\d+)-(\d+)-(\d+)-(\d+)-(\d+).tar.gz/", $_GET['file'], $p);
            $data = $p[3].'/'.$p[2].'/'.$p[1].' às '.$p[4].':'.$p[5];

            if (isset($_GET['status'])) {
                @unlink($backupfile);

                Mensagem::agendaMensagemSuccess(get_string_kopere('backup_deletesucessfull'));
                Header::location('Backup::dashboard');
            } else {
                DashboardUtil::startPage(array(
                    array('Backup::dashboard', 'Backup'),
                    get_string_kopere('backup_deleting')
                ));

                echo "<div class=\"element-box\">
                          <h3>".get_string_kopere('backup_delete_confirm')."</h3>
                          <p>".get_string_kopere('backup_delete_title')."</p>
                          <p>".get_string_kopere('backup_delete_title', ['file' => $_GET['file'], 'data' => $data])."</p>
                          <div>";
                Button::delete(get_string('yes'), "?Backup::delete&file={$_GET['file']}&status=sim", '', false);
                Button::add(get_string('no'), 'Backup::dashboard', 'margin-left-10', false);
                echo "    </div>
                      </div>";

                DashboardUtil::endPage();
            }
        } else {
            Header::notfound(get_string_kopere('backup_notound'));
        }
    }

    public function download() {
        ob_clean();
        ob_end_flush();
        session_write_close();

        $backupfile = $this->getBackupPath().$_GET['file'];

        if (file_exists($backupfile)) {
            header('Content-Type: application/octet-stream');
            header('Content-Transfer-Encoding: Binary');
            header('Content-disposition: attachment; filename="'.$_GET['file'].'"');

            readfile($backupfile);
            die();
        } else {
            Header::notfound(get_string_kopere('backup_notound'));
        }
    }

    //public function scheduling() {
    //    DashboardUtil::startPage(array(
    //        array('Backup::dashboard', 'Backup'),
    //        'Agendamento de Backup'
    //    ));
    //    DashboardUtil::endPage();
    //}

    private function getBackupPath($create = true) {
        global $CFG;

        $filepath = $CFG->dataroot.'/backup/';
        if ($create) {
            @mkdir($filepath);
        }

        return $filepath;
    }
}