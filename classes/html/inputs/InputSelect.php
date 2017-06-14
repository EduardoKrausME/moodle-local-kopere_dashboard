<?php
/**
 * User: Eduardo Kraus
 * Date: 10/06/17
 * Time: 20:42
 */

namespace local_kopere_dashboard\html\inputs;


class InputSelect extends InputBase
{
    /**
     * @var array
     */
    private $values;
    /**
     * @var string
     */
    private $values_key;
    /**
     * @var string
     */
    private $values_value;

    /**
     * @var bool
     */
    private $addSelecione = false;


    /**
     * InputSelect constructor.
     */
    public function __construct ()
    {
        $this->setType ( 'select' );
    }

    /**
     * @return InputSelect
     */
    public static function newInstance ()
    {
        return new InputSelect();
    }

    /**
     * @return mixed
     */
    public function getValues ()
    {
        return $this->values;
    }

    /**
     * @param array  $values
     * @param string $key
     * @param string $value
     * @param bool   $addSelecione
     *
     * @return $this
     */
    public function setValues ( $values, $key = 'key', $value = 'value' )
    {
        $this->values       = $values;
        $this->values_key   = $key;
        $this->values_value = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAddSelecione ()
    {
        return $this->addSelecione;
    }

    /**
     * @param $addSelecione
     *
     * @return $this
     */
    public function setAddSelecione ( $addSelecione )
    {
        $this->addSelecione = $addSelecione;

        return $this;
    }


    /**
     * @return string
     */
    public function toString ()
    {
        $returnInput = "<select ";
        $returnInput .= "id=\"$this->name\" name=\"$this->name\" ";

        if ( $this->class )
            $returnInput .= "class=\"$this->class\" ";

        if ( $this->style )
            $returnInput .= "style=\"$this->style\" ";

        $returnInput .= ">";

        if ( $this->addSelecione )
            $returnInput .= "\n\t<option value=\"\">..::Selecione::..</option>";

        foreach ( $this->values as $row ) {
            $extra = '';
            if ( is_array ( $row ) ) {
                $key   = $row[ $this->values_key ];
                $value = $row[ $this->values_value ];

                if ( isset( $row[ 'disableselect' ] ) && $row[ 'disableselect' ] )
                    $extra = ' disabled="disabled"';
            } else {
                $key   = $row->{$this->values_key};
                $value = $row->{$this->values_value};

                if ( isset( $row->disableselect ) && $row->disableselect )
                    $extra = ' disabled="disabled"';
            }

            $returnInput .= "\n\t<option value=\"$key\"";
            if ( $key == $this->value )
                $returnInput .= ' selected="selected"';

            $returnInput .= $extra . '>';

            $returnInput .= htmlentities ( $value ) . '</option>';
        }

        $returnInput .= "\n</select>";

        return $returnInput;
    }
}