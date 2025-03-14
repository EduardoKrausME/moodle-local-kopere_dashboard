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
 * button file
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html;

/**
 * Class button
 *
 * @package local_kopere_dashboard\html
 */
class button {

    /**
     * Function get_instance
     *
     * @return button
     */
    public static function get_instance() {
        return new button();
    }

    /** @var array */
    private $class = ["btn"];

    /**
     * Function set_no_btn
     *
     * @return button
     */
    public function set_no_btn() {
        $this->class = [];

        return $this;
    }

    /**
     * Function set_primary
     *
     * @return button
     */
    public function set_primary() {
        $this->class[] = "btn-primary";

        return $this;
    }

    /**
     * Function set_success
     *
     * @return button
     */
    public function set_success() {
        $this->class[] = "btn-success";

        return $this;
    }

    /**
     * Function set_danger
     *
     * @return button
     */
    public function set_danger() {
        $this->class[] = "btn-danger";

        return $this;
    }

    /**
     * Function set_info
     *
     * @return button
     */
    public function set_info() {
        $this->class[] = "btn-info";

        return $this;
    }

    /**
     * Function set_warning
     *
     * @return button
     */
    public function set_warning() {
        $this->class[] = "btn-warning";

        return $this;
    }

    /**
     * Function set_size_xs
     *
     * @return button
     */
    public function set_size_xs() {
        $this->class[] = "btn-xs";

        return $this;
    }

    /**
     * Function set_size_sm
     *
     * @return button
     */
    public function set_size_sm() {
        $this->class[] = "btn-sm";

        return $this;
    }

    /**
     * Function set_size_lg
     *
     * @return button
     */
    public function set_size_lg() {
        $this->class[] = "btn-lg";

        return $this;
    }

    /**
     * Function add_class
     *
     * @param string $class
     *
     * @return button
     */
    public function add_class($class) {
        $this->class[] = $class;

        return $this;
    }

    /**
     * Function set_target
     *
     * @param string $target
     *
     * @return button
     */
    public function set_target($target) {
        $this->tags = "{$this->tags} target='{$target}'";

        return $this;
    }

    /** @var string */
    private $link;

    /**
     * Function setLink
     *
     * @param string $link
     *
     * @return button
     */
    public function set_link($link) {
        $this->link = $link;

        return $this;
    }

    /**
     * Var tags
     *
     * @var string
     */
    private $tags = "";

    /**
     * Function add_tag
     *
     * @param string $tag
     *
     * @return $this
     */
    public function add_tag($tag) {
        $this->tags = "{$this->tags} {$tag}";

        return $this;
    }

    /**
     * Var paddingtop
     *
     * @var int
     */
    private $paddingtop = 20;

    /**
     * Function set_padding_top
     *
     * @param int $top
     */
    public function set_padding_top($top) {
        $this->paddingtop = $top;
    }

    /**
     * Function to_string
     *
     * @param string $text
     * @param bool $paragraph
     * @param bool $return
     *
     * @return string
     */
    public function to_string($text, $paragraph = true, $return = false) {
        global $OUTPUT;

        $data = [
            "link" => $this->link,
            "tags" => $this->tags,
            "class" => $this->class,
            "text" => $text,
            "paragraph" => $paragraph,
            "paddingtop" => $this->paddingtop,
        ];

        $bt = $OUTPUT->render_from_template("local_kopere_dashboard/html-button", $data);

        if ($return) {
            return $bt;
        } else {
            echo $bt;
        }
        return "";
    }

    // Old static function.

    /**
     * Function close_popup
     *
     * @param string $text
     */
    public static function close_popup($text) {
        echo "<button class=\"btn btn-primary margin-left-10\" data-dismiss=\"modal\">{$text}</button>";
    }

    /**
     * Function add
     *
     * @param string $text
     * @param string $link
     * @param string $class
     * @param bool $p
     * @param bool $return
     *
     * @return string
     */
    public static function add($text, $link, $class = "", $p = true, $return = false) {
        $class = "btn btn-primary {$class}";

        return self::create_button($text, $link, $p, $class, $return);
    }

