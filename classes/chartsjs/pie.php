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
 * @created    25/05/17 16:00
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\chartsjs;

defined('MOODLE_INTERNAL') || die();

/**
 * Class pie
 *
 * @package local_kopere_dashboard\chartsjs
 */
class pie extends chart_base {
    /**
     * @var
     */
    public $label;
    /**
     * @var
     */
    public $value;

    /**
     * pie constructor.
     *
     * @param $label
     * @param $value
     */
    public function __construct( $label, $value) {
        $this->label = $label;
        $this->value = $value;
    }

    /**
     * @param array $pie_values
     */
    public static function create_regular( array $pie_values) {
        self::start();

        $itens = array();
        $labels = array();
        $colors = array();

        /** @var pie $pie */
        foreach ($pie_values as $pie) {
            $itens [] = $pie->value;
            $labels[] = '"' . $pie->label . '"';
            $colors[] = self::get_color();
        }
        ?>
        <div id="canvas-holder">
            <canvas id="chart-area" style="max-width:500px;"/>
        </div>
        <script>
            var data = {
                datasets: [{
                    data: [ <?php echo implode(', ', $itens) ?> ],
                    backgroundColor: [ <?php echo implode(', ', $colors) ?> ]
                }],
                labels: [ <?php echo implode(', ', $labels) ?> ]
            };
            var ctx = document.getElementById("chart-area").getContext("2d");
            new Chart(ctx, {
                type: 'pie',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });

        </script>
        <?php
    }

    /**
     * @param array $pie_values
     */
    public static function create_donut( array $pie_values) {
        self::start();

        $itens = array();
        $labels = array();
        $colors = array();

        /** @var pie $pie */
        foreach ($pie_values as $pie) {
            $itens [] = $pie->value;
            $labels[] = '"' . $pie->label . '"';
            $colors[] = self::get_color();
        }
        ?>
        <div id="canvas-holder">
            <canvas id="chart-area"/>
        </div>
        <script>
            var data = {
                datasets: [{
                    data: [ <?php echo implode(', ', $itens) ?> ],
                    backgroundColor: [ <?php echo implode(', ', $colors) ?> ]
                }],
                labels: [ <?php echo implode(', ', $labels) ?> ]
            };
            var ctx = document.getElementById("chart-area").getContext("2d");
            new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        </script>
        <?php
    }
}