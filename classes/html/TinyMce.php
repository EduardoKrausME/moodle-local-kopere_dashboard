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

class TinyMce {
    private static $_isSend = false;

    public static function register() {
        global $CFG;

        if (self::$_isSend) {
            return '';
        }

        self::$_isSend = true;

        return '<script src="' . $CFG->wwwroot . '/local/kopere_dashboard/assets/tinymce/tinymce.min.js" type="text/javascript"></script>';
    }

    /**
     * @param string $seletor
     * @param  int $id
     *
     * @return string
     */
    public static function createInputEditor($seletor = '.tinymce_editor_box', $id = 0) {
        global $CFG;
        $path = $CFG->wwwroot . '/local/kopere_dashboard/assets/tinymce/';
        $returnHtml = self::register();

        $returnHtml
            .= "<script type=\"text/javascript\">
            tinymce.init ( {
                selector : '{$seletor}',
                plugins  : [
                    'advlist autolink lists link image charmap ',
                    'searchreplace visualblocks code fullscreen responsivefilemanager',
                    'media table contextmenu youtube autosave paste anchor'
                ],

                style_formats : [
                    {
                        title : 'Inline', items : [
                            { title : 'Code',          icon : 'code',          format : 'code' },
                            { title : 'Underline',     icon : 'underline',     format : 'underline' },
                            { title : 'Strikethrough', icon : 'strikethrough', format : 'strikethrough' },
                            { title : 'Superscript',   icon : 'superscript',   format : 'superscript' },
                            { title : 'Subscript',     icon : 'subscript',     format : 'subscript' }
                        ]
                    },
                    {
                        title : '" . get_string_kopere('blocks') . ", items : [
                            { title : '" . get_string_kopere('blocks_paragraph') . "',  block : 'p' },
                            { title : 'Blockquote', block : 'blockquote' },
                            { title : 'Div',        block : 'div' }
                        ]
                    },
                    {
                        title : '" . get_string_kopere('image_alignment') . ", items : [
                            {
                                title    : '" . get_string_kopere('image_alignment_left') . ",
                                selector : 'img',
                                styles   : { 'float' : 'left', 'margin' : '0 10px 0 10px' }
                            },
                            {
                                title    : '" . get_string_kopere('image_alignment_right') . ",
                                selector : 'img',
                                styles   : { 'float' : 'right', 'margin' : '0 10px 0 10px' }
                            }
                        ]
                    },
                    {
                        title : '" . get_string_kopere('colors') . ", items : [
                            { title : '" . get_string_kopere('color_red') . ", inline : 'span', styles : { color : '#D81B60' } },
                            { title : '" . get_string_kopere('color_red') . ", inline : 'span', styles : { color : '#E53935' } },
                            { title : '" . get_string_kopere('color_red') . ", inline : 'span', styles : { color : '#F4511E' } },

                            { title : '" . get_string_kopere('color_purple') . ", inline : 'span', styles : { color : '#9C27B0' } },
                            { title : '" . get_string_kopere('color_brown') . ", inline : 'span', styles : { color : '#5d4037' } },

                            { title : '" . get_string_kopere('color_blue') . ", inline : 'span', styles : { color : '#3F51B5' } },
                            { title : '" . get_string_kopere('color_blue') . ", inline : 'span', styles : { color : '#1565C0' } },
                            { title : '" . get_string_kopere('color_blue') . ", inline : 'span', styles : { color : '#0288D1' } },
                            { title : '" . get_string_kopere('color_blue') . ", inline : 'span', styles : { color : '#00ACC1' } },

                            { title : '" . get_string_kopere('color_green') . ", inline : 'span', styles : { color : '#009688' } },
                            { title : '" . get_string_kopere('color_green') . ", inline : 'span', styles : { color : '#43A047' } },
                            { title : '" . get_string_kopere('color_green') . ", inline : 'span', styles : { color : '#7CB342' } },

                            { title : '" . get_string_kopere('color_yellow') . ", inline : 'span', styles : { color : '#AFB42B' } },
                            { title : '" . get_string_kopere('color_yellow') . ", inline : 'span', styles : { color : '#FDD835' } },

                            { title : '" . get_string_kopere('color_orange') . ", inline : 'span', styles : { color : '#FDD835' } },
                            { title : '" . get_string_kopere('color_orange') . ", inline : 'span', styles : { color : '#FFA000' } },
                            { title : '" . get_string_kopere('color_orange') . ", inline : 'span', styles : { color : '#F57C00' } },

                            { title : '" . get_string_kopere('color_grey') . ", inline : 'span', styles : { color : '#757575' } },
                            { title : '" . get_string_kopere('color_grey') . ", inline : 'span', styles : { color : '#607D8B' } }
                        ]
                    },
                    {
                        title : '" . get_string_kopere('background') . ", items : [
                            { title : '" . get_string_kopere('color_red') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#D81B60' } },
                            { title : '" . get_string_kopere('color_red') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#E53935' } },
                            { title : '" . get_string_kopere('color_red') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#F4511E' } },

                            { title : '" . get_string_kopere('color_purple') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#9C27B0' } },
                            { title : '" . get_string_kopere('color_brown') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#5d4037' } },

                            { title : '" . get_string_kopere('color_blue') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#3F51B5' } },
                            { title : '" . get_string_kopere('color_blue') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#1565C0' } },
                            { title : '" . get_string_kopere('color_blue') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#0288D1' } },
                            { title : '" . get_string_kopere('color_blue') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#00ACC1' } },

                            { title : '" . get_string_kopere('color_green') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#009688' } },
                            { title : '" . get_string_kopere('color_green') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#43A047' } },
                            { title : '" . get_string_kopere('color_green') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#7CB342' } },

                            { title : '" . get_string_kopere('color_yellow') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#AFB42B' } },
                            { title : '" . get_string_kopere('color_yellow') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#FDD835' } },

                            { title : '" . get_string_kopere('color_orange') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#FDD835' } },
                            { title : '" . get_string_kopere('color_orange') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#FFA000' } },
                            { title : '" . get_string_kopere('color_orange') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#F57C00' } },

                            { title : '" . get_string_kopere('color_grey') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#757575' } },
                            { title : '" . get_string_kopere('color_grey') . ", inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#607D8B' } }
                        ]
                    }
                ],

                toolbar : 'link anchor image | ' +
                        'styleselect | formatselect | fontselect | fontsizeselect | ' +
                        'alignleft aligncenter alignright alignjustify | ' +
                        'bullist numlist outdent indent | ' +
                        'youtube | fullscreen',

                external_plugins : {
                    'filemanager' : '{$path}filemanager/plugin.js'
                },

                external_filemanager_path : '{$path}filemanager/',
                filemanager_title         : '" . get_string_kopere('filemanager_title') . ",

                auto_focus           : 'elm1',
                relative_urls        : false,
                remove_script_host   : false,
                language             : 'pt_BR',
                folderSaveFile       : '{$id}'
            } );
        </script>";

        return $returnHtml;
    }
}