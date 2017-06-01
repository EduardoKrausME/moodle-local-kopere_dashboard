<?php

namespace local_kopere_dashboard\html;

class Form
{

    private $formAction;

    function __construct ( $formAction = null, $classExtra = '' )
    {
        $this->formAction = $formAction;
        if ( $this->formAction ) {
            echo "<form method=\"post\" class=\"validate $classExtra\" enctype=\"multipart/form-data\" action=\"{$this->formAction}\" >";
            echo '<div class="displayErroForm alert alert-danger" style="display: none;"><span></span></div>';
            echo '<input name="POST" type="hidden" value="true" />';
        }
    }

    public function printRow ( $titulo, $input, $name = '', $adicionalText = '' )
    {
        echo '<div class="form-group area_' . $name . '">
                  <label for="' . $name . '">' . $titulo . '</label>
                  ' . $input . '
                  <div class="help-block form-text with-errors">' . $adicionalText . '</div>
              </div>';
    }

    public function printPanel ( $titulo, $input )
    {
        echo '<div class="form-group">
                  <label>' . $titulo . '</label>
                  <div class="panel panel-default">
                      <div class=" panel-body ">' . $input . '</div>
                  </div>
              </div>';
    }

    public function printRowOne ( $titulo, $input, $name = '', $adicionalText = '' )
    {
        echo '<div class="form-check area_' . $name . '"">
                  <label for="' . $name . '" class="form-check-label">
                      ' . $input . ' ' . $titulo . '</label>
                  <div class="help-block form-text with-errors form-control-feedback-">' . $adicionalText . '</div>
              </div>';
    }

    public function printSection ( $sectionTitle )
    {
        echo '<div class="form-section"><span>' . $sectionTitle . '</span></div>';
    }

    public function printSpacer ( $height )
    {
        echo '<div class="form-group" style="height: ' . $height . 'px">&nbsp;</div>';
    }

    public function createTextInput ( $titulo, $name, $value = '', $class = '', $additionalText = '', $style = '' )
    {
        $value = str_replace ( '"', '&quot;', $value );
        $html  = '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '" class="' . $class . '" style="' . $style . '"  />';
        $this->printRow ( $titulo, $html, $name, $additionalText );
    }

    public function createDataInput ( $titulo, $name, $value = '', $class = '', $additionalText = '', $style = '' )
    {
        preg_match ( "/\d\d:\d\d/", $value, $test );
        if ( isset( $test[ 0 ] ) )
            $class2 = 'single-datetimerange ';
        else
            $class2 = 'single-daterange ';

        $value = str_replace ( '"', '&quot;', $value );
        $html  = '<input type="text" name="' . $name . '" id="' . $name . '" value="' . htmlspecialchars ( $value ) . '" class="' . $class2 . $class . '" style="' . $style . '"  />';
        $this->printRow ( $titulo, $html, $name, $additionalText );
    }

    public function createFileInput ( $titulo, $name, $class = '', $additionalText = '', $style = '' )
    {
        $html = '<input type="file" name="' . $name . '" id="' . $name . '" class="' . $class . '" style="' . $style . '"  />';
        $this->printRow ( $titulo, $html, $name, $additionalText );
    }

    public function createPasswordInput ( $titulo, $name, $class = '', $additionalText = '', $style = '', $value = '' )
    {
        $html = '<input type="password" name="' . $name . '" id="' . $name . '" class="' . $class . '" style="' . $style . '"  value="' . htmlspecialchars ( $value ) . '" />';
        $this->printRow ( $titulo, $html, $name, $additionalText );
    }

    public function createHiddenInput ( $name, $value = '' )
    {
        echo '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . htmlspecialchars ( $value ) . '"/>';
    }

    public function createTextArea ( $titulo, $name, $value = '', $class = '', $additionalText = '', $style = '' )
    {
        $value = str_replace ( "\n", '&#10;', $value );
        $value = str_replace ( "\r", '&#13;', $value );

        $html = '<textarea name="' . $name . '" id="' . $name . '" class="' . $class . '" style="' . $style . '">' . htmlspecialchars ( $value ) . '</textarea>';
        $this->printRow ( $titulo, $html, $name, $additionalText );
    }

    public function createHtmlEditor ( $titulo, $name, $value = '', $class = '', $additionalText = '', $style = '' )
    {
        $this->createTextArea ( $titulo, $name, $value, $class, $additionalText, $style . 'height:500px' );

        TinyMce::createInputEditor ( '#' . $name );
    }

    public function createSelectInput ( $titulo, $name, $values, $selected = '', $class = '', $additionalText = '' )
    {
        $html = '<select name="' . $name . '" id="' . $name . '" class="' . $class . '" >';
        foreach ( $values as $row ) {
            if ( !is_array ( $row ) )
                $row = array( $row, $row );

            $html .= '<option value="' . $row[ 0 ] . '"';
            if ( $row[ 0 ] == $selected )
                $html .= ' selected="selected"';
            $html .= '>' . htmlentities ( $row[ 1 ] ) . '</option>';
        }
        $html .= '</select>';
        $this->printRow ( $titulo, $html, $name, $additionalText );
    }

    public function createCheckboxInput ( $titulo, $name, $value, $checked = false, $class = '', $additionalText = '' )
    {
        $c = '';
        if ( $checked )
            $c = 'checked="checked"';
        $html = '<input type="hidden"   name="' . $name . '"                    value="0"/>';
        $html .= '<input type="checkbox" name="' . $name . '" id="' . $name . '" value="' . htmlspecialchars ( $value ) . '" ' . $c . '  class="' . $class . '" />';
        $this->printRowOne ( $titulo, $html, $name, $additionalText );
    }


    public function createSubmitInput ( $value = '', $class = '', $additionalText = '' )
    {
        $html = '<input name="" class="btn btn-success ' . $class . '" type="submit" value="' . htmlspecialchars ( $value ) . '" />';
        $this->printRow ( '', $html, 'btsubmit', $additionalText );
    }

    public function close ()
    {
        if ( $this->formAction )
            echo '</form>';
    }

    public function closeAndAutoSubmitInput ( $campo )
    {
        echo "<input id=\"submit_$campo\" name=\"\" type=\"submit\" style='display: none;' />
              <script>
                  $( '#$campo' ).change( function() {
                      $( '#submit_$campo' ).click();
                  });
              </script>";


        $this->close ();
    }

    public static function createAdditionalText ( $text )
    {
        return '<span class="coments">' . $text . '</span>';
    }

    public static function print_r ( $array )
    {
        echo '<pre>';
        echo print_r ( $array, 1 );
        echo '</pre>';
    }
}
