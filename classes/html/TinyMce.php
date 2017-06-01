<?php

namespace local_kopere_dashboard\html;

class TinyMce
{
    private static $_isSend = false;

    public static function register ()
    {
        if ( self::$_isSend )
            return;

        self::$_isSend = true;

        echo '<script src="' . self::getAbsulutePath () . 'tinymce.min.js" type="text/javascript"></script>';
    }

    /**
     * @param string $seletor
     * @param  int   $id
     */
    public static function createInputEditor ( $seletor = '.tinymce_editor_box', $id = 0 )
    {
        self::register ();
        ?>
        <script type="text/javascript">
            tinymce.init ( {
                selector : '<?php echo $seletor ?>',
                plugins  : [
                    'advlist autolink lists link image charmap ',
                    'searchreplace visualblocks code fullscreen responsivefilemanager',
                    'media table contextmenu youtube autosave paste nanospell anchor'
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
                        title : 'Blocks', items : [
                            { title : 'Blockquote', block : 'blockquote' },
                            { title : 'Div',        block : 'div' }
                        ]
                    },
                    {
                        title : 'Alinhamento de imagem', items : [
                            {
                                title    : 'Image Left',
                                selector : 'img',
                                styles   : { 'float' : 'left', 'margin' : '0 10px 0 10px' }
                            },
                            {
                                title    : 'Image Right',
                                selector : 'img',
                                styles   : { 'float' : 'right', 'margin' : '0 10px 0 10px' }
                            }
                        ]
                    },
                    {
                        title : 'Cores', items : [
                            { title : 'Vermelho', inline : 'span', styles : { color : '#D81B60' } },
                            { title : 'Vermelho', inline : 'span', styles : { color : '#E53935' } },
                            { title : 'Vermelho', inline : 'span', styles : { color : '#F4511E' } },

                            { title : 'Roxo    ', inline : 'span', styles : { color : '#9C27B0' } },
                            { title : 'Marron  ', inline : 'span', styles : { color : '#5d4037' } },

                            { title : 'Azul    ', inline : 'span', styles : { color : '#3F51B5' } },
                            { title : 'Azul    ', inline : 'span', styles : { color : '#1565C0' } },
                            { title : 'Azul    ', inline : 'span', styles : { color : '#0288D1' } },
                            { title : 'Azul    ', inline : 'span', styles : { color : '#00ACC1' } },

                            { title : 'Verde   ', inline : 'span', styles : { color : '#009688' } },
                            { title : 'Verde   ', inline : 'span', styles : { color : '#43A047' } },
                            { title : 'Verde   ', inline : 'span', styles : { color : '#7CB342' } },

                            { title : 'Amarelo ', inline : 'span', styles : { color : '#AFB42B' } },
                            { title : 'Amarelo ', inline : 'span', styles : { color : '#FDD835' } },

                            { title : 'Laranja ', inline : 'span', styles : { color : '#FDD835' } },
                            { title : 'Laranja ', inline : 'span', styles : { color : '#FFA000' } },
                            { title : 'Laranja ', inline : 'span', styles : { color : '#F57C00' } },

                            { title : 'Cinza   ', inline : 'span', styles : { color : '#757575' } },
                            { title : 'Cinza   ', inline : 'span', styles : { color : '#607D8B' } }
                        ]
                    },
                    {
                        title : 'Cor de Fundo', items : [
                            { title : 'Vermelho', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#D81B60' } },
                            { title : 'Vermelho', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#E53935' } },
                            { title : 'Vermelho', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#F4511E' } },

                            { title : 'Roxo    ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#9C27B0' } },
                            { title : 'Marron  ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#5d4037' } },

                            { title : 'Azul    ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#3F51B5' } },
                            { title : 'Azul    ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#1565C0' } },
                            { title : 'Azul    ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#0288D1' } },
                            { title : 'Azul    ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#00ACC1' } },

                            { title : 'Verde   ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#009688' } },
                            { title : 'Verde   ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#43A047' } },
                            { title : 'Verde   ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#7CB342' } },

                            { title : 'Amarelo ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#AFB42B' } },
                            { title : 'Amarelo ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#FDD835' } },

                            { title : 'Laranja ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#FDD835' } },
                            { title : 'Laranja ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#FFA000' } },
                            { title : 'Laranja ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#F57C00' } },

                            { title : 'Cinza   ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#757575' } },
                            { title : 'Cinza   ', inline : 'span', styles : { color: '#FFFFFF', 'background-color' : '#607D8B' } }
                        ]
                    }
                ],

                toolbar : 'link anchor image | ' +
                        'styleselect | formatselect | fontselect | fontsizeselect | ' +
                        'alignleft aligncenter alignright alignjustify | ' +
                        'bullist numlist outdent indent | ' +
                        'youtube | ' +
                        'fullscreen nanospell',

                external_plugins : {
                    'filemanager' : '<?php echo self::getAbsulutePath () ?>filemanager/plugin.js',
                    'nanospell'   : '<?php echo self::getAbsulutePath () ?>nanospell/plugin.js'
                },

                external_filemanager_path : '<?php echo self::getAbsulutePath () ?>filemanager/',
                filemanager_title         : 'Gerenciador de Arquivos',

                nanospell_server     : 'php',
                nanospell_dictionary : 'pt_br',
                auto_focus           : 'elm1',
                relative_urls        : false,
                remove_script_host   : false,
                language             : 'pt_BR',
                folderSaveFile       : '<?php echo $id ?>'
            } );
        </script>
        <?php
    }

    private static function getAbsulutePath ()
    {
        global $CFG;

        return $CFG->wwwroot . '/local/kopere_dashboard/assets/tinymce/';
    }

}