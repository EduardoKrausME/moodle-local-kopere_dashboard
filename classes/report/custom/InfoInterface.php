<?php
/**
 * User: Eduardo Kraus
 * Date: 25/05/17
 * Time: 10:07
 */

namespace local_kopere_dashboard\report\custom;


interface InfoInterface
{
    /**
     * @return boolean
     */
    public function isEnable();

    /**
     * @return string
     */
    public function getTypeName();
}