<?php
/**
 * User: Eduardo Kraus
 * Date: 10/06/17
 * Time: 23:33
 */

namespace local_kopere_dashboard\html\inputs;


class InputCheckbox extends InputBase
{
    /**
     * @var bool
     */
    private $checked = false;

    /**
     * InputCheckbox constructor.
     */
    public function __construct ()
    {
        $this->setType ( 'checkbox' );
        $this->setValue ( '1' );
    }

    /**
     * @return InputCheckbox
     */
    public static function newInstance ()
    {
        return new InputCheckbox();
    }

    /**
     * @return bool
     */
    public function isChecked ()
    {
        return $this->checked;
    }

    /**
     * @param $checked
     *
     * @return $this
     */
    public function setChecked ( $checked )
    {
        $this->checked = $checked;

        return $this;
    }


    /**
     * @param $configName
     *
     * @return $this
     */
    public function setCheckedByConfig ( $configName )
    {
        $this->setName ( $configName );
        if ( get_config ( 'local_kopere_dashboard', $configName ) )
            $this->checked = true;

        return $this;
    }

    /**
     * @return string
     */
    public function toString ()
    {
        $returnInput = "<input ";

        $returnInput .= "id=\"$this->name\" name=\"$this->name\" type=\"checkbox\" ";

        if ( $this->checked )
            $returnInput .= 'checked="checked" ';

        if ( $this->value )
            $returnInput .= "value=\"" . htmlentities ( $this->value ) . "\" ";

        if ( $this->class )
            $returnInput .= "class=\"$this->class\" ";

        if ( $this->style )
            $returnInput .= "style=\"$this->style\" ";

        $returnInput .= ">";

        return $returnInput;
    }
}