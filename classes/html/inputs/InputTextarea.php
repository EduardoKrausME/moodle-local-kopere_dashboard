<?php
/**
 * User: Eduardo Kraus
 * Date: 10/06/17
 * Time: 23:06
 */

namespace local_kopere_dashboard\html\inputs;


class InputTextarea extends InputBase
{
    public function __construct ()
    {
        $this->setType ( 'textarea' );
    }

    /**
     * @return InputTextarea
     */
    public static function newInstance ()
    {
        return new InputTextarea();
    }

    /**
     * @return string
     */
    public function toString ()
    {
        $returnInput = "<textarea ";

        $returnInput .= "id=\"$this->name\" name=\"$this->name\" ";

        if ( $this->type )
            $returnInput .= "type=\"$this->type\" ";

        if ( $this->class )
            $returnInput .= "class=\"$this->class\" ";

        if ( $this->style )
            $returnInput .= "style=\"$this->style\" ";

        $returnInput .= ">";

        if ( $this->value )
            $returnInput .= htmlentities ( $this->value );

        $returnInput .= "</textarea>";

        return $returnInput;
    }
}