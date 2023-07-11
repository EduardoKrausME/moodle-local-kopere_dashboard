<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @created    10/06/17 20:31
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

use local_kopere_dashboard\util\config;

/**
 * Class input_base
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_base implements i_input {

    const VAL_REQUIRED = 'required';
    const VAL_INT = 'val_int';
    const VAL_VALOR = 'val_valor';
    const VAL_PHONE = 'val_phone';
    const VAL_CELPHONE = 'val_celphone';
    const VAL_CEP = 'val_cep';
    const VAL_CPF = 'val_cpf';
    const VAL_CNPJ = 'val_cnpj';
    const VAL_NOME = 'val_nome';
    const VAL_URL = 'val_url';
    const VAL_EMAIL = 'val_email';
    const VAL_PASSWORD = 'val_password';

    const MASK_PHONE = 'mask_phone';
    const MASK_CELULAR = 'mask_celphone';
    const MASK_CEP = 'mask_cep';
    const MASK_CPF = 'mask_cpf';
    const MASK_CNPJ = 'mask_cnpj';
    const MASK_DATAHORA = 'mask_datahora';
    const MASK_DATA = 'mask_data';
    const MASK_INT = 'mask_int';
    const MASK_VALOR = 'mask_valor';
    const MASK_FLOAT = 'mask_float';

    /** @var  string */
    protected $name = null;

    /** @var  string */
    protected $type;

    /** @var  string */
    protected $class;

    /** @var  string */
    protected $style;

    /** @var  string */
    protected $value = null;

    /** @var  string */
    protected $title;

    /** @var  string */
    protected $description;

    /** @var  string */
    protected $extras = "";

    /** @var bool */
    protected $required = false;

    /**
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function set_name($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function set_type($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function get_class() {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function set_class($class) {
        $this->add_validator($class);

        return $this;
    }

    /**
     * @return string
     */
    public function get_style() {
        return $this->style;
    }

    /**
     * @param string $style
     *
     * @return $this
     */
    public function set_style($style) {
        $this->style = $style;

        return $this;
    }

    /**
     * @return string
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     * @throws \coding_exception
     */
    public function set_value($value) {
        if ($this->value == null && $this->name != null) {
            $this->value = optional_param($this->name, $value, PARAM_RAW);
        } else {
            $this->value = $value;
        }

        return $this;
    }

    /**
     * @param $configname
     *
     * @return $this
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function set_value_by_config($configname, $default = '') {
        $this->set_name($configname);

        $value = config::get_key($configname);
        if ($value == null) {
            $value = $default;
        }

        $this->set_value($value);

        return $this;
    }

    /**
     * @return string
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function set_title($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function set_description($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @return $this
     */
    public function set_required() {
        $this->add_validator(self::VAL_REQUIRED);
        $this->required = true;

        return $this;
    }

    /**
     * @param $validator
     *
     * @return $this
     */
    public function add_validator($validator) {
        if ($this->class) {
            $this->class .= " {$validator}";
        } else {
            $this->class = $validator;
        }

        return $this;
    }

    /**
     * @param $mask
     *
     * @return $this
     */
    public function add_mask($mask) {
        if ($this->class) {
            $this->class .= " {$mask}";
        } else {
            $this->class = $mask;
        }

        return $this;
    }

    public function add_extras($extra) {
        $this->extras .= " {$extra}";

        return $this;
    }

    public function to_string() {

        $inputid = preg_replace('/[\W]/', '', $this->name);

        $return = "<input id='{$inputid}'
                          name='{$this->name}'
                          type='{$this->type}'";

        if ($this->value !== null) {
            $return .= " value='" . htmlentities($this->value) . "' ";
        }

        if ($this->class !== null) {
            $return .= " class='{$this->class}' ";
        }

        if ($this->style !== null) {
            $return .= " style='{$this->style}' ";
        }

        if ($this->required) {
            $return .= " required ";
        }

        $return .= $this->extras;

        $return .= ">";

        return $return;
    }
}
