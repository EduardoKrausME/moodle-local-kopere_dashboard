<?php
/**
 * User: Eduardo Kraus
 * Date: 23/12/2018
 * Time: 12:07
 */

namespace local_kopere_dashboard\util;


class submenu_util {
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $icon;

    /**
     * @return string
     */
    public function get_host() {
        return $this->host;
    }

    /**
     * @param string $host
     * @return submenu_util
     */
    public function set_host($host) {
        $this->host = $host;
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
     * @return submenu_util
     */
    public function set_title($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function get_icon() {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return submenu_util
     */
    public function set_icon($icon) {
        $this->icon = $icon;
        return $this;
    }

}