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
 * @created    27/05/17 11:05
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\chartsjs;

defined('MOODLE_INTERNAL') || die();

/**
 * Class lines
 *
 * @package local_kopere_dashboard\chartsjs
 */
class lines extends chart_base {
    /**
     * @param array $lines
     * @param       $x_axis_label
     * @param       $y_axis_label
     */
    public static function create_multilines( array $lines, $x_axis_label, $y_axis_label) {
        self::start();

        $datum = $labels = array();
        foreach ($lines as $line) {
            $values = $line['values'];
            $labels = $values_itens = array();
            foreach ($values as $value) {
                $values_itens[] = '{ x: new Date("' . $value['x'] . ' GMT-0300"), y: ' . $value['y'] . ' }';
                $labels[] = 'new Date("' . $value['x'] . ' GMT-0300")';
            }

            $cor = self::get_color();
            $datum[]
                = "{
                        label           : '{$line['key']}',
                        backgroundColor : " . preg_replace("/rgb\((\d+, \d+, \d+)\)/", "rgba($1, 0.5)", $cor) . ",
                        borderColor     : " . $cor . ",
                        data            : [ " . implode(',', $values_itens) . " ],
                        fill            : false
                    }";
        }

        ?>
        <div id="canvas-holder" style="width:40%">
            <canvas id="chart-area"/>
        </div>
        <script>
            var data = {
                datasets: [ <?php echo implode(', ', $datum) ?> ],
                labels: [ <?php echo implode(', ', $labels) ?> ]
            };
            var ctx = document.getElementById("chart-area").getContext("2d");
            new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: function (tooltipItem, data) {
                                console.log([tooltipItem, data]);
                                var date = new Date(data.labels[tooltipItem[0].index]);
                                return dois_digitos(date.getDate()) + " de " + get_mes(date.getMonth()) +
                                    " de " + date.getFullYear();
                            }
                        }
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: '<?php echo $x_axis_label ?>'
                            },
                            ticks: {
                                minRotation: 0,
                                maxRotation: 0,
                                callback: function (tick) {
                                    console.log(tick);
                                    var date = new Date(tick);
                                    return dois_digitos(date.getDate()) + '/' + dois_digitos(date.getMonth() + 1);
                                }
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: '<?php echo $y_axis_label ?>'
                            }
                        }]
                    }
                }
            });
            function dois_digitos(num) {
                if (num < 10) {
                    return '0' + num;
                }
                return num;
            }
            function get_mes(num) {
                var months = 'Janeiro Fevereiro Março Abril Maio Junho Julho Agosto Setembro Outubro Novembro Dezembro'.split(' ');
                return months[num];
            }

        </script>
        <?php
    }
}