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
 * @created    31/01/17 05:09
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\Botao;
use local_kopere_dashboard\report\ReportBenchmark;
use local_kopere_dashboard\report\ReportBenchmark_test;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Mensagem;

class Benchmark {
    public function test() {
        DashboardUtil::startPage('Teste de desempenho', null, null, 'Performace');

        echo '<div class="element-box">';
        Mensagem::printInfo('Plug-in baseado em
                    <a class="alert-link" href="https://moodle.org/plugins/report_benchmark"
                       target="_blank">report_benchmark</a>');

        echo '<div style="text-align: center;">
                  <p>Este teste pode demorar até 3 minutos para executar. </p>
                  <p>Tente fazer mais de uma vês o teste para ter uma média.</p>
                  <p>E, não execute em horário de picos.</p>';
        Botao::add('Executar o teste', 'Benchmark::execute');
        echo '</div>';

        echo '</div>';

        DashboardUtil::endPage();
    }

    public function execute() {
        global $CFG;

        DashboardUtil::startPage(array(
            array('Benchmark::test', 'Teste de desempenho'),
            'Desempenho'
        ));

        require_once($CFG->libdir . '/filelib.php');

        echo '<div class="element-box">';
        echo '<h3>Teste da performance da hospedagem</h3>';

        $test = new ReportBenchmark();

        $score = $test->get_total()["score"];
        if ($score < 500) {
            Mensagem::printInfo('<strong>Pontuação total:</strong> ' . $score);
        } else if ($score < 1000) {
            Mensagem::printWarning('<strong>Pontuação total:</strong> ' . $score);
        } else {
            Mensagem::printDanger('<strong>Pontuação total:</strong> ' . $score);
        }

        echo '<table class="table" id="benchmarkresult">
                  <thead>
                      <tr>
                          <th class="text-center media-middle">#</th>
                          <th>Descrição</th>
                          <th class="media-middle">Tempo, em segundos</th>
                          <th class="media-middle">Valor máximo aceitável</th>
                          <th class="media-middle">Limite crítico</th>
                      </tr>
                  </thead>
                  <tbody>';

        foreach ($test->get_results() as $result) {
            echo "<tr class=\"{$result['class']}\" >
                      <td class=\"text-center media-middle\">{$result['id']}</td>
                      <td >{$result['name']}<div><small>{$result['info']}</small></div></td>
                      <td class=\"text-center media-middle\">" . $this->formatNumber($result['during']) . "</td>
                      <td class=\"text-center media-middle\">" . $this->formatNumber($result['limit']) . "</td>
                      <td class=\"text-center media-middle\">" . $this->formatNumber($result['over']) . "</td>
                  </tr>";
        }

        echo '</tbody></table>';

        echo '<h3>Teste das configurações do Moodle</h3>';
        $this->performance();

        echo '</div>';

        DashboardUtil::endPage();
    }

    public function performance() {
        global $CFG;

        echo "<table class=\"table\" id=\"benchmarkresult\">
                  <tr>
                      <th>Problema</th>
                      <th>Status</th>
                      <th>Descrição</th>
                      <th>Ação</th>
                  </tr>";

        $tests = array(
            ReportBenchmark_test::themedesignermode(),
            ReportBenchmark_test::cachejs(),
            ReportBenchmark_test::debug(),
            ReportBenchmark_test::backup_auto_active(),
            ReportBenchmark_test::enablestats()
        );

        foreach ($tests as $test) {
            echo "<tr class='{$test['class']}'>
                      <td>{$test['title']}</td>
                      <td>{$test['resposta']}</td>
                      <td>{$test['description']}</td>
                      <td><a target=\"_blank\" href=\"{$CFG->wwwroot}/admin/{$test['url']}\">" . get_string('edit', '') . "</a></td>
                  </tr>";
        }

        echo '</tbody></table>';
    }

    private function formatNumber($number) {
        return str_replace('.', ',', $number);
    }
}