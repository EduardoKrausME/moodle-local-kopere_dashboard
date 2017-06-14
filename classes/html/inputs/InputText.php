<?php
/**
 * User: Eduardo Kraus
 * Date: 10/06/17
 * Time: 20:43
 */

namespace local_kopere_dashboard\html\inputs;


class InputText extends InputBase
{
    public function __construct ()
    {
        $this->setType ( 'text' );
    }

    /**
     * @return InputText
     */
    public static function newInstance ()
    {
        return new InputText();
    }
}