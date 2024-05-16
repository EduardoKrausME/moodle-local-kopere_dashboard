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
 * User: Eduardo Kraus
 * Date: 04/07/17
 * Time: 08:55
 */

namespace local_kopere_dashboard\util;

/**
 * Class navigation
 *
 * @package local_kopere_dashboard\util
 */
class navigation {
    /**
     * @param     $atualpage
     * @param     $totalregisters
     * @param     $baseurl
     * @param int $perpag
     */
    public static function create($atualpage, $totalregisters, $baseurl, $perpag = 20) {
        $countpages = intval($totalregisters / $perpag);

        if (($totalregisters % $perpag) != 0) {
            $countpages += 1;
        }

        echo "<span class='pagination-info'>" . get_string_kopere('navigation_page',
                ['atualPage' => $atualpage, 'countPages' => $countpages]) . "</span>";

        echo "<ul class='pagination'>";
        if ($atualpage != 1) {
            echo "<li><a href='{$baseurl}1' >«</a></li>";
        }
        $i = $atualpage - 4;
        if ($i < 1) {
            $i = 1;
        }
        if ($i != 1) {
            echo "<li><span>...</span></li>";
        }

        $loop = 0;
        for (; $i <= $countpages; $i++) {
            if ($i == $atualpage) {
                echo "<li class='active'><span>{$i}</span></li>";
            } else {
                echo "<li><a href='{$baseurl}{$i}'>{$i}</a></li>";
            }

            $loop++;
            if ($loop == 7) {
                if ($i != $countpages) {
                    echo "<span>...</span></li>";
                }
                break;
            }
        }
        if (($atualpage) != $countpages && $countpages > 1) {
            echo "<li><a href='{$baseurl}{$countpages}'>»</a></li>";
        }
        echo "</ul>";
    }
}
