<?php

namespace local_kopere_dashboard\vo;

class kopere_dashboard_reports extends \stdClass
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $reportcatid;

    /**
     * @var string
     */
    public $reportkey;

    /**
     * @var string
     */
    public $title;

    /**
     * @var int
     */
    public $enable;

    /**
     * @var string
     */
    public $enablesql;

    /**
     * @var string
     */
    public $reportsql;

    /**
     * @var string
     */
    public $prerequisit;

    /**
     * @var string
     */
    public $columns;

    /**
     * @var string
     */
    public $foreach;


    /**
     * @return kopere_dashboard_reports
     */
    public static function createBlank ( $item )
    {
        $return = new kopere_dashboard_reports();

        $return->id = $item->id;
        $return->reportcatid = optional_param ( 'reportcatid', $item->reportcatid, PARAM_INT );
        $return->reportkey = optional_param ( 'reportkey', $item->reportkey, PARAM_TEXT );
        $return->title = optional_param ( 'title', $item->title, PARAM_TEXT );
        $return->enable = optional_param ( 'enable', $item->enable, PARAM_INT );
        $return->enablesql = optional_param ( 'enablesql', $item->enablesql, PARAM_TEXT );
        $return->reportsql = optional_param ( 'reportsql', $item->reportsql, PARAM_TEXT );
        $return->prerequisit = optional_param ( 'prerequisit', $item->prerequisit, PARAM_TEXT );
        $return->columns = optional_param ( 'columns', $item->columns, PARAM_TEXT );
        $return->foreach = optional_param ( 'foreach', $item->foreach, PARAM_TEXT );

        return $return;
    }

    /**
     * @return kopere_dashboard_reports
     */
    public static function createNew ()
    {
        $return = new kopere_dashboard_reports();

        $return->id = optional_param ( 'id', 0, PARAM_INT );
        $return->reportcatid = optional_param ( 'reportcatid', 0, PARAM_INT );
        $return->reportkey = optional_param ( 'reportkey', '', PARAM_TEXT );
        $return->title = optional_param ( 'title', '', PARAM_TEXT );
        $return->enable = optional_param ( 'enable', 1, PARAM_INT );
        $return->enablesql = optional_param ( 'enablesql', '', PARAM_TEXT );
        $return->reportsql = optional_param ( 'reportsql', '', PARAM_TEXT );
        $return->prerequisit = optional_param ( 'prerequisit', '', PARAM_TEXT );
        $return->columns = optional_param ( 'columns', '', PARAM_TEXT );
        $return->foreach = optional_param ( 'foreach', '', PARAM_TEXT );

        return $return;
    }

}