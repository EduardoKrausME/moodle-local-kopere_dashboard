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

defined('MOODLE_INTERNAL') || die();

/**
 * Class input_base
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_base implements i_input {
    /**
     *
     */
    const VAL_REQUIRED = 'required';
    const VAL_INT      = 'int';
    const VAL_VALOR    = 'valor';
    const VAL_PHONE    = 'phone';
    const VAL_CEP      = 'cep';
    const VAL_CPF      = 'cpf';
    const VAL_CNPJ     = 'cnpj';
    const VAL_NOME     = 'nome';
    const VAL_URL      = 'url';
    const VAL_EMAIL    = 'email';

    const MASK_PHONE = 'phone';
    const MASK_CELULAR = 'celular';
    const MASK_CEP = 'cep';
    const MASK_CPF = 'cpf';
    const MASK_CNPJ = 'cnpj';
    const MASK_DATAHORA = 'datahora';
    const MASK_DATA = 'data';
    const MASK_INT = 'int';
    const MASK_VALOR = 'valor';
    const MASK_FLOAT = 'float';

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $type;

    /** @var  string */
    protected $class;

    /** @var  string */
    protected $style;

    /** @var  string */
    protected $value;

    /** @var  string */
    protected $title;

    /** @var  string */
    protected $description;

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
     */
    public function set_value($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * @param $config_name
     *
     * @return $this
     */
    public function set_value_by_config($config_name) {
        $this->set_name($config_name);
        $this->set_value(
            get_config('local_kopere_dashboard', $config_name)
        );

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

        return $this;
    }

    /**
     * @param $validator
     *
     * @return $this
     */
    public function add_validator($validator) {
        if ($this->class) {
            $this->class .= " " . $validator;
        } else {
            $this->class = $validator;
        }

        return $this;
    }

    public function to_string() {
        $return = "<input ";

        $return .= "id=\"$this->name\" name=\"$this->name\" ";

        $return .= "type=\"$this->type\" ";

        if ($this->value !== null) {
            $return .= "value=\"" . htmlentities($this->value) . "\" ";
        }

        if ($this->class !== null) {
            $return .= "class=\"$this->class\" ";
        }

        if ($this->style !== null) {
            $return .= "style=\"$this->style\" ";
        }

        $return .= ">";

        return $return;
    }
}