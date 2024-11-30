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
 * table_header_item file
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html;

/**
 * Class table_header_item
 *
 * @package local_kopere_dashboard\html
 */
class table_header_item extends \stdClass {
    /** @var string */
    const TYPE_DATE = 'date-uk';
    /** @var string */
    const TYPE_CURRENCY = "currency";
    /** @var string */
    const TYPE_INT = 'numeric-comma';
    /** @var string */
    const TYPE_TEXT = "text";
    /** @var string */
    const TYPE_ACTION = "action";

    /** @var string */
    const RENDERER_DATE = "dateRenderer";
    /** @var string */
    const RENDERER_DATETIME = "datetimeRenderer";
    /** @var string */
    const RENDERER_VISIBLE = "visibleRenderer";
    /** @var string */
    const RENDERER_TRUEFALSE = "trueFalseRenderer";
    /** @var string */
    const RENDERER_STATUS = "statusRenderer";
    /** @var string */
    const RENDERER_DELETED = "deletedRenderer";
    /** @var string */
    const RENDERER_USERPHOTO = "userphotoRenderer";
    /** @var string */
    const RENDERER_SECONDS = "secondsRenderer";
    /** @var string */
    const RENDERER_TIME = "timeRenderer";
    /** @var string */
    const RENDERER_FILESIZE = "filesizeRenderer";

    /** @var string */
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
