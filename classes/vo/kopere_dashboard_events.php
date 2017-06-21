<?php

namespace local_kopere_dashboard\vo;

class kopere_dashboard_events extends \stdClass
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $module;

    /**
     * @var string
     */
    public $event;

    /**
     * @var int
     */
    public $status = 1;

    /**
     * @var string
     */
    public $userfrom;

    /**
     * @var string
     */
    public $userto;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $message;


    /**
     * @return kopere_dashboard_events
     */
    public static function createBlank ( $item )
    {
        $return = new kopere_dashboard_events();

        $return->id       = $item->id;
        $return->module   = optional_param ( 'module', $item->module, PARAM_TEXT );
        $return->event    = optional_param ( 'event', $item->event, PARAM_TEXT );
        $return->status   = optional_param ( 'status', $item->status, PARAM_INT );
        $return->userfrom = optional_param ( 'userfrom', $item->userfrom, PARAM_TEXT );
        $return->userto   = optional_param ( 'userto', $item->userto, PARAM_TEXT );
        $return->subject  = optional_param ( 'subject', $item->subject, PARAM_TEXT );
        $return->message  = optional_param ( 'message', $item->message, PARAM_RAW );

        return $return;
    }

    /**
     * @return kopere_dashboard_events
     */
    public static function createNew ()
    {
        $return = new kopere_dashboard_events();

        $return->id       = optional_param ( 'id', 0, PARAM_INT );
        $return->module   = optional_param ( 'module', '', PARAM_TEXT );
        $return->event    = optional_param ( 'event', '', PARAM_TEXT );
        $return->status   = optional_param ( 'status', 1, PARAM_INT );
        $return->userfrom = optional_param ( 'userfrom', '', PARAM_TEXT );
        $return->userto   = optional_param ( 'userto', '', PARAM_TEXT );
        $return->subject  = optional_param ( 'subject', '', PARAM_TEXT );
        $return->message  = optional_param ( 'message', '', PARAM_RAW );

        return $return;
    }

}