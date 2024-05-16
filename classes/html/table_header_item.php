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

class table_header_item extends \stdClass {
    const TYPE_DATE = 'date-uk';
    const TYPE_CURRENCY = 'currency';
    const TYPE_INT = 'numeric-comma';
    const TYPE_TEXT = 'text';
    const TYPE_BYTES = 'file-size';
    const TYPE_ACTION = 'action';

    const RENDERER_DATE = 'dataDateRenderer';
    const RENDERER_DATETIME = 'dataDatetimeRenderer';
    const RENDERER_VISIBLE = 'dataVisibleRenderer';
    const RENDERER_TRUEFALSE = 'dataTrueFalseRenderer';
    const RENDERER_STATUS = 'dataStatusRenderer';
    const RENDERER_USERPHOTO = 'dataUserphotoRenderer';
    const RENDERER_SEGUNDOS = 'segundosRenderer';

    /**
     * @var
     */
    public $funcao;
    /** @var string */
    public $title = '';
    /** @var string */
    public $type = '';
    /** @var string */
    public $chave = '';
    /** @var string */
    public $class = '';
    /** @var string */
    public $styleheader = '';
    /** @var string */
    public $stylecol = '';

    /** @var int */
    public $cols = 0;
}
