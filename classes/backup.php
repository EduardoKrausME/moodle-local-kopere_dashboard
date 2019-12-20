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

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\button;
use local_kopere_dashboard\html\table;
use local_kopere_dashboard\util\bytes_util;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\end_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\mensagem;
use local_kopere_dashboard\util\server_util;
use local_kopere_dashboard\util\title_util;

/**
 * Class backup
 *
 * @package local_kopere_dashboard
 */
class backup {
    /**
     *
     * @throws \coding_exception
     */
    public function dashboard() {
        dashboard_util::add_breadcrumb(get_string_kopere('backup_title'));
        dashboard_util::start_page();

        echo '<div class="element-box">';

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            mensagem::print_warning(get_string_kopere('backup_windows'));
        } else {
            if (server_util::function_enable('shell_exec')) {
                mensagem::print_info(get_string_kopere('backup_hours'));
                echo '<div style="text-align: center;">
                          <p>' . get_string_kopere('backup_sleep') . '</p>';

                button::add(get_string_kopere('backup_newsqlnow'), '?classname=backup&method=execute_dumpsql');

                echo '</div>';
            } else {
                mensagem::print_danger(get_string_kopere('backup_noshell'));
            }

            $backups = glob(self::get_backup_path(false) . 'backup_*');
            $backupslista = array();
            foreach ($backups as $backup) {
                preg_match("/backup_(\d+)-(\d+)-(\d+)-(\d+)-(\d+)\..*/", $backup, $p);
                $backupslista[] = array(
                    'file' => $p[0],
                    'data' => $p[3] . '/' . $p[2] . '/' . $p[1] . ' às ' . $p[4] . ':' . $p[5],
                    'size' => bytes_util::size_to_byte(filesize($backup)),
                    'acoes' => "<div class=\"text-center\">
                                    " . button::icon('download', "?classname=backup&method=download&file={$p[0]}", false) . "
                                &nbsp;&nbsp;&nbsp; " . button::icon_popup_table('delete', "?classname=backup&method=delete&file={$p[0]}") . "
                                </div>"
                );
            }

            if (isset($backupslista[0])) {

                echo '</div>';
                title_util::print_h3('backup_list');
                echo '<div class="element-box">';

                $table = new table();
                $table->add_header(get_string_kopere('backup_list_file'), 'file');
                $table->add_header(get_string_kopere('backup_list_created'), 'data');
                $table->add_header(get_string_kopere('backup_list_size'), 'size');
                $table->add_header(get_string_kopere('backup_list_action'), 'acoes');

                $table->set_row($backupslista);
                $table->close(true, 'order:[[1,"DESC"]]');
            } else {
                mensagem::print_warning(get_string_kopere('backup_none'));
            }
        }
        echo '</div>';

