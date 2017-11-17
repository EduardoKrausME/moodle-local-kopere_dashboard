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
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html;

defined('MOODLE_INTERNAL') || die();

class TableHeaderItem extends \stdClass {
    const TYPE_DATE = 'date';
    const TYPE_CURRENCY = 'numeric';
    const TYPE_INT = 'numeric';
    const TYPE_TEXT = 'text';
    const TYPE_BYTES = 'bytes';
    const TYPE_ACTION = 'action';

    const RENDERER_DATE = 'rendererdate';
    const RENDERER_VISIBLE = 'visible';
    const RENDERER_TRUEFALSE = 'truefalse';
    const RENDERER_STATUS = 'status';

    /**
     * @var
     */
    public $funcao;
    /**
     * @var string
     */
    public $title       = '';
    /**
     * @var string
     */
    public $type        = '';
    /**
     * @var string
     */
    public $chave       = '';
    /**
     * @var string
     */
    public $class       = '';
    /**
     * @var string
     */
    public $styleHeader = '';
    /**
     * @var string
     */
    public $styleCol = '';

    /**
     * @var int
     */
    public $cols = 0;
}
