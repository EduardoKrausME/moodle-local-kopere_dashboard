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

use local_kopere_dashboard\html\Botao;
use local_kopere_dashboard\html\Table;
use local_kopere_dashboard\util\BytesUtil;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Header;
use local_kopere_dashboard\util\Mensagem;
use local_kopere_dashboard\util\ServerUtil;

class Backup {
    public function dashboard() {
        DashboardUtil::startPage('Backup');

        echo '<div class="element-box">';

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            {
                Mensagem::printWarning('Não disponível em Servidor Windows!');
            }
        } else {
            if (ServerUtil::isFunctionEnabled('shell_exec')) {
                Mensagem::printInfo('Não execute backup em horários de picos!');
                echo '<div style="text-align: center;">
                          <p>Backup podem demorar vários minutos para executar.</p>';
                Botao::add('Criar novo Backup agora', 'Backup::execute');
                echo '</div>';
                echo '</div>';
            } else {
                Mensagem::printDanger('Função shell_exec esta desativada!');
            }

            $backups = glob(self::getBackupPath(false) . 'backup_*');
            $backupsLista = array();
            foreach ($backups as $backup) {
                preg_match("/backup_(\d+)-(\d+)-(\d+)-(\d+)-(\d+).tar.gz/", $backup, $p);
                $backupsLista[] = array(
                    'file' => $p[0],
                    'data' => $p[3] . '/' . $p[2] . '/' . $p[1] . ' às ' . $p[4] . ':' . $p[5],
                    'size' => BytesUtil::sizeToByte(filesize($backup)),
                    'acoes' => "<div class=\"text-center\">
                                    " . Botao::icon('download', "Backup::download&file={$p[0]}") . "&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp; " . Botao::icon('delete', "Backup::delete&file={$p[0]}") . "
                                </div>"
                );
            }

            // echo '<div class="element-box">
            // <h3>Agendamento de backups</h3>';
            // Botao::add ( 'Agendar Backup', 'Backup::scheduling' );
            // echo '</div>';

            echo '<div class="element-box">';
            echo '<h3>Lista de backups</h3>';

            if (isset($backupsLista[0])) {
                $table = new Table();
                $table->addHeader('Arquivo', 'file');
                $table->addHeader('Criado em', 'data');
                $table->addHeader('Tamanho', 'size');
                $table->addHeader('Ações', 'acoes');

                $table->setRow($backupsLista);
                $table->close(true);
            } else {
                Mensagem::printWarning('Nenhum backup localizado!');
            }
        }

        echo '</div>';

