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
 * input_base file
 *
 * introduced 10/06/17 20:31
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

use local_kopere_dashboard\util\config;

/**
 * Class input_base
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_base implements i_input {

    /** @var string */
    const VAL_REQUIRED = "required";
    /** @var string */
    const VAL_INT = "val_int";
    /** @var string */
    const VAL_VALOR = "val_valor";
    /** @var string */
    const VAL_PHONE = "val_phone";
    /** @var string */
    const VAL_CELPHONE = "val_celphone";
    /** @var string */
    const VAL_CEP = "val_cep";
    /** @var string */
    const VAL_CPF = "val_cpf";
    /** @var string */
    const VAL_CNPJ = "val_cnpj";
    /** @var string */
    const VAL_NOME = "val_nome";
    /** @var string */
    const VAL_URL = "val_url";
    /** @var string */
    const VAL_EMAIL = "val_email";
    /** @var string */
    const VAL_PASSWORD = "val_password";

    /** @var string */
    const MASK_PHONE = "mask_phone";
    /** @var string */
    const MASK_CELULAR = "mask_celphone";
    /** @var string */
    const MASK_CEP = "mask_cep";
    /** @var string */
    const MASK_CPF = "mask_cpf";
    /** @var string */
    const MASK_CNPJ = "mask_cnpj";
    /** @var string */
    const MASK_DATAHORA = "mask_datahora";
    /** @var string */
    const MASK_DATA = "mask_data";
    /** @var string */
    const MASK_INT = "mask_int";
    /** @var string */
    const MASK_VALOR = "mask_valor";
    /** @var string */
    const MASK_FLOAT = "mask_float";

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

    /** @var string */
    protected $inputid = null;

    /**
     * Function get_name
     *
     * @return mixed|string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Function set_name
     *
     * @param $name
     *
     * @return $this|mixed
     */
    public function set_name($name) {
        $this->name = $name;

        $this->inputid = preg_replace('/[\W]/', '', $this->name);

        return $this;
    }

    /**
     * Function get_type
     *
     * @return mixed|string
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * Function set_type
     *
     * @param $type
     *
     * @return $this|mixed
     */
    public function set_type($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Function get_class
     *
     * @return mixed|string
     */
    public function get_class() {
        return $this->class;
    }

    /**
     * Function set_class
     *
     * @param $class
     *
     * @return $this|mixed
     */
    public function set_class($class) {
        $this->add_validator($class);

        return $this;
    }

    /**
     * Function get_style
     *
     * @return mixed|string
     */
    public function get_style() {
        return $this->style;
    }

    /**
     * Function set_style
     *
     * @param $style
     *
     * @return $this|mixed
     */
    public function set_style($style) {
        $this->style = $style;

        return $this;
    }

    /**
     * Function get_value
     *
     * @return mixed|string
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * Function set_value
     *
     * @param $value
     *
     * @return $this|mixed
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
     * Function set_value_by_config
     *
     * @param $configname
     * @param string $default
     *
     * @return $this|mixed
     * @throws \coding_exception
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
     * Function get_title
     *
     * @return mixed|string
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Function set_title
     *
     * @param $title
     *
     * @return $this|mixed
     */
    public function set_title($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Function get_description
     *
     * @return mixed|string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Function set_description
     *
     * @param $description
     *
     * @return $this|mixed
     */
    public function set_description($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Function set_required
     *
     * @return $this|mixed
     */
    public function set_required() {
        $this->add_validator(self::VAL_REQUIRED);
        $this->required = true;

        return $this;
    }

    /**
     * Function add_validator
     *
     * @param $validator
     *
     * @return $this|mixed
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
     * Function add_mask
     *
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

    /**
     * Function add_extras
     *
     * @param $extra
     *
     * @return $this
     */
    public function add_extras($extra) {
        $this->extras .= " {$extra}";

        return $this;
    }

    /**
     * Function to_string
     *
     * @return mixed|string
     */
    public function to_string() {
        $return = "<input id='{$this->inputid}'
                          name='{$this->name}'
                          type='{$this->type}'";

        if ($this->value !== null) {
            $return .= " value='" . htmlentities($this->value, ENT_COMPAT) . "' ";
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

    /**
     * Function get_inputid
     *
     * @return string
     */
    public function get_inputid(): string {
        return $this->inputid;
    }
}
