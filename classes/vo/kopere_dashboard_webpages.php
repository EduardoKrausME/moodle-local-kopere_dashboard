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

namespace local_kopere_dashboard\vo;

use local_kopere_dashboard\util\html;

/**
 * Class kopere_dashboard_webpages
 * @package local_kopere_dashboard\vo
 */
class kopere_dashboard_webpages extends \stdClass {

    /** @var int */
    public $id;

    /** @var int */
    public $menuid;

    /** @var int */
    public $courseid;

    /** @var string */
    public $title;

    /** @var string */
    public $link;

    /** @var string */
    public $text;

    /** @var string */
    public $theme;

    /** @var int */
    public $visible;

    /** @var int */
    public $pageorder;

    /** @var string */
    public $config;

    /**
     * @param $item
     * @return kopere_dashboard_webpages
     * @throws \coding_exception
     */
    public static function create_by_object($item) {
        $return = new kopere_dashboard_webpages();

        $return->id = $item->id;
        $return->menuid = optional_param('menuid', $item->menuid, PARAM_INT);
        $return->courseid = optional_param('courseid', $item->courseid, PARAM_INT);
        $return->title = optional_param('title', $item->title, PARAM_TEXT);
        $return->link = html::link(optional_param('link', $item->link, PARAM_TEXT));
        $return->text = optional_param('text', $item->text, PARAM_RAW);
        $return->theme = optional_param('theme', $item->theme, PARAM_TEXT);
        $return->visible = optional_param('visible', $item->visible, PARAM_INT);
        $return->pageorder = optional_param('pageorder', $item->pageorder, PARAM_INT);
        $return->config = optional_param('config', $item->config, PARAM_TEXT);

        return $return;
    }

    /**
     * @return kopere_dashboard_webpages
     * @throws \coding_exception
     */
    public static function create_by_default() {
        $return = new kopere_dashboard_webpages();

        $return->id = 0;
        $return->menuid = optional_param('menuid', 0, PARAM_INT);
        $return->courseid = optional_param('courseid', 0, PARAM_INT);
        $return->title = optional_param('title', '', PARAM_TEXT);
        $return->link = html::link(optional_param('link', '', PARAM_TEXT));
        $return->text = optional_param('text', '', PARAM_RAW);
        $return->theme = optional_param('theme', '', PARAM_TEXT);
        $return->visible = optional_param('visible', 1, PARAM_INT);
        $return->pageorder = optional_param('pageorder', 0, PARAM_INT);
        $return->config = optional_param('config', '', PARAM_TEXT);

        return $return;
    }
}
