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
 * backup file
 *
 * introduced 13/05/17 13:28
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\button;
use local_kopere_dashboard\html\table;
use local_kopere_dashboard\util\bytes_util;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\end_util;
use local_kopere_dashboard\util\header;
use local_kopere_dashboard\util\message;
use local_kopere_dashboard\util\server_util;
use local_kopere_dashboard\util\title_util;
use local_kopere_dashboard\util\url_util;

/**
 * Class backup
 *
 * @package local_kopere_dashboard
 */
class backup {

    /**
     * Function dashboard
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function dashboard() {
        global $DB;

        dashboard_util::add_breadcrumb(get_string("backup_title", "local_kopere_dashboard"));
        dashboard_util::start_page();

        echo '<div class="element-box">';

        if (strtoupper(substr(PHP_OS, 0, 3)) === "WIN") {
            message::print_warning(get_string("backup_windows", "local_kopere_dashboard"));
        } else {
            if (server_util::function_enable("shell_exec")) {
                message::print_info(get_string("backup_hours", "local_kopere_dashboard"));
                echo '<div style="text-align: center;">
                          <p>' . get_string("backup_sleep", "local_kopere_dashboard") . '</p>';

                if ($DB->get_dbfamily() == "mysql") {
                    button::add(get_string("backup_newnow", "local_kopere_dashboard"),
                        url_util::makeurl("backup", "execute"));
                    button::add(get_string("backup_newsqlnow", "local_kopere_dashboard"),
                        url_util::makeurl("backup", "execute_dumpsql"));
                } else if ($DB->get_dbfamily() == "postgres") {
                    button::add(get_string("backup_newsqlnow", "local_kopere_dashboard"),
                        url_util::makeurl("backup", "execute_dumpsql_pgsql"));
                }

                echo "</div>";
            } else {
                message::print_danger(get_string("backup_noshell", "local_kopere_dashboard"));
            }

            $backups = glob(self::get_backup_path(false) . "backup_*");
            $backupslista = [];
            foreach ($backups as $backup) {
                preg_match("/backup_(\d+)-(\d+)-(\d+)-(\d+)-(\d+)(.*?)\./", $backup, $p);
                $file = substr($p[0], 0, -1);
                $backupslista[] = [
                    "file" => $file,
                    "data" => "{$p[3]}/{$p[2]}/{$p[1]} às {$p[4]}:{$p[5]}",
                    "size" => bytes_util::size_to_byte(filesize($backup)),
                    "acoes" =>
                        "<div class='text-center'>" .
                        button::icon("download", url_util::makeurl("backup", "download", ["file" => $file])) .
                        "&nbsp;&nbsp;&nbsp; " .
                        button::icon_popup_table("delete", url_util::makeurl("backup", "delete", ["file" => $file])) .
                        "</div>",
                ];
            }

            if (isset($backupslista[0])) {

                echo "</div>";
                title_util::print_h3("backup_list");
                echo '<div class="element-box">';

                $table = new table();
                $table->add_header(get_string("backup_list_file", "local_kopere_dashboard"), "file");
                $table->add_header(get_string("backup_list_created", "local_kopere_dashboard"), "data");
                $table->add_header(get_string("backup_list_size", "local_kopere_dashboard"), "size");
                $table->add_header(get_string("backup_list_action", "local_kopere_dashboard"), "acoes");

                $table->set_row($backupslista);
                $table->close(true, ["order" => [[1, "desc"]]]);
            } else {
                message::print_warning(get_string("backup_none", "local_kopere_dashboard"));
            }
        }
        echo "</div>";

        dashboard_util::end_page();
    }

    /**
     * Function execute
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function execute() {
        global $CFG, $DB;

        if (!server_util::function_enable("shell_exec")) {
            message::schedule_message_danger(get_string("backup_noshell", "local_kopere_dashboard"));
            header::location(url_util::makeurl("backup", "dashboard"));
        }

        $backupdir = $this->get_backup_path() . "backup_" . date('Y-m-d-H-i');

        if ($DB->get_dbfamily() == "mysql") {
            $comando = "/usr/bin/mysqldump -h {$CFG->dbhost} -u {$CFG->dbuser} -p{$CFG->dbpass} {$CFG->dbname} > {$backupdir}.sql";
            shell_exec($comando);
        } else {
            self::execute_dumpsql_pgsql($backupdir, true);
        }

        $comando = "tar -zcvf {$backupdir}.tar.gz {$CFG->dataroot}/filedir {$CFG->dataroot}/kopere* {$backupdir}.sql";
        shell_exec($comando);

        @unlink("{$backupdir}.sql");

        message::schedule_message_success(get_string("backup_execute_success", "local_kopere_dashboard"));
        header::location(url_util::makeurl("backup", "dashboard"));
    }

    /**
     * Function execute_dumpsql
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function execute_dumpsql() {
        global $DB, $CFG;

        set_time_limit(0);

        dashboard_util::add_breadcrumb(get_string("backup_title", "local_kopere_dashboard"),
            url_util::makeurl("backup", "dashboard"));
        dashboard_util::add_breadcrumb(get_string("backup_execute_exec", "local_kopere_dashboard"));
        dashboard_util::add_breadcrumb(get_string("backup_execute_exec", "local_kopere_dashboard") . ": {$CFG->dbname}");
        dashboard_util::start_page();

        echo '<div class="element-box">';

        $backupdir = $this->get_backup_path() . "backup_" . date("Y-m-d-H-i");
        $unique = uniqid();
        $backupfile = "{$backupdir}-{$unique}.sql";

        $dumpstart = "-- " . get_string("pluginname", "local_kopere_dashboard") . " SQL Dump\n-- Host: {$CFG->dbhost}\n-- " .
            get_string("backup_execute_date", "local_kopere_dashboard") . " " . date("d/m/Y \à\s H-i") . "\n\n";
        file_put_contents($backupfile, $dumpstart);

        $dbname = "--\n-- " . get_string("backup_execute_database", "local_kopere_dashboard") .
            " `{$CFG->dbname}`\n--";
        file_put_contents($backupfile, $dbname, FILE_APPEND);

        $tables = $DB->get_records_sql("SHOW TABLES");

        foreach ($tables as $table => $val) {

            echo "<p id='tabela-dump-{$table}'>" .
                get_string("backup_execute_table", "local_kopere_dashboard") . " <strong>{$table}</strong></p>";

            $dbstart = "\n\n\n--\n-- " . get_string("backup_execute_structure", "local_kopere_dashboard") . " `{$table}`\n--\n\n";
            file_put_contents($backupfile, $dbstart, FILE_APPEND);

            $schema = $DB->get_record_sql("SHOW CREATE TABLE `{$table}`");
            if (isset($schema->{"create table"})) {
                $tablesql = $schema->{"create table"} . ";\n\n";
                file_put_contents($backupfile, $tablesql, FILE_APPEND);

                $dbdumpstart = "--\n-- " . get_string("backup_execute_dump", "local_kopere_dashboard") . " `{$table}`\n--\n";
                file_put_contents($backupfile, $dbdumpstart, FILE_APPEND);

                $tablenoprefix = str_replace($CFG->prefix, "", $table);
                $columns = $DB->get_columns($tablenoprefix);

                $colunas = [];
                foreach ($columns as $colum => $value) {
                    $colunas[] = $colum;
                }

                $listcol = implode("`, `", $colunas); // phpcs:disable

                $insertheader = "\nINSERT INTO `{$table}` (`{$listcol}`) VALUES\n";

                $registros = 0;
                while ($records = $DB->get_records($tablenoprefix, null, "id ASC", "*", $registros, 50)) {

                    $sql = [];
                    foreach ($records as $record) {
                        $parametros = [];
                        foreach ($colunas as $coluna) {
                            $de = ["\\", "\0", "\n", "\r", "'", '"', "\x1a"];
                            $para = ["\\\\", "\\0", "\\n", "\\r", "\\'", '\\"', '\\Z'];
                            $param = str_replace($de, $para, $record->$coluna);
                            $parametros[] = "'{$param}'";
                        }
                        $sql[] = "(" . implode(", ", $parametros) . ")";
                    }

                    file_put_contents($backupfile, $insertheader . implode(",\n", $sql) . ";", FILE_APPEND);

                    $registros += 50;
                }

            } else {
                $tablesql = "-- " . get_string("backup_execute_dump_error", "local_kopere_dashboard") . "\n\n";
                file_put_contents($backupfile, $tablesql, FILE_APPEND);
            }
        }

        message::print_success(get_string("backup_execute_complete", "local_kopere_dashboard"));

        echo '<div id="end-page-to" style="text-align: center;">';
        button::add(get_string("backup_returnlist", "local_kopere_dashboard"), url_util::makeurl("backup", "dashboard"));
        echo "</div>";
        echo "</div>";

        dashboard_util::end_page();
    }

    /**
     * Function execute_dumpsql_pgsql
     *
     * @param string $backupdir
     * @param bool $execute
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function execute_dumpsql_pgsql($backupdir = null, $execute = false) {
        global $DB, $CFG, $PAGE;

        $decsep = get_string("decsep", "langconfig");
        $thousandssep = get_string("thousandssep", "langconfig");

        if (!$execute) {
            set_time_limit(0);
            session_write_close();
            ob_end_flush();

            dashboard_util::add_breadcrumb(get_string("backup_title", "local_kopere_dashboard"),
                url_util::makeurl("backup", "dashboard"));
            dashboard_util::add_breadcrumb(get_string("backup_execute_exec", "local_kopere_dashboard"));
            dashboard_util::add_breadcrumb(get_string("backup_execute_exec", "local_kopere_dashboard") . ": {$CFG->dbname}");
            dashboard_util::start_page();

            echo '<div class="element-box">';
        }

        if (!$backupdir) {
            $backupdir = $this->get_backup_path() . "backup_" . date("Y-m-d-H-i");
        }
        $unique = uniqid();
        $backupfile = "{$backupdir}-{$unique}.sql";

        $dumpstart = "-- " . get_string("pluginname", "local_kopere_dashboard") . " SQL Dump\n-- Host: {$CFG->dbhost}\n-- " .
            get_string("backup_execute_date", "local_kopere_dashboard") . " " . date("d/m/Y \à\s H-i") . "\n\n";
        file_put_contents($backupfile, $dumpstart);

        $dbname = "--\n-- " . get_string("backup_execute_database", "local_kopere_dashboard") .
            " `{$CFG->dbname}`\n--";
        file_put_contents($backupfile, $dbname, FILE_APPEND);

        $tables = $DB->get_records_sql("
                SELECT table_name
                  FROM information_schema.tables
                 WHERE table_schema  = 'public'
                   AND table_catalog = '{$CFG->dbname}'
              ORDER BY table_name");

        $exporttables = 0;
        if (!$execute) {
            $mark = get_string("backup_mark_all", "local_kopere_dashboard");
            echo "<form method='post'>
                      <table class='table table-hover'>
                          <tr>
                              <th>Tabela<br>
                                <span class='btn btn-success mark-all-table'>{$mark}</span></th>
                              <th>Dados<br>
                                <span class='btn btn-success mark-all-data'>{$mark}</span></th>
                              <th>Nome da coluna</th>
                              <th>Linhas</th>
                          </tr>";
        }

        foreach ($tables as $table => $val) {
            $inputname = "export-{$table}";

            $rows = $DB->get_record_sql("SELECT reltuples::bigint AS count FROM pg_class WHERE relname = '{$table}'");
            $numrows = number_format($rows->count, 0, $decsep, $thousandssep);

            $checked = "";
            if ($rows->count < 1000000) {
                $checked = "checked";
            }
            if (!$execute) {
                echo "<tr>
                          <td><input type='checkbox' name='{$inputname}-table' value='1'
                                     class='table-checkbox-table' checked></td>
                          <td><input type='checkbox' name='{$inputname}-data'  value='1'
                                     class='table-checkbox-data' {$checked}></td>
                          <td>{$table} - <span class='btn btn-success mark-line-table'>{$mark}</span></td>
                          <td>{$numrows}</td>
                      </tr>";
            }

            if ($execute ||
                optional_param("{$inputname}-table", false, PARAM_INT) ||
                optional_param("{$inputname}-data", false, PARAM_INT)) {
                $dbstart = "\n\n\n--\n-- " . get_string("backup_execute_structure", "local_kopere_dashboard") . " `{$table}`\n--\n\n";
                file_put_contents($backupfile, $dbstart, FILE_APPEND);
            }

            if ($execute || optional_param("{$inputname}-table", false, PARAM_INT)) {
                $exporttables++;
                $colunas = $DB->get_records_sql("
                    SELECT column_name, data_type, character_maximum_length, column_default, is_nullable
                      FROM information_schema.columns
                     WHERE table_name = '{$table}'");
                $tablesql = self::execute_dumpsql_pgsql_createtable($table, $colunas);
                file_put_contents($backupfile, $tablesql, FILE_APPEND);

                $dbdumpstart = "--\n-- " . get_string("backup_execute_dump", "local_kopere_dashboard") . " `{$table}`\n--\n";
                file_put_contents($backupfile, $dbdumpstart, FILE_APPEND);

                $tablenoprefix = str_replace($CFG->prefix, "", $table);
                $columns = $DB->get_columns($tablenoprefix);

                $colunas = [];
                foreach ($columns as $colum => $value) {
                    $colunas[] = $colum;
                }
                $listcol = implode("`, `", $colunas); // phpcs:disable
            }
            if (optional_param("{$inputname}-data", false, PARAM_INT)) {
                $insertheader = "\nINSERT INTO `{$table}` (`{$listcol}`) VALUES\n";
                $registros = 0;
                while ($records = $DB->get_records($tablenoprefix, null, "id ASC", "*", $registros, 50)) {
                    $sql = [];
                    foreach ($records as $record) {
                        $parametros = [];
                        foreach ($colunas as $coluna) {
                            if (is_null($record->$coluna)) {
                                $parametros[] = "NULL";
                            } else {
                                $parametros[] = "'" . addslashes($record->$coluna) . "'";
                            }
                        }
                        $sql[] = "(" . implode(", ", $parametros) . ")";
                    }

                    file_put_contents($backupfile, $insertheader . implode(",\n", $sql) . ";", FILE_APPEND);

                    $registros += 50;
                }
            }
        }

        if (!$execute) {
            echo "
                        <tr>
                            <th>Tabela<br>
                              <span class='btn btn-success mark-all-table'>{$mark}</span></th>
                            <th>Dados<br>
                              <span class='btn btn-success mark-all-data'>{$mark}</span></th>
                            <th>Nome da coluna</th>
                            <th>Linhas</th>
                        </tr>
                    </table>
                    <input type='submit' value='Executar' class='btn btn-success'>
                </form>";
        }

        if ($exporttables) {
            if (!$execute) {
                message::print_success(get_string("backup_execute_complete", "local_kopere_dashboard"));
            }
        } else {
            unlink($backupfile);
        }

        if (!$execute) {
            echo '<div id="end-page-to" style="text-align: center;">';
            button::add(get_string("backup_returnlist", "local_kopere_dashboard"), url_util::makeurl("backup", "dashboard"));
            echo "</div>";
            echo "</div>";

            $PAGE->requires->js_call_amd("local_kopere_dashboard/backup", "mark", ["#tabela-dump-{$table}"]);


            dashboard_util::end_page();
        }
    }

    private function execute_dumpsql_pgsql_createtable($table, $colunas) {
        // Gerando as colunas para o CREATE TABLE.
        $columns = [];
        foreach ($colunas as $coluna) {
            if ($coluna->column_name == "id") {
                $columndefinition = "id int NOT NULL AUTO_INCREMENT";
            } else if ($coluna->data_type == "character varying") {
                $columndefinition = "{$coluna->column_name} VARCHAR({$coluna->character_maximum_length})";
                if ($coluna->is_nullable == 'NO') {
                    $columndefinition .= " NOT NULL";
                }
            } else {
                $columndefinition = "{$coluna->column_name} {$coluna->data_type}";

                // Adicionando o comprimento para tipos de dados que têm comprimento máximo.
                if ($coluna->character_maximum_length) {
                    $columndefinition .= "({$coluna->character_maximum_length})";
                }

                // Adicionando o valor padrão, se houver.
                if ($coluna->column_default) {
                    $defaults = explode("::", $coluna->column_default);
                    $columndefinition .= " DEFAULT {$defaults[0]}";
                }

                // Adicionando a definição de NULL ou NOT NULL.
                if ($coluna->is_nullable == 'NO') {
                    $columndefinition .= " NOT NULL";
                }
            }

            $columns[] = "    " . $columndefinition;
        }

        // Início do SQL para criar a tabela.
        $createtablesql = "CREATE TABLE {$table} (\n";
        // Juntando todas as colunas na string SQL.
        $createtablesql .= implode(",\n", $columns);
        // Finalizando o SQL.
        $createtablesql .= ",\n    PRIMARY KEY (id)\n);\n";

        //echo '<pre>';
        //print_r($colunas);
        //print_r($createtablesql);
        //echo '</pre>';

        return $createtablesql;
    }

    /**
     * Function delete
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function delete() {
        $file = optional_param("file", "", PARAM_TEXT);
        $status = optional_param("status", false, PARAM_TEXT);

        $backupfile = $this->get_backup_path() . $file . ".sql";

        if (file_exists($backupfile)) {
            if ($status) {
                @unlink($backupfile);

                message::schedule_message_success(get_string("backup_deletesucessfull", "local_kopere_dashboard"));
                header::location(url_util::makeurl("backup", "dashboard"));
            } else {

                dashboard_util::add_breadcrumb(get_string("backup_title", "local_kopere_dashboard"),
                    url_util::makeurl("backup", "dashboard"));
                dashboard_util::add_breadcrumb(get_string("backup_deleting", "local_kopere_dashboard"));
                dashboard_util::start_page();

                echo "<div class='element-box'>
                          <h3>" . get_string("backup_delete_confirm", "local_kopere_dashboard") . "</h3>
                          <p>" . get_string("backup_delete_title", "local_kopere_dashboard", $file) . "</p>
                          <div>";
                button::delete(get_string("yes"),
                    url_util::makeurl("backup", "delete", ["file" => $file, "status" => "sim"]), "", false);
                button::add(get_string("no"),
                    url_util::makeurl("backup", "dashboard"), "margin-left-10", false);
                echo "    </div>
                      </div>";

                dashboard_util::end_page();
            }
        } else {
            header::notfound(get_string("backup_notound", "local_kopere_dashboard"));
        }
    }

    /**
     * Function download
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function download() {
        ob_clean();
        session_write_close();
        ob_end_flush();

        $file = optional_param("file", "", PARAM_TEXT);

        $backupfile = $this->get_backup_path() . $file;

        if (file_exists($backupfile)) {
            header("Content-Type: application/octet-stream");
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"{$file}\"");

            readfile($backupfile);
            end_util::end_script_show();
        } else {
            header::notfound(get_string("backup_notound", "local_kopere_dashboard"));
        }
    }

    /**
     * Function get_backup_path
     *
     * @param bool $create
     *
     * @return string
     */
    private function get_backup_path($create = true) {
        global $CFG;

        $filepath = "{$CFG->dataroot}/backup/";
        if ($create) {
            @mkdir($filepath);
        }

        return $filepath;
    }
}
