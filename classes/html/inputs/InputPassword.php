<?php
/**
 * User: Eduardo Kraus
 * Date: 10/06/17
 * Time: 20:45
 */

namespace local_kopere_dashboard\html\inputs;


class InputPassword extends InputBase
{
    public function __construct ()
    {
        $this->setType ( 'select' );
    }

    /**
     * @return InputPassword
     */
    public static function newInstance ()
    {
        return new InputPassword();
    }

    public function toString ()
    {
        $this->setValue ( null );

        return parent::toString ();
    }
}