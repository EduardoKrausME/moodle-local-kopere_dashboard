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

$string['modulename'] = 'Kopere Dashboard';
$string['pluginname'] = 'Kopere Dashboard';
$string['kopere_dashboard:view'] = 'Ver Kopere Dashboard';
$string['kopere_dashboard:manage'] = 'Gerenciar Kopere Dashboard';
$string['settings'] = 'Configurar';

$string['messageprovider:kopere_dashboard_messages'] = 'Envia Notificações';
$string['kopere_dashboard:emailconfirmsubmission'] = 'Envia Notificações';


// ReportBenchmark

$string['cloadname'] = 'Tempo de carregamento do Moodle';
$string['cloadmoreinfo'] = 'Tempo de carregando o arquivo de configuração "config.php"';

$string['processorname'] = 'Uma função chamada muitas vezes';
$string['processormoreinfo'] = 'Uma função é chamada em um loop para testar a velocidade do processador';

$string['filereadname'] = 'Leitura de arquivos';
$string['filereadmoreinfo'] = 'Testar a velocidade de leitura na pasta temporária da MoodleData';

$string['filewritename'] = 'Criação de arquivos';
$string['filewritemoreinfo'] = 'Testar a velocidade de gravação na pasta temporária da MoodleData';

$string['coursereadname'] = 'Leitura de um curso';
$string['coursereadmoreinfo'] = 'Testar a velocidade de leitura ao ler um curso';

$string['coursewritename'] = 'Criando um curso';
$string['coursewritemoreinfo'] = 'Testar a velocidade do banco de dados para escrever um curso';

$string['querytype1name'] = 'Solicitação complexa (n°1)';
$string['querytype1moreinfo'] = 'Testar a velocidade do banco de dados para executar um pedido complexo';

$string['querytype2name'] = 'Solicitação complexa (n°2)';
$string['querytype2moreinfo'] = 'Testar a velocidade do banco de dados para executar outro pedido mais complexo';

$string['loginguestname'] = 'Tempo para se conectar como convidado';
$string['loginguestmoreinfo'] = 'Medindo o tempo para logar no Moodle com a conta de convidado';

$string['loginusername'] = 'Tempo para se conectar com uma conta de usuário falsa';
$string['loginusermoreinfo'] = 'Medindo o tempo para logar no Moodle com uma conta de usuário falsa';