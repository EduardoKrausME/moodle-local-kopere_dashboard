<?php

namespace local_kopere_dashboard\html;

use local_kopere_dashboard\html\inputs\IInput;

class Form
{

    private $formAction;

    function __construct ( $formAction = null, $classExtra = '' )
    {
        if ( $this->formAction ) {
            $this->formAction = '?' . $formAction;
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

    public function printPanel ( $titulo, $panelBody )
    {
        echo '<div class="form-group">
                  <label>' . $titulo . '</label>
                  <div class="panel panel-default">
                      <div class=" panel-body ">' . $panelBody . '</div>
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

    public function addInput ( IInput $input )
    {
        if ( $input->getType () == 'checkbox' )
            $this->createHiddenInput ( $input->getName (), 0 );

        $this->printRow (
            $input->getTitle (),
            $input->toString (),
            $input->getName (),
            $input->getDescription ()
        );
    }

    public function createHiddenInput ( $name, $value = '' )
    {
        echo '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars ( $value ) . '"/>';
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

    public static function print_r ( $array )
    {
        echo '<pre>';
        echo print_r ( $array, 1 );
        echo '</pre>';
    }
}