        dashboard_util::end_page();
    }

    /**
     * @throws \dml_exception
     */
    public function execute_dumpsql() {
        global $DB, $CFG;

        set_time_limit(0);

        dashboard_util::add_breadcrumb(get_string_kopere('backup_title'), '?classname=backup&method=dashboard');
        dashboard_util::add_breadcrumb(get_string_kopere('backup_execute_exec'));
        dashboard_util::add_breadcrumb(get_string_kopere('backup_execute_exec') . ": {$CFG->dbname}");
        dashboard_util::start_page();

        echo '<div class="element-box">';

        $backupfullpath = $this->get_backup_path() . 'backup_' . date('Y-m-d-H-i');
        $backupfile = "{$backupfullpath}.sql";

        $dumpstart = "--" . get_string_kopere('pluginname') . " SQL Dump\n-- Host: {$CFG->dbhost}\n-- " .
            get_string_kopere('backup_execute_date') . " " . date('d/m/Y \à\s H-i') . "\n\n";
        file_put_contents($backupfile, $dumpstart);

        $dbname = "--\n-- " . get_string_kopere('backup_execute_database') .
            " `{$CFG->dbname}`\n--";
        file_put_contents($backupfile, $dbname, FILE_APPEND);

        $tables = $DB->get_records_sql('SHOW TABLES');

        foreach ($tables as $table => $val) {

            echo "<p id='tabela-dump-$table'>" . get_string_kopere('backup_execute_table') . " <strong>$table</strong></p>
                      <script>
                          jQuery( 'html,body' ).animate ( { scrollTop: $( '#tabela-dump-$table' ).offset().top }, 0 );
                      </script>";

            $dbstart = "\n\n\n--\n-- " . get_string_kopere('backup_execute_structure') . " `$table`\n--\n\n";
            file_put_contents($backupfile, $dbstart, FILE_APPEND);

            $schema = $DB->get_record_sql("SHOW CREATE TABLE `{$table}`");
            if (isset($schema->{'create table'})) {
                $tablesql = $schema->{'create table'} . ";\n\n";
                file_put_contents($backupfile, $tablesql, FILE_APPEND);

                $dbdumpstart = "--\n-- " . get_string_kopere('backup_execute_dump') . " `$table`\n--\n";
                file_put_contents($backupfile, $dbdumpstart, FILE_APPEND);

                $tablenoprefix = str_replace($CFG->prefix, "", $table);
                $columns = $DB->get_columns($tablenoprefix);

                $colunas = [];
                foreach ($columns as $colum => $value) {
                    $colunas[] = $colum;
                }

                $listCol = implode('`, `', $colunas);

                $insertHeader = "\nINSERT INTO `{$table}` (`{$listCol}`) VALUES\n";

                $registros = 0;
                while ($records = $DB->get_records($tablenoprefix, null, '', '*', $registros, $registros + 50)) {

                    $sql = [];
                    foreach ($records as $record) {
                        $parametros = [];
                        foreach ($colunas as $coluna) {
                            $de = array('\\', "\0", "\n", "\r", "'", '"', "\x1a");
                            $para = array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z');
                            $param = str_replace($de, $para, $record->$coluna);
                            $parametros[] = "'{$param}'";
                        }
                        $sql[] = "(" . implode(", ", $parametros) . ")";
                    }

                    file_put_contents($backupfile, $insertHeader . implode(",\n", $sql) . ";", FILE_APPEND);

                    $registros += 50;
                }

            } else {
                $tablesql = "-- " . get_string_kopere('backup_execute_dump_error') . "\n\n";
                file_put_contents($backupfile, $tablesql, FILE_APPEND);
            }
        }

        mensagem::print_success(get_string_kopere('backup_execute_complete'));

        echo '<div id="end-page-to" style="text-align: center;">';
        button::add(get_string_kopere('backup_returnlist'), '?classname=backup&method=dashboard');
        echo '<script>
                      jQuery("html,body").animate ( { scrollTop: $( "#end-page-to" ).offset().top }, "slow" );
                  </script>
              </div>';

        echo '</div>';

        dashboard_util::end_page();
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function delete() {
        $file = optional_param('file', '', PARAM_TEXT);
        $status = optional_param('status', false, PARAM_TEXT);

        $backupfile = $this->get_backup_path() . $file;

        if (file_exists($backupfile)) {
            if ($status) {
                @unlink($backupfile);

                mensagem::agenda_mensagem_success(get_string_kopere('backup_deletesucessfull'));
                header::location('?classname=backup&method=dashboard');
            } else {

                dashboard_util::add_breadcrumb(get_string_kopere('backup_title'), '?classname=backup&method=dashboard');
                dashboard_util::add_breadcrumb(get_string_kopere('backup_deleting'));
                dashboard_util::start_page();

                echo "<div class=\"element-box\">
                          <h3>" . get_string_kopere('backup_delete_confirm') . "</h3>
                          <p>" . get_string_kopere('backup_delete_title', $file) . "</p>
                          <div>";
                button::delete(get_string('yes'), "?classname=backup&method=delete&file={$file}&status=sim", '', false);
                button::add(get_string('no'), '?classname=backup&method=dashboard', 'margin-left-10', false);
                echo "    </div>
                      </div>";

                dashboard_util::end_page();
            }
        } else {
            header::notfound(get_string_kopere('backup_notound'));
        }
    }

    /**
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function download() {
        ob_clean();
        ob_end_flush();
        session_write_close();

        $file = optional_param('file', '', PARAM_TEXT);

        $backupfile = $this->get_backup_path() . $file;

        if (file_exists($backupfile)) {
            header('Content-Type: application/octet-stream');
            header('Content-Transfer-Encoding: Binary');
            header('Content-disposition: attachment; filename="' . $file . '"');

            readfile($backupfile);
            end_util::end_script_show();
        } else {
            header::notfound(get_string_kopere('backup_notound'));
        }
    }

    /**
     * @param bool $create
     *
     * @return string
     */
    private function get_backup_path($create = true) {
        global $CFG;

        $filepath = $CFG->dataroot . '/backup/';
        if ($create) {
            @mkdir($filepath);
        }

        return $filepath;
    }
}