    /**
     * Function edit
     *
     * @param string $text
     * @param string $link
     * @param string $class
     * @param bool $p
     * @param bool $return
     *
     * @return string
     */
    public static function edit($text, $link, $class = "", $p = true, $return = false) {
        $class = "btn btn-success {$class}";

        return self::create_button($text, $link, $p, $class, $return);
    }

    /**
     * Function delete
     *
     * @param string $text
     * @param string $link
     * @param string $class
     * @param bool $p
     * @param bool $return
     *
     * @return string
     */
    public static function delete($text, $link, $class = "", $p = true, $return = false) {
        $class = "btn btn-danger {$class}";

        return self::create_button($text, $link, $p, $class, $return);
    }

    /**
     * Function primary
     *
     * @param string $text
     * @param string $link
     * @param string $class
     * @param bool $p
     * @param bool $return
     *
     * @return string
     */
    public static function primary($text, $link, $class = "", $p = true, $return = false) {
        $class = "btn btn-primary {$class}";

        return self::create_button($text, $link, $p, $class, $return);
    }

    /**
     * Function info
     *
     * @param string $text
     * @param string $link
     * @param string $class
     * @param bool $p
     * @param bool $return
     *
     * @return string
     */
    public static function info($text, $link, $class = "", $p = true, $return = false) {
        $class = "btn btn-info {$class}";

        return self::create_button($text, $link, $p, $class, $return);
    }

    /**
     * Function help
     *
     * @param string $infourl
     * @param null $text
     * @param string $hastag
     *
     * @return string
     * @throws \coding_exception
     */
    public static function help($infourl, $text = null, $hastag = "wiki-wrapper") {
        global $CFG;

        if ($text == null) {
            $text = get_string("help_title", "local_kopere_dashboard");
        }

        return "<a href='https://github.com/EduardoKrausME/moodle-local-kopere_dashboard/wiki/{$infourl}#{$hastag}'
                   target=\"_blank\" class=\"help\">
                  <img src='{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/help.svg' style='height: 30px' >
                  $text
              </a>";
    }

    /**
     * Function icon
     *
     * @param string $icon
     * @param string $link
     *
     * @return string
     */
    public static function icon($icon, $link) {
        global $CFG;
        return "<a href='{$link}'>
                    <img src='{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/actions/{$icon}.svg' width=\"19\">
                </a>";
    }

    /**
     * Function icon confirm
     *
     * @param string $icon
     * @param string $link
     * @param string $confirmtext
     * @param string $confirmtitle
     *
     * @return string
     */
    public static function icon_confirm($icon, $link, $confirmtext, $confirmtitle) {
        global $OUTPUT, $PAGE;

        $data = [
            "button_id" => uniqid(),
            "link" => $link,
            "icon" => $icon,
            "confirmtitle" => $confirmtitle,
            "confirmtext" => $confirmtext,
        ];
        $PAGE->requires->js_call_amd("local_kopere_dashboard/button_icon", "action",
            [$data["button_id"], $data["link"]]);
        return $OUTPUT->render_from_template("local_kopere_dashboard/html-button_icon", $data);
    }

    /**
     * Function icon_popup_table
     *
     * @param string $icon
     * @param string $link
     *
     * @return string
     */
    public static function icon_popup_table($icon, $link) {
        global $CFG;
        return
            "<a href='{$link}'>" .
            "    <img src='{$CFG->wwwroot}/local/kopere_dashboard/assets/dashboard/img/actions/{$icon}.svg'" .
            "         width=\"19\" role=\"button\">" .
            "</a>";
    }

    /**
     * Function create_button
     *
     * @param string $text
     * @param string $link
     * @param bool $p
     * @param string $class
     * @param bool $return
     *
     * @return string
     */
    private static function create_button($text, $link, $p, $class, $return) {
        $bt = "<a href='{$link}' class='{$class}' >{$text}</a>";
        if ($p) {
            $bt = "<div style='width:100%;min-height:30px;padding:0 0 20px;'>{$bt}</div>";
        }

        if ($return) {
            return $bt;
        } else {
            echo $bt;
            return "";
        }
    }
}
