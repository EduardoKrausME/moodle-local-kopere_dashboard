<?php
/**
 * User: Eduardo Kraus
 * Date: 13/05/17
 * Time: 13:29
 */

namespace local_kopere_dashboard;


use local_kopere_dashboard\util\DashboardUtil;

class Gamification
{
    public function dashboard ()
    {
        DashboardUtil::startPage ( 'Gamification' );

        echo '<div class="element-box">';
        echo '<h3>Gamification dos seus alunos</h3>';


        echo '</div>';
        DashboardUtil::endPage ();
    }
}