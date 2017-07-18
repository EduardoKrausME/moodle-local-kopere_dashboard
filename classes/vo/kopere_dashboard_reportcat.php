<?php

namespace local_kopere_dashboard\vo;

class kopere_dashboard_reportcat extends \stdClass
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $image;

    /**
     * @var int
     */
    public $enable;

    /**
     * @var string
     */
    public $enablesql;


    /**
     * @return kopere_dashboard_reportcat
     */
    public static function createBlank ( $item )
    {
        $return = new kopere_dashboard_reportcat();

        $return->id = $item->id;
        $return->title = optional_param ( 'title', $item->title, PARAM_TEXT );
        $return->type = optional_param ( 'type', $item->type, PARAM_TEXT );
        $return->image = optional_param ( 'image', $item->image, PARAM_TEXT );
        $return->enable = optional_param ( 'enable', $item->enable, PARAM_INT );
        $return->enablesql = optional_param ( 'enablesql', $item->enablesql, PARAM_TEXT );

        return $return;
    }

    /**
     * @return kopere_dashboard_reportcat
     */
    public static function createNew ()
    {
        $return = new kopere_dashboard_reportcat();

        $return->id = optional_param ( 'id', 0, PARAM_INT );
        $return->title = optional_param ( 'title', '', PARAM_TEXT );
        $return->type = optional_param ( 'type', '', PARAM_TEXT );
        $return->image = optional_param ( 'image', '', PARAM_TEXT );
        $return->enable = optional_param ( 'enable', 1, PARAM_INT );
        $return->enablesql = optional_param ( 'enablesql', '', PARAM_TEXT );

        return $return;
    }

}