        DashboardUtil::endPage();
    }

    public function execute() {
        global $CFG;

        if (!ServerUtil::isFunctionEnabled('shell_exec')) {
            Mensagem::agendaMensagemDanger('Função shell_exec esta desativada!');
            Header::location('Backup::dashboard');
        }

        $sqlFullPath = $this->getBackupPath() . 'backup_' . date('Y-m-d-H-i');

        $comando = "/usr/bin/mysqldump -h {$CFG->dbhost} -u {$CFG->dbuser} -p{$CFG->dbpass} {$CFG->dbname} > {$sqlFullPath}.sql";
        shell_exec($comando);

        $comando = "tar -zcvf {$sqlFullPath}.tar.gz {$CFG->dataroot}/filedir {$CFG->dataroot}/kopere* {$sqlFullPath}.sql";
        shell_exec($comando);

        unlink("{$sqlFullPath}.sql");

        Mensagem::agendaMensagemSuccess('Backup criado com sucesso!');
        Header::location('Backup::dashboard');
    }

    public function execute_DumpSql() {
        global $DB, $CFG;

        DashboardUtil::startPage(array(
            array('Backup::dashboard', 'Backup'),
            'Execução do Backup'
        ));
        echo '<div class="element-box">';
        echo "<h3>Executando Backup: {$CFG->dbname}</h3>";

        $backupfile = $this->getBackupPath() . 'backup_' . date('Y-m-d-H-i') . '.sql';

        $dumpStart = "-- Kopere Dashboard SQL Dump\n-- Host: {$CFG->dbhost}\n-- Data da geração: " . date('d/m/Y \à\s H-i') . "\n\n";
        file_put_contents($backupfile, $dumpStart);

        $dbName = "--\n-- Banco de dados: `{$CFG->dbname}`\n--\n\n-- --------------------------------------------------------\n\n";
        file_put_contents($backupfile, $dbName, FILE_APPEND);

        $tables = $DB->get_records_sql('SHOW TABLES');

        foreach ($tables as $table => $val) {

            echo "<p id='tabela-dump-$table'>Executando Backup da tabela <strong>$table</strong></p>
                      <script>
                          jQuery( 'html,body' ).animate ( { scrollTop: $( '#tabela-dump-$table' ).offset().top }, 0 );
                      </script>";

            $dbStart = "--\n-- Estrutura para tabela `$table`\n--\n\n";
            file_put_contents($backupfile, $dbStart, FILE_APPEND);

            $schema = $DB->get_record_sql("SHOW CREATE TABLE `{$table}`");
            if (isset($schema->{'create table'})) {
                $tableSql = $schema->{'create table'} . ";\n\n";
                file_put_contents($backupfile, $tableSql, FILE_APPEND);

                $dbDumpStart = "--\n-- Fazendo dump de dados para tabela `$table`\n--\n\n";
                file_put_contents($backupfile, $dbDumpStart, FILE_APPEND);
            } else {
                $tableSql = "-- Erro ao capturar a tabela\n\n";
                file_put_contents($backupfile, $tableSql, FILE_APPEND);
            }
        }

        Mensagem::printSuccess('Backup concluído!');

        echo '<div id="end-page-to" style="text-align: center;">';
        Botao::add('Voltar para a lista de Backups', 'Backup::dashboard');
        echo '<script>
                      jQuery("html,body").animate ( { scrollTop: $( "#end-page-to" ).offset().top }, "slow" );
                  </script>
              </div>';

        echo '</div>';

        DashboardUtil::endPage();
    }

    public function delete() {
        $backupfile = $this->getBackupPath() . $_GET['file'];

        if (file_exists($backupfile)) {
            preg_match("/backup_(\d+)-(\d+)-(\d+)-(\d+)-(\d+).tar.gz/", $_GET['file'], $p);
            $data = $p[3] . '/' . $p[2] . '/' . $p[1] . ' às ' . $p[4] . ':' . $p[5];

            if (isset($_GET['status'])) {
                @unlink($backupfile);

                Mensagem::agendaMensagemSuccess('Backup excluído com sucesso!');
                Header::location('Backup::dashboard');
            } else {
                DashboardUtil::startPage(array(
                    array('Backup::dashboard', 'Backup'),
                    'Excluíndo Backup'
                ));

                echo "<div class=\"element-box\">
                          <h3>Exclusão do Backup</h3>
                          <p>Deseja realmente excluir o backup <strong>{$_GET['file']}</strong> criado em <strong>$data</strong>?</p>
                          <div>";
                Botao::delete('Sim', "?Backup::delete&file={$_GET['file']}&status=sim", '', false);
                Botao::add('Não', 'Backup::dashboard', 'margin-left-10', false);
                echo "    </div>
                      </div>";

                DashboardUtil::endPage();
            }
        } else {
            Header::notfound('Arquivo não localizado!');
        }
    }

    public function download() {
        ob_clean();
        ob_end_flush();
        session_write_close();

        $backupfile = $this->getBackupPath() . $_GET['file'];

        if (file_exists($backupfile)) {
            header('Content-Type: application/octet-stream');
            header('Content-Transfer-Encoding: Binary');
            header('Content-disposition: attachment; filename="' . $_GET['file'] . '"');

            readfile($backupfile);
            die();
        } else {
            Header::notfound('Arquivo não localizado!');
        }
    }

    public function scheduling() {
        DashboardUtil::startPage(array(
            array('Backup::dashboard', 'Backup'),
            'Agendamento de Backup'
        ));

        DashboardUtil::endPage();
    }

    private function getBackupPath($create = true) {
        global $CFG;

        $filepath = $CFG->dataroot . '/backup/';
        if ($create) {
            @mkdir($filepath);
        }

        return $filepath;
    }
}