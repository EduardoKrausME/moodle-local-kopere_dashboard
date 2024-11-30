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
 * input_textarea file
 *
 * introduced 10/06/17 23:06
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

use editor_tiny\manager;

/**
 * Class input_textarea
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_htmleditor extends input_textarea {

    /**
     * Function new_instance
     *
     * @return input_textarea
     */
    public static function new_instance() {
        return new input_htmleditor();
    }

    /**
     * Function to_string
     *
     * @return mixed|string
     */
    public function to_string() {
        global $PAGE;

        $return = parent::to_string();

        $config = $this->tyni_editor_config();

        $PAGE->requires->js_amd_inline("
            M.util.js_pending( 'editor_tiny/editor' );
            require( [ 'editor_tiny/editor' ], ( tiny ) => {
                tiny.setupForElementId( {
                    elementId : '{$this->inputid}',
                    options   : {$config},
                } );
                M.util.js_complete( 'editor_tiny/editor' );
            } );");

        return $return;
    }

    /**
     * Use this editor for given element.
     *
     * @return string
     * @throws \dml_exception
     */
    private function tyni_editor_config() {
        global $PAGE;

        $options = ['noclean' => true];
        $context = $PAGE->context;
        if (isset($options['context']) && ($options['context'] instanceof \context)) {
            $context = $options['context'];
        }
        $config = (object)[
            'css' => $PAGE->theme->editor_css_url()->out(false),
            'context' => $context->id,
            'filepicker' => [],
            'currentLanguage' => current_language(),
            'branding' => false,
            'language' => [
                'currentlang' => current_language(),
                'installed' => get_string_manager()->get_list_of_translations(true),
                'available' => get_string_manager()->get_list_of_languages(),
            ],

            'placeholderSelectors' => [],
            'plugins' => (new manager())->get_plugin_configuration($context, $options, [], null),
            'nestedmenu' => true,
        ];

        if (defined('BEHAT_SITE_RUNNING') && BEHAT_SITE_RUNNING) {
            $config->placeholderSelectors = ['.behat-tinymce-placeholder'];
        }

        $config = convert_to_array($config);
        return json_encode($config);
    }
}
