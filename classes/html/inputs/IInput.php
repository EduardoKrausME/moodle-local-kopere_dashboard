<?php
/**
 * User: Eduardo Kraus
 * Date: 10/06/17
 * Time: 20:30
 */

namespace local_kopere_dashboard\html\inputs;


interface IInput
{
    /**
     * @return string
     */
    public function getName ();

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName ( $name );

    /**
     * @return string
     */
    public function getType ();

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType ( $type );

    /**
     * @return string
     */
    public function getClass ();

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass ( $class );

    /**
     * @return string
     */
    public function getStyle ();

    /**
     * @param string $style
     *
     * @return $this
     */
    public function setStyle ( $style );

    /**
     * @return string
     */
    public function getValue ();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue ( $value );

    /**
     * @param $configName
     *
     * @return $this
     */
    public function setValueByConfig ( $configName );

    /**
     * @return string
     */
    public function getTitle ();

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle ( $title );

    /**
     * @return string
     */
    public function getDescription ();

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription ( $description );

    /**
     * @return $this
     */
    public function setRequired ();

    /**
     * @param $validator
     *
     * @return $this
     */
    public function addValidator ( $validator );

    public function toString ();
}