<?php
/**
 * User: Eduardo Kraus
 * Date: 10/06/17
 * Time: 23:26
 */

namespace local_kopere_dashboard\html\inputs;


class InputDateRange extends InputBase
{
    public function __construct ()
    {
        $this->setType ( 'text' );
    }

    /**
     * @return InputDateRange
     */
    public static function newInstance ()
    {
        return new InputDateRange();
    }

    public function setDateRange ()
    {
        $this->addValidator ( 'single-daterange' );

        return $this;
    }

    public function setDatetimeRange ()
    {
        $this->addValidator ( 'single-datetimerange' );

        return $this;
    }
}