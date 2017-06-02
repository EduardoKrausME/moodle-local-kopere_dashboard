<?php
/**
 * User: Eduardo Kraus
 * Date: 01/06/17
 * Time: 15:44
 */

namespace local_kopere_dashboard;


use local_kopere_dashboard\util\DashboardUtil;

class About
{
    public function dashboard ()
    {
        DashboardUtil::startPage ( 'Sobre' );

        echo '<div class="element-box">
                  <p><img src="https://www.eduardokraus.com/logos/kopere_dashboard.svg" /></p>
                  <p>&nbsp;</p>
                  <p>Projeto open-source desenvolvido e mantido por <a target="_blank" href="https://www.eduardokraus.com/kopere-dashboard">Eduardo Kraus</a>.</p>
                  <p>Código disponível em  <a target="_blank" href="https://github.com/EduardoKrausME/moodle-local-kopere_dashboard">github.com/EduardoKrausME/moodle-local-kopere_dashboard</a>.</p>
                  <p>Ajuda esta <a target="_blank" href="https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/wiki">no Wiki</a>.</p>
                  <p>Achou algum BUG ou gostaria de sugerir melhorias abra uma <a href="https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/issues" target="_blank">issue</a>.</p>
              </div>';

        DashboardUtil::endPage ();
    }
}