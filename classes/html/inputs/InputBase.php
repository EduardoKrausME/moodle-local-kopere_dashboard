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
 * Class InputBase
 *
 * @package local_kopere_dashboard\html\inputs
 */
class InputBase implements IInput {
    const VAL_REQUIRED = 'required';
    const VAL_INT = 'int';
    const VAL_VALOR = 'valor';
    const VAL_PHONE = 'phone';
    const VAL_CEP = 'cep';
    const VAL_CPF = 'cpf';
    const VAL_CNPJ = 'cnpj';
    const VAL_NOME = 'nome';
    const VAL_URL = 'url';
    const VAL_EMAIL = 'email';

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
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class) {
        $this->addValidator($class);

        return $this;
    }

    /**
     * @return string
     */
    public function getStyle() {
        return $this->style;
    }

    /**
     * @param string $style
     *
     * @return $this
     */
    public function setStyle($style) {
        $this->style = $style;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * @param $configName
     *
     * @return $this
     */
    public function setValueByConfig($configName) {
        $this->setName($configName);
        $this->setValue(
            get_config('local_kopere_dashboard', $configName)
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @return $this
     */
    public function setRequired() {
        $this->addValidator(self::VAL_REQUIRED);

        return $this;
    }

    /**
     * @param $validator
     *
     * @return $this
     */
    public function addValidator($validator) {
        if ($this->class) {
            $this->class .= " " . $validator;
        } else {
            $this->class = $validator;
        }

        return $this;
    }

    public function toString() {
        $returnInput = "<input ";

        $returnInput .= "id=\"$this->name\" name=\"$this->name\" ";

        $returnInput .= "type=\"$this->type\" ";

        if ($this->value !== null) {
            $returnInput .= "value=\"" . htmlentities($this->value) . "\" ";
        }

        if ($this->class !== null) {
            $returnInput .= "class=\"$this->class\" ";
        }

        if ($this->style !== null) {
            $returnInput .= "style=\"$this->style\" ";
        }

        $returnInput .= ">";

        return $returnInput;
    }
}