<?php
/**
 * User: Eduardo Kraus
 * Date: 10/06/17
 * Time: 23:13
 */

namespace local_kopere_dashboard\html\inputs;


use local_kopere_dashboard\html\TinyMce;

class InputHtmlEditor extends InputTextarea
{
    /**
     * @return InputHtmlEditor
     */
    public static function newInstance ()
    {
        return new InputHtmlEditor();
    }

    /**
     * @return string
     */
    public function toString ()
    {
        $this->setStyle ( $this->getStyle () . ';height:500px' );

        $returnInput = parent::toString ();
        $returnInput .= TinyMce::createInputEditor ( '#' . $this->getName () );

        return $returnInput;
    }
}