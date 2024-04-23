<?php

require_once('../../../config.php');
require_once('../lib.php');
require_once('../autoload.php');

$page = required_param('page', PARAM_TEXT);
$id = required_param('id', PARAM_TEXT);
$link = optional_param('link', '', PARAM_TEXT);
require_login();
require_capability('moodle/site:config', context_system::instance());

$htmldata = optional_param('htmldata', false, PARAM_RAW);
$cssdata = optional_param('cssdata', false, PARAM_RAW);
if ($htmldata) {
    $html = "{$htmldata}\n<style>{$cssdata}</style>";
    if ($page == "webpages") {
        $webpages = $DB->get_record("kopere_dashboard_webpages", ["id" => $id]);
        $webpages->text = $html;
        $DB->update_record("kopere_dashboard_webpages", $webpages);

        redirect("{$CFG->wwwroot}/local/kopere_dashboard/open-internal.php?classname=webpages&method=page_details&id={$id}");
    } else if ($page == "notification") {
        $events = $DB->get_record("kopere_dashboard_events", ["id" => $id]);
        $events->message = $html;
        $DB->update_record("kopere_dashboard_events", $events);
        
        redirect("{$CFG->wwwroot}/local/kopere_dashboard/open-internal.php?classname=notifications&method=add_segunda_etapa&id={$id}");
    } else if ($page == 'aceite') {
        set_config('formulario_pedir_aceite', $html, 'local_kopere_dashboard');

        redirect("{$CFG->wwwroot}/local/kopere_dashboard/open-internal.php?classname=pay-setting&method=settings");
    } else if ($page == 'meiodeposito') {
        set_config('kopere_pay-meiodeposito-conta', $html, 'local_kopere_dashboard');

        redirect("{$CFG->wwwroot}/local/kopere_dashboard/open-internal.php?classname=pay-meio_pagamento&method=edit&meio=MeioDeposito");
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Page</title>
    <link rel="stylesheet" href="styles/toastr.css">
    <link rel="stylesheet" href="styles/grapes.css">
    <link rel="stylesheet" href="styles/grapesjs-preset-webpage.css">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/tooltip.css">
    <link rel="stylesheet" href="styles/demos.css">
    <link rel="stylesheet" href="styles/grapick.css">

    <script src="js/jquery.js"></script>
    <script src="js/toastr.js"></script>
    <script src="js/grapes.js"></script>

    <script src="js/plugins/grapesjs-preset-webpage.js"></script>
    <script src="js/plugins/grapesjs-blocks-basic.js"></script>
    <script src="js/plugins/grapesjs-plugin-forms.js"></script>
    <script src="js/plugins/grapesjs-component-countdown.js"></script>
    <script src="js/plugins/grapesjs-tabs.js"></script>
    <script src="js/plugins/grapesjs-custom-code.js"></script>
    <script src="js/plugins/grapesjs-touch.js"></script>
    <script src="js/plugins/grapesjs-parser-postcss.js"></script>
    <script src="js/plugins/grapesjs-tooltip.js"></script>
    <script src="js/plugins/grapesjs-tui-image-editor.js"></script>
    <script src="js/plugins/grapesjs-typed.js"></script>
    <script src="js/plugins/grapesjs-style-bg.js"></script>
    <?php
    require_once("{$CFG->dirroot}/local/kopere_dashboard/classes/fonts/font_util.php");
    $fontList = \local_kopere_dashboard\fonts\font_util::list_fonts();
    echo "<style>{$fontList['css']}</style>";
    ?>
</head>
<body>

<div id="gjs" style="height:0; overflow:hidden">
    <?php
    if (file_exists(__DIR__ . "/default-{$page}.html")) {
        $pagePreview = "";
        if ($page == "webpages") {
            $text = $DB->get_field("kopere_dashboard_webpages", "text", ["id" => $id]);
            $pagePreview = "{$CFG->wwwroot}/local/kopere_dashboard/";
        } else if ($page == "notification") {
            $text = $DB->get_field("kopere_dashboard_events", "message", ["id" => $id]);
        } else if ($page == "aceite") {
            $text = get_config('local_kopere_dashboard', 'formulario_pedir_aceite');
            $pagePreview = "{$CFG->wwwroot}/local/kopere_pay/termos.php";
        } else if ($page == "meiodeposito") {
            $text = get_config('local_kopere_dashboard', 'kopere_pay-meiodeposito-conta');
        }

        if (!isset($text[1])) {
            $text = file_get_contents(__DIR__ . "/default-{$page}.html");
        }

        $text = str_replace("{wwwroot}", $CFG->wwwroot, $text);
        $text = str_replace("{shortname}", $SITE->shortname, $text);
        $text = str_replace("{fullname}", $SITE->fullname, $text);
        echo $text;
    } else {
        die("OPS...");
    }
    ?>
</div>

<script type="text/javascript">
    var editor = grapesjs.init({
        'height'          : '100%',
        'container'       : '#gjs',
        'fromElement'     : true,
        'showOffsets'     : true,
        'storageManager'  : false,
        'assetManager'    : {
            'embedAsBase64' : true,
            'assets'        : [],
        },
        'selectorManager' : {componentFirst : true},
        'styleManager'    : {
            'sectors' : [
                {
                    'name'       : '<?php echo get_string_kopere("grapsjs-general") ?>',
                    'properties' : [
                        'display',
                        {extend : 'position', 'type' : 'select'},
                        'top',
                        'right',
                        'left',
                        'bottom',
                    ],
                },
                {
                    'name'       : '<?php echo get_string_kopere("grapsjs-dimensions") ?>',
                    'open'       : false,
                    'properties' : [
                        'width',
                        'height',
                        'max-width',
                        'min-width',
                        'max-height',
                        'min-height',
                        'margin',
                        'padding'
                    ],
                },
                {
                    'name'       : '<?php echo get_string_kopere("grapsjs-tipografia") ?>',
                    'open'       : false,
                    'properties' : [
                        {
                            'property' : 'font-family',
                            'type'     : 'select',
                            'name'     : '<?php echo get_string_kopere("grapsjs-stylemanager-properties-font-family") ?>',
                            'options'  : [
                                {
                                    'id' : "Arial", 'label' : 'Arial', 'value' : 'Arial, Helvetica, sans-serif'
                                },
                                <?php echo $fontList['editor-list'] ?>
                            ]
                        },
                        'font-size',
                        'font-weight',
                        'letter-spacing',
                        'color',
                        'line-height',
                        {
                            'extend'  : 'text-align',
                            'options' : [
                                {
                                    'id'        : 'left',
                                    'label'     : '<?php echo get_string_kopere("grapsjs-stylemanager-properties-left") ?>',
                                    'className' : 'fa fa-align-left'
                                },
                                {
                                    'id'        : 'center',
                                    'label'     : '<?php echo get_string_kopere("grapsjs-stylemanager-properties-center") ?>',
                                    'className' : 'fa fa-align-center'
                                },
                                {
                                    'id'        : 'right',
                                    'label'     : '<?php echo get_string_kopere("grapsjs-stylemanager-properties-right") ?>',
                                    'className' : 'fa fa-align-right'
                                },
                                {
                                    'id'        : 'justify',
                                    'label'     : '<?php echo get_string_kopere("grapsjs-stylemanager-properties-justify") ?>',
                                    'className' : 'fa fa-align-justify'
                                }
                            ],
                        },
                        {
                            'property' : 'text-decoration',
                            'type'     : 'radio',
                            'default'  : 'none',
                            'options'  : [
                                {
                                    'id'        : 'none',
                                    'label'     : '<?php echo get_string_kopere("grapsjs-stylemanager-properties-none") ?>',
                                    'className' : 'fa fa-times'
                                },
                                {
                                    'id'        : 'underline',
                                    'label'     : '<?php echo get_string_kopere("grapsjs-stylemanager-properties-underline") ?>',
                                    'className' : 'fa fa-underline'
                                },
                                {
                                    'id'        : 'line-through',
                                    'label'     : '<?php echo get_string_kopere("grapsjs-stylemanager-properties-line-through") ?>',
                                    'className' : 'fa fa-strikethrough'
                                }
                            ],
                        },
                        'text-shadow',
                        {
                            'property' : 'text-transform',
                            'type'     : 'radio',
                            'default'  : 'none',
                            'options'  : [
                                {
                                    'id'    : 'none',
                                    'label' : 'x'
                                },
                                {
                                    'id'    : 'capitalize',
                                    'label' : 'Tt'
                                },
                                {
                                    'id'    : 'lowercase',
                                    'label' : 'tt'
                                },
                                {
                                    'id'    : 'uppercase',
                                    'label' : 'TT'
                                }
                            ]
                        }
                    ],
                },
                {
                    'name'       : '<?php echo get_string_kopere("grapsjs-stylemanager-properties-background") ?>',
                    'open'       : false,
                    'properties' : [
                        'background',
                    ],
                },
                {
                    'name'       : '<?php echo get_string_kopere("grapsjs-decoration") ?>',
                    'open'       : false,
                    'properties' : [
                        'opacity',
                        'border-radius',
                        'border',
                    ],
                },
            ],
        },
        'plugins'         : [
            'grapesjs-blocks-basic',
            'grapesjs-plugin-forms',
            'grapesjs-component-countdown',
            'grapesjs-tabs',
            'grapesjs-custom-code',
            'grapesjs-touch',
            'grapesjs-parser-postcss',
            'grapesjs-tooltip',
            'grapesjs-tui-image-editor',
            'grapesjs-typed',
            'grapesjs-style-bg',
            'grapesjs-preset-webpage',
        ],
        'pluginsOpts'     : {
            'grapesjs-blocks-basic'     : {
                'flexGrid' : false,
            },
            'grapesjs-tui-image-editor' : {
                'script' : [
                    './js/tui/tui-code-snippet.js',
                    './js/tui/tui-color-picker.js',
                    './js/tui/tui-image-editor.js'
                ],
                'style'  : [
                    './styles/tui/tui-color-picker.css',
                    './styles/tui/tui-image-editor.css',
                ],
            },
            'grapesjs-tabs'             : {
                'tabsBlock' : {'category' : '<?php echo get_string_kopere("grapsjs-stylemanager-sectors-extra") ?>'}
            },
            'grapesjs-typed'            : {
                'block' : {
                    'category' : '<?php echo get_string_kopere("grapsjs-stylemanager-sectors-extra") ?>',
                    'content'  : {
                        'type'       : 'typed',
                        'type-speed' : 40,
                        'strings'    : [
                            'Text row one',
                            'Text row two',
                            'Text row three',
                        ],
                    }
                }
            },
            'grapesjs-preset-webpage'   : {
                'modalImportTitle'   : '<?php echo get_string_kopere("grapsjs-edit_code") ?>',
                'modalImportLabel'   : '<div style="margin-bottom: 10px; font-size: 13px;"><?php echo get_string_kopere("grapsjs-edit_code_paste_here_html") ?></div>',
                'modalImportContent' : function(editor) {
                    var html = editor.getHtml();
                    html = html.split(/<body.*?>/).join('');
                    html = html.split('</body>').join('');

                    var css = "\n" + editor.getCss();
                    css = css.split(/\*.*?}/s).join('');
                    css = css.split(/\nbody.*?}/s).join('');
                    css = css.split(/:root.*?}/s).join('');
                    css = css.split(/\.row.*?}/s).join('');
                    css = css.split(/\[data-gjs-type="?wrapper"?]\s?>\s?#/).join('#');
                    css = css.split(/\[data-gjs-type="?wrapper"?]\s?>\s/).join('');

                    return `${html}\n<style>\n${css}</style>`;
                },
            },
            'grapesjs-blocks-table'     : {
                'containerId' : '#gjs'
            },
        },
        'i18n'            : {
            'locale'         : 'en',
            'detectLocale'   : false,
            'localeFallback' : 'en',
            'messages'       : {
                'en' : {
                    'assetManager'    : {
                        'addButton'   : "<?php echo get_string_kopere("grapsjs-assetmanager-addbutton") ?>",
                        'modalTitle'  : "<?php echo get_string_kopere("grapsjs-assetmanager-modaltitle") ?>",
                        'uploadTitle' : "<?php echo get_string_kopere("grapsjs-assetmanager-uploadtitle") ?>"
                    },
                    'domComponents'   : {
                        'names' : {
                            ""        : "<?php echo get_string_kopere("grapsjs-domcomponents-names-") ?>",
                            'wrapper' : "<?php echo get_string_kopere("grapsjs-domcomponents-names-wrapper") ?>",
                            'text'    : "<?php echo get_string_kopere("grapsjs-domcomponents-names-text") ?>",
                            'comment' : "<?php echo get_string_kopere("grapsjs-domcomponents-names-comment") ?>",
                            'image'   : "<?php echo get_string_kopere("grapsjs-domcomponents-names-image") ?>",
                            'video'   : "<?php echo get_string_kopere("grapsjs-domcomponents-names-video") ?>",
                            'label'   : "<?php echo get_string_kopere("grapsjs-domcomponents-names-label") ?>",
                            'link'    : "<?php echo get_string_kopere("grapsjs-domcomponents-names-link") ?>",
                            'map'     : "<?php echo get_string_kopere("grapsjs-domcomponents-names-map") ?>",
                            'tfoot'   : "<?php echo get_string_kopere("grapsjs-domcomponents-names-tfoot") ?>",
                            'tbody'   : "<?php echo get_string_kopere("grapsjs-domcomponents-names-tbody") ?>",
                            'thead'   : "<?php echo get_string_kopere("grapsjs-domcomponents-names-thead") ?>",
                            'table'   : "<?php echo get_string_kopere("grapsjs-domcomponents-names-table") ?>",
                            'row'     : "<?php echo get_string_kopere("grapsjs-domcomponents-names-row") ?>",
                            'cell'    : "<?php echo get_string_kopere("grapsjs-domcomponents-names-cell") ?>",
                            'section' : "<?php echo get_string_kopere("grapsjs-domcomponents-names-section") ?>",
                            'body'    : "<?php echo get_string_kopere("grapsjs-domcomponents-names-wrapper") ?>"
                        }
                    },
                    'deviceManager'   : {
                        'device'  : "<?php echo get_string_kopere("grapsjs-devicemanager-device") ?>",
                        'devices' : {
                            'desktop'         : "<?php echo get_string_kopere("grapsjs-devicemanager-devices-desktop") ?>",
                            'tablet'          : "<?php echo get_string_kopere("grapsjs-devicemanager-devices-tablet") ?>",
                            'mobileLandscape' : "<?php echo get_string_kopere("grapsjs-devicemanager-devices-mobilelandscape") ?>",
                            'mobilePortrait'  : "<?php echo get_string_kopere("grapsjs-devicemanager-devices-mobileportrait") ?>"
                        }
                    },
                    'panels'          : {
                        'buttons' : {
                            'titles' : {
                                'preview'       : "<?php echo get_string_kopere("grapsjs-panels-buttons-titles-preview") ?>",
                                'fullscreen'    : "<?php echo get_string_kopere("grapsjs-panels-buttons-titles-fullscreen") ?>",
                                "sw-visibility" : "<?php echo get_string_kopere("grapsjs-panels-buttons-titles-sw-visibility") ?>",
                                "open-sm"       : "<?php echo get_string_kopere("grapsjs-panels-buttons-titles-open-sm") ?>",
                                "open-tm"       : "<?php echo get_string_kopere("grapsjs-panels-buttons-titles-open-tm") ?>",
                                "open-layers"   : "<?php echo get_string_kopere("grapsjs-panels-buttons-titles-open-layers") ?>",
                                "open-blocks"   : "<?php echo get_string_kopere("grapsjs-panels-buttons-titles-open-blocks") ?>"
                            }
                        }
                    },
                    'selectorManager' : {
                        'label'      : "<?php echo get_string_kopere("grapsjs-selectormanager-label") ?>",
                        'selected'   : "<?php echo get_string_kopere("grapsjs-selectormanager-selected") ?>",
                        'emptyState' : "<?php echo get_string_kopere("grapsjs-selectormanager-emptystate") ?>",
                        'states'     : {
                            'hover'           : "<?php echo get_string_kopere("grapsjs-selectormanager-states-hover") ?>",
                            'active'          : "<?php echo get_string_kopere("grapsjs-selectormanager-states-active") ?>",
                            "nth-of-type(2n)" : "<?php echo get_string_kopere("grapsjs-selectormanager-states-nth-of-type-2n") ?>"
                        }
                    },
                    'styleManager'    : {
                        'empty'      : "<?php echo get_string_kopere("grapsjs-stylemanager-empty") ?>",
                        'layer'      : "<?php echo get_string_kopere("grapsjs-stylemanager-layer") ?>",
                        'fileButton' : "<?php echo get_string_kopere("grapsjs-stylemanager-filebutton") ?>",
                        'sectors'    : {
                            'general'     : "<?php echo get_string_kopere("grapsjs-stylemanager-sectors-general") ?>",
                            'layout'      : "<?php echo get_string_kopere("grapsjs-stylemanager-sectors-layout") ?>",
                            'typography'  : "<?php echo get_string_kopere("grapsjs-stylemanager-sectors-typography") ?>",
                            'decorations' : "<?php echo get_string_kopere("grapsjs-stylemanager-sectors-decorations") ?>",
                            'extra'       : "<?php echo get_string_kopere("grapsjs-stylemanager-sectors-extra") ?>",
                            'flex'        : "<?php echo get_string_kopere("grapsjs-stylemanager-sectors-flex") ?>",
                            'dimension'   : "<?php echo get_string_kopere("grapsjs-stylemanager-sectors-dimension") ?>"
                        },
                        'properties' : {
                            'float'                      : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-float") ?>",
                            'display'                    : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-display") ?>",
                            'position'                   : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-position") ?>",
                            'top'                        : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-top") ?>",
                            'right'                      : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-right") ?>",
                            'left'                       : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-left") ?>",
                            'bottom'                     : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-bottom") ?>",
                            'width'                      : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-width") ?>",
                            'height'                     : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-height") ?>",
                            "max-width"                  : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-max-width") ?>",
                            "max-height"                 : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-max-height") ?>",
                            'margin'                     : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-margin") ?>",
                            "margin-top"                 : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-margin-top") ?>",
                            "margin-right"               : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-margin-right") ?>",
                            "margin-left"                : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-margin-left") ?>",
                            "margin-bottom"              : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-margin-bottom") ?>",
                            'padding'                    : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-padding") ?>",
                            "padding-top"                : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-padding-top") ?>",
                            "padding-left"               : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-padding-left") ?>",
                            "padding-right"              : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-padding-right") ?>",
                            "padding-bottom"             : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-padding-bottom") ?>",
                            "font-family"                : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-font-family") ?>",
                            "font-size"                  : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-font-size") ?>",
                            "font-weight"                : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-font-weight") ?>",
                            "letter-spacing"             : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-letter-spacing") ?>",
                            'color'                      : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-color") ?>",
                            "line-height"                : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-line-height") ?>",
                            "text-align"                 : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-text-align") ?>",
                            "text-shadow"                : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-text-shadow") ?>",
                            "text-shadow-h"              : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-text-shadow-h") ?>",
                            "text-shadow-v"              : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-text-shadow-v") ?>",
                            "text-shadow-blur"           : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-text-shadow-blur") ?>",
                            "text-shadow-color"          : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-text-shadow-color") ?>",
                            "border-top-left"            : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-top-left") ?>",
                            "border-top-right"           : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-top-right") ?>",
                            "border-bottom-left"         : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-bottom-left") ?>",
                            "border-bottom-right"        : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-bottom-right") ?>",
                            "border-radius-top-left"     : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-radius-top-left") ?>",
                            "border-radius-top-right"    : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-radius-top-right") ?>",
                            "border-radius-bottom-left"  : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-radius-bottom-left") ?>",
                            "border-radius-bottom-right" : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-radius-bottom-right") ?>",
                            "border-radius"              : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-radius") ?>",
                            'border'                     : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border") ?>",
                            "border-width"               : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-width") ?>",
                            "border-style"               : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-style") ?>",
                            "border-color"               : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-border-color") ?>",
                            "box-shadow"                 : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-box-shadow") ?>",
                            "box-shadow-h"               : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-box-shadow-h") ?>",
                            "box-shadow-v"               : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-box-shadow-v") ?>",
                            "box-shadow-blur"            : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-box-shadow-blur") ?>",
                            "box-shadow-spread"          : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-box-shadow-spread") ?>",
                            "box-shadow-color"           : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-box-shadow-color") ?>",
                            "box-shadow-type"            : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-box-shadow-type") ?>",
                            'background'                 : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-background") ?>",
                            "background-color"           : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-background-color") ?>",
                            "background-image"           : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-background-image") ?>",
                            "background-repeat"          : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-background-repeat") ?>",
                            "background-position"        : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-background-position") ?>",
                            "background-attachment"      : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-background-attachment") ?>",
                            "background-size"            : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-background-size") ?>",
                            'transition'                 : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transition") ?>",
                            "transition-property"        : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transition-property") ?>",
                            "transition-duration"        : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transition-duration") ?>",
                            "transition-timing-function" : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transition-timing-function") ?>",
                            'perspective'                : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-perspective") ?>",
                            'transform'                  : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transform") ?>",
                            "transform-rotate-x"         : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transform-rotate-x") ?>",
                            "transform-rotate-y"         : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transform-rotate-y") ?>",
                            "transform-rotate-z"         : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transform-rotate-z") ?>",
                            "transform-scale-x"          : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transform-scale-x") ?>",
                            "transform-scale-y"          : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transform-scale-y") ?>",
                            "transform-scale-z"          : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-transform-scale-z") ?>",
                            "flex-direction"             : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-flex-direction") ?>",
                            "flex-wrap"                  : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-flex-wrap") ?>",
                            "justify-content"            : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-justify-content") ?>",
                            "align-items"                : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-align-items") ?>",
                            "align-content"              : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-align-content") ?>",
                            'order'                      : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-order") ?>",
                            "flex-basis"                 : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-flex-basis") ?>",
                            "flex-grow"                  : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-flex-grow") ?>",
                            "flex-shrink"                : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-flex-shrink") ?>",
                            "align-self"                 : "<?php echo get_string_kopere("grapsjs-stylemanager-properties-align-self") ?>"
                        }
                    },
                    'traitManager'    : {
                        'empty'  : "<?php echo get_string_kopere("grapsjs-traitmanager-empty") ?>",
                        'label'  : "<?php echo get_string_kopere("grapsjs-traitmanager-label") ?>",
                        'traits' : {
                            'options' : {
                                'target' : {
                                    'false'  : "<?php echo get_string_kopere("grapsjs-traitmanager-traits-options-target-false") ?>",
                                    '_blank' : "<?php echo get_string_kopere("grapsjs-traitmanager-traits-options-target-_blank") ?>"
                                }
                            }
                        }
                    }
                }
            }
        }
    });

    editor.getConfig().showDevices = 0;
    editor.Panels.addPanel({
        'id' : "devices-c"
    }).get("buttons").add([
        {
            'id'        : "block-save",
            'className' : "btn-salvar padding-0",
            'label'     : `<form class="form-save-preview" method="post" target="_top"
                                 style="display:none;margin:0;">
                               <input type="hidden" name="page"     value="<?php echo $page ?>">
                               <input type="hidden" name="link"     value="<?php echo $link ?>">
                               <input type="hidden" name="id"       value="<?php echo $id ?>">
                               <input type="hidden" name="sesskey"  value="<?php echo sesskey() ?>">
                               <input type="hidden" name="htmldata" class="form-htmldata">
                               <input type="hidden" name="cssdata"  class="form-cssdata">
                               <button type="submit" class="btn-salvar gjs-pn-btn gjs-pn-active gjs-four-color">
                                   <i class='fa fa-save'></i>&nbsp;
                                   <?php echo get_string_kopere("grapsjs-page_save") ?>
                              </button>
                           </form>
                           <form class="form-save-preview form-preview" method="post" target="home-preview"
                                 style="display:none;margin:0;"
                                 action="<?php echo $pagePreview ?>">
                               <input type="hidden" name="page"     value="<?php echo $page ?>">
                               <input type="hidden" name="link"     value="<?php echo $link ?>">
                               <input type="hidden" name="id"       value="<?php echo $id ?>">
                               <input type="hidden" name="sesskey"  value="<?php echo sesskey() ?>">
                               <input type="hidden" name="htmldata" class="form-htmldata">
                               <input type="hidden" name="cssdata"  class="form-cssdata">
                               <button type="submit" class="btn-salvar gjs-pn-btn gjs-pn-active gjs-four-color">
                                   <i class='fa fa-eye'></i>&nbsp;
                                   <?php echo get_string_kopere("grapsjs-page_preview") ?>
                              </button>
                           </form>`,
        }
    ]);

    function showButtonUpdate() {
        var html = editor.getHtml();
        html = html.split(/<body.*?>/).join('');
        html = html.split('</body>').join('');

        var css = editor.getCss();
        css = css.split(/\*.*?}/s).join('');
        css = css.split(/body.*?}/s).join('');
        css = css.split(/\[data-gjs-type="?wrapper"?]\s?>\s?#/).join('#');
        css = css.split(/\[data-gjs-type="?wrapper"?]\s?>\s/).join('');

        $(".form-htmldata").val(html);
        $(".form-cssdata").val(css);
        $(".form-save-preview").show(300);

        if ("<?php echo $pagePreview ?>".length < 3) {
            $(".form-preview").hide();
        }
    }

    editor.on('load', showButtonUpdate);
    editor.on('update', showButtonUpdate);

    // Update canvas-clear command
    editor.Commands.add('canvas-clear', function() {
        if (confirm("<?php echo get_string_kopere("grapsjs-confirm_clear") ?>")) {
            editor.runCommand('core:canvas-clear');
            setTimeout(function() {
                localStorage.clear()
            }, 0)
        }
    });

    // Simple warn notifier
    var origWarn = console.warn;
    toastr.options = {
        'closeButton'       : true,
        'preventDuplicates' : true,
        'showDuration'      : 250,
        'hideDuration'      : 150
    };
    console.warn = function(msg) {
        if (msg.indexOf('[undefined]') == -1) {
            toastr.warning(msg);
        }
        origWarn(msg);
    };

    // Add and beautify tooltips
    var options = [
        ['sw-visibility', '<?php echo get_string_kopere("grapsjs-show_border") ?>'],
        ['preview', '<?php echo get_string_kopere("grapsjs-preview") ?>'],
        ['fullscreen', '<?php echo get_string_kopere("grapsjs-fullscreen") ?>'],
        ['undo', '<?php echo get_string_kopere("grapsjs-undo") ?>'],
        ['redo', '<?php echo get_string_kopere("grapsjs-redo") ?>'],
        ['canvas-clear', '<?php echo get_string_kopere("grapsjs-clear") ?>'],
        ['gjs-open-import-webpage', '<?php echo get_string_kopere("grapsjs-edit_code") ?>'],
    ];
    options.forEach(function(item) {
        editor.Panels.getButton('options', item[0]).set('attributes', {title : item[1], 'data-tooltip-pos' : 'bottom'});
    });

    var views = [
        ['open-sm', '<?php echo get_string_kopere("grapsjs-open_sm") ?>'],
        ['open-layers', '<?php echo get_string_kopere("grapsjs-open_layers") ?>'],
        ['open-blocks', '<?php echo get_string_kopere("grapsjs-open_block") ?>'],
    ];
    views.forEach(function(item) {
        editor.Panels.getButton('views', item[0]).set('attributes', {title : item[1], 'data-tooltip-pos' : 'bottom'});
    });
    var titles = document.querySelectorAll('*[title]');

    for (var i = 0; i < titles.length; i++) {
        var el = titles[i];
        var title = el.getAttribute('title');
        title = title ? title.trim() : '';
        if (!title)
            break;
        el.setAttribute('data-tooltip', title);
        el.setAttribute('title', '');
    }


    // Do stuff on load
    editor.on('load', function() {
        var $ = grapesjs.$;

        // Show borders by default
        editor.Panels.getButton('options', 'sw-visibility').set({
            'command' : 'core:component-outline',
            'active'  : true,
        });

        // Load and show settings and style manager
        var openTmBtn = editor.Panels.getButton('views', 'open-tm');
        openTmBtn && openTmBtn.set('active', 1);
        var openSm = editor.Panels.getButton('views', 'open-sm');
        openSm && openSm.set('active', 1);

        // Remove trait view
        editor.Panels.removeButton('views', 'open-tm');

        // Add Settings Sector
        var traitsSector = $('<div class="gjs-sm-sector no-select">' +
            '<div class="gjs-sm-sector-title"><span class="icon-settings fa fa-cog"></span> <span class="gjs-sm-sector-label"><?php echo get_string('grapsjs-settings', 'local_kopere_dashboard') ?></span></div>' +
            '<div class="gjs-sm-properties" style="display: none;"></div></div>');
        var traitsProps = traitsSector.find('.gjs-sm-properties');
        traitsProps.append($('.gjs-traits-cs'));
        $('.gjs-sm-sectors').before(traitsSector);
        traitsSector.find('.gjs-sm-sector-title').on('click', function() {
            var traitStyle = traitsProps.get(0).style;
            var hidden = traitStyle.display == 'none';
            if (hidden) {
                traitStyle.display = 'block';
            } else {
                traitStyle.display = 'none';
            }
        });

        // Open block manager
        var openBlocksBtn = editor.Panels.getButton('views', 'open-blocks');
        openBlocksBtn && openBlocksBtn.set('active', 1);
    });
</script>
</body>
</html>