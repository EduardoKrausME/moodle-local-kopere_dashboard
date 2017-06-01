<?php

namespace local_kopere_dashboard\vo;

class kopere_dashboard_webpages extends \stdClass
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $menuid;

    /**
     * @var int
     */
    public $courseid;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $link;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $theme;

    /**
     * @var int
     */
    public $visible;

    /**
     * @var int
     */
    public $pageorder;

    /**
     * @var string
     */
    public $config;


    /**
     * @return kopere_dashboard_webpages
     */
    public static function createBlank ( $item )
    {
        $return = new kopere_dashboard_webpages();

        $return->id        = $item->id;
        $return->menuid    = optional_param ( 'menuid', $item->menuid, PARAM_INT );
        $return->courseid  = optional_param ( 'courseid', $item->courseid, PARAM_INT );
        $return->title     = optional_param ( 'title', $item->title, PARAM_TEXT );
        $return->link      = optional_param ( 'link', $item->link, PARAM_TEXT );
        $return->text      = optional_param ( 'text', $item->text, PARAM_RAW );
        $return->theme     = optional_param ( 'theme', $item->theme, PARAM_TEXT );
        $return->visible   = optional_param ( 'visible', $item->visible, PARAM_INT );
        $return->pageorder = optional_param ( 'pageorder', $item->pageorder, PARAM_INT );
        $return->config    = optional_param ( 'config', $item->config, PARAM_TEXT );

        return $return;
    }

    /**
     * @return kopere_dashboard_webpages
     */
    public static function createNew ()
    {
        $return = new kopere_dashboard_webpages();

        $return->id        = 0;
        $return->menuid    = optional_param ( 'menuid', 0, PARAM_INT );
        $return->courseid  = optional_param ( 'courseid', 0, PARAM_INT );
        $return->title     = optional_param ( 'title', '', PARAM_TEXT );
        $return->link      = optional_param ( 'link', '', PARAM_TEXT );
        $return->text      = optional_param ( 'text', '', PARAM_RAW );
        $return->theme     = optional_param ( 'theme', '', PARAM_TEXT );
        $return->visible   = optional_param ( 'visible', 1, PARAM_INT );
        $return->pageorder = optional_param ( 'pageorder', 0, PARAM_INT );
        $return->config    = optional_param ( 'config', '', PARAM_TEXT );

        return $return;
    }

}