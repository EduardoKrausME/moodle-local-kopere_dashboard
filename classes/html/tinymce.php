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

/**
 * Class tinymce
 *
 * @package local_kopere_dashboard\html
 */
class tinymce {
    /**
     * @var bool
     */
    private static $issend = false;

    /**
     * @return string
     */
    public static function register() {
        global $CFG;

        if (self::$issend) {
            return '';
        }

        self::$issend = true;

        return "<script src='{$CFG->wwwroot}/local/kopere_dashboard/vendor/tinymce/tinymce/tinymce.min.js'
                        type='text/javascript'></script>";
    }

    /**
     * @param string $seletor
     * @param  int $id
     *
     * @return string
     */
    public static function create_input_editor($seletor = '.tinymce_editor_box', $id = 0) {
        global $CFG;
        $filemanager = "{$CFG->wwwroot}/local/kopere_dashboard/vendor/responsivefilemanager/";

        $externalplugins = '';
        if (file_exists("{$filemanager}dialog.php")) {
            $externalplugins = "
                external_filemanager_path : '{$filemanager}',
                filemanager_title         : '" . get_string_kopere('filemanager_title') . "',
                external_plugins          : {
                    'filemanager' : '{$filemanager}plugin.min.js'
                },";
        }

        $returnhtml = self::register();

        $returnhtml
            .= "<script type='text/javascript'>
            tinymce.init ( {
                selector : '{$seletor}',
                plugins  : [
                    'advlist anchor autolink lists link image charmap ',
                    'visualblocks code fullscreen ',
                    'media table contextmenu autosave paste'
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
                        title : '" . get_string_kopere('blocks') . "', items : [
                            { title : '" . get_string_kopere('blocks_paragraph') . "',  block : 'p' },
                            { title : 'Blockquote', block : 'blockquote' },
                            { title : 'Div',        block : 'div' }
                        ]
                    },
                    {
                        title : '" . get_string_kopere('image_alignment') . "', items : [
                            {
                                title    : '" . get_string_kopere('image_alignment_left') . "',
                                selector : 'img',
                                styles   : { 'float' : 'left', 'margin' : '0 10px 0 10px' }
                            },
                            {
                                title    : '" . get_string_kopere('image_alignment_right') . "',
                                selector : 'img',
                                styles   : { 'float' : 'right', 'margin' : '0 10px 0 10px' }
                            }
                        ]
                    },
                    {
                        title : '" . get_string_kopere('colors') . "', items : [
                            { title : '" . get_string_kopere('color_red') . "', inline : 'span', styles :
                                { color : '#D81B60' } },
                            { title : '" . get_string_kopere('color_red') . "', inline : 'span', styles :
                                { color : '#E53935' } },
                            { title : '" . get_string_kopere('color_red') . "', inline : 'span', styles :
                                { color : '#F4511E' } },

                            { title : '" . get_string_kopere('color_purple') . "', inline : 'span', styles :
                                { color : '#9C27B0' } },
                            { title : '" . get_string_kopere('color_brown') . "', inline : 'span', styles :
                                { color : '#5d4037' } },

                            { title : '" . get_string_kopere('color_blue') . "', inline : 'span', styles :
                                { color : '#3F51B5' } },
                            { title : '" . get_string_kopere('color_blue') . "', inline : 'span', styles :
                                { color : '#1565C0' } },
                            { title : '" . get_string_kopere('color_blue') . "', inline : 'span', styles :
                                { color : '#0288D1' } },
                            { title : '" . get_string_kopere('color_blue') . "', inline : 'span', styles :
                                { color : '#00ACC1' } },

                            { title : '" . get_string_kopere('color_green') . "', inline : 'span', styles :
                                { color : '#009688' } },
                            { title : '" . get_string_kopere('color_green') . "', inline : 'span', styles :
                                { color : '#43A047' } },
                            { title : '" . get_string_kopere('color_green') . "', inline : 'span', styles :
                                { color : '#7CB342' } },

                            { title : '" . get_string_kopere('color_yellow') . "', inline : 'span', styles :
                                { color : '#AFB42B' } },
                            { title : '" . get_string_kopere('color_yellow') . "', inline : 'span', styles :
                                { color : '#FDD835' } },

                            { title : '" . get_string_kopere('color_orange') . "', inline : 'span', styles :
                                { color : '#FDD835' } },
                            { title : '" . get_string_kopere('color_orange') . "', inline : 'span', styles :
                                { color : '#FFA000' } },
                            { title : '" . get_string_kopere('color_orange') . "', inline : 'span', styles :
                                { color : '#F57C00' } },

                            { title : '" . get_string_kopere('color_grey') . "', inline : 'span', styles :
                                { color : '#757575' } },
                            { title : '" . get_string_kopere('color_grey') . "', inline : 'span', styles :
                                { color : '#607D8B' } }
                        ]
                    },
                    {
                        title : '" . get_string_kopere('background') . "', items : [
                            { title : '" . get_string_kopere('color_red') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#D81B60' } },
                            { title : '" . get_string_kopere('color_red') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#E53935' } },
                            { title : '" . get_string_kopere('color_red') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#F4511E' } },

                            { title : '" . get_string_kopere('color_purple') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#9C27B0' } },
                            { title : '" . get_string_kopere('color_brown') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#5d4037' } },

                            { title : '" . get_string_kopere('color_blue') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#3F51B5' } },
                            { title : '" . get_string_kopere('color_blue') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#1565C0' } },
                            { title : '" . get_string_kopere('color_blue') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#0288D1' } },
                            { title : '" . get_string_kopere('color_blue') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#00ACC1' } },

                            { title : '" . get_string_kopere('color_green') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#009688' } },
                            { title : '" . get_string_kopere('color_green') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#43A047' } },
                            { title : '" . get_string_kopere('color_green') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#7CB342' } },

                            { title : '" . get_string_kopere('color_yellow') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#AFB42B' } },
                            { title : '" . get_string_kopere('color_yellow') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#FDD835' } },

                            { title : '" . get_string_kopere('color_orange') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#FDD835' } },
                            { title : '" . get_string_kopere('color_orange') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#FFA000' } },
                            { title : '" . get_string_kopere('color_orange') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#F57C00' } },

                            { title : '" . get_string_kopere('color_grey') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#757575' } },
                            { title : '" . get_string_kopere('color_grey') . "', inline : 'span', styles :
                                { color: '#FFFFFF', 'background-color' : '#607D8B' } }
                        ]
                    }
                ],

                toolbar : 'link anchor image | ' +
                        'styleselect | formatselect | fontselect | fontsizeselect | ' +
                        'alignleft aligncenter alignright alignjustify | ' +
                        'bullist numlist outdent indent | fullscreen',

                {$externalplugins}

                auto_focus           : 'elm1',
                relative_urls        : false,
                remove_script_host   : false,
                language             : 'pt_BR',
                folderSaveFile       : '{$id}'
            } );
        </script>";

        return $returnhtml;
    }
}
