<?php

namespace local_kopere_dashboard\vo;

class kopere_dashboard_menu extends \stdClass
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $link;

    /**
     * @var string
     */
    public $title;


    /**
     * @return kopere_dashboard_menu
     */
    public static function createBlank ( $item )
    {
        $return = new kopere_dashboard_menu();

        $return->id = $item->id;
        $return->link = optional_param ( 'link', $item->link, PARAM_TEXT );
        $return->title = optional_param ( 'title', $item->title, PARAM_TEXT );

        return $return;
    }

    /**
     * @return kopere_dashboard_menu
     */
    public static function createNew ()
    {
        $return = new kopere_dashboard_menu();

        $return->id = 0;
        $return->link = optional_param ( 'link', '', PARAM_TEXT );
        $return->title = optional_param ( 'title', '', PARAM_TEXT );

        return $return;
    }

}