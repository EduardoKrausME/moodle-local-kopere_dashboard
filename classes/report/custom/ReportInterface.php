<?php
/**
 * User: Eduardo Kraus
 * Date: 25/05/17
 * Time: 11:02
 */

namespace local_kopere_dashboard\report\custom;


interface ReportInterface
{

    /**
     * @return string
     */
    public function name();
    /**
     * @return boolean
     */
    public function isEnable();

    /**
     * @return void
     */
    public function generate ( );

    /**
     * @return void
     */
    public function listData ( );
}