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
 * Editor.
 *
 * @package     theme_boost_magnific
 * @copyright   2024 Eduardo kraus (http://eduardokraus.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require_once('./function.php');
require_once('../lib.php');
require_once('../autoload.php');
$PAGE->set_context(\context_system::instance());

$page = required_param('page', PARAM_TEXT);
$id = required_param('id', PARAM_TEXT);
$link = optional_param('link', '', PARAM_TEXT);
require_login();
require_capability('moodle/site:config', context_system::instance());

function loadsvg($file) {
    echo file_get_contents($file);
}

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>VvvebJs</title>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/editor.css" rel="stylesheet">
</head>
<body>

<div id="vvveb-builder">
    <div id="top-panel">
        <img src="img/logo.png" class="float-start" id="logo">

        <div class="btn-group float-start" role="group">
            <button class="btn btn-light active" title="Toggle file manager" id="toggle-file-manager-btn"
                    data-vvveb-action="toggleTreeList" data-bs-toggle="button" aria-pressed="false">
                <img src="libs/builder/icons/file-manager-layout.svg" width="18" height="18" role="presentation">
            </button>

            <button class="btn btn-light active" title="Toggle left column" id="toggle-left-column-btn"
                    data-vvveb-action="toggleLeftColumn" data-bs-toggle="button" aria-pressed="false">
                <img src="libs/builder/icons/left-column-layout.svg" width="18" height="18" role="presentation">
            </button>

            <button class="btn btn-light active" title="Toggle right column" id="toggle-right-column-btn"
                    data-vvveb-action="toggleRightColumn" data-bs-toggle="button" aria-pressed="false">
                <img src="libs/builder/icons/right-column-layout.svg" width="18" height="18" role="presentation">
            </button>
        </div>

        <div class="btn-group me-3" role="group">
            <button class="btn btn-light" title="Undo (Ctrl/Cmd + Z)" id="undo-btn" data-vvveb-action="undo"
                    data-vvveb-shortcut="ctrl+z">
                <?php loadsvg("img/icon-undo.svg") ?>
            </button>

            <button class="btn btn-light" title="Redo (Ctrl/Cmd + Shift + Z)" id="redo-btn" data-vvveb-action="redo"
                    data-vvveb-shortcut="ctrl+shift+z">
                <?php loadsvg("img/icon-redo.svg") ?>
            </button>
        </div>


        <div class="btn-group me-3" role="group">
            <button class="btn btn-light" title="Designer Mode (Free dragging)" id="designer-mode-btn"
                    data-bs-toggle="button" aria-pressed="false" data-vvveb-action="setDesignerMode">
                <?php loadsvg("img/icon-hand-rock.svg") ?>
            </button>

            <button class="btn btn-light" title="Preview" id="preview-btn" type="button" data-bs-toggle="button"
                    aria-pressed="false" data-vvveb-action="preview">
                <?php loadsvg("img/icon-preview.svg") ?>
            </button>

            <button class="btn btn-light" title="Fullscreen (F11)" id="fullscreen-btn" data-bs-toggle="button"
                    aria-pressed="false" data-vvveb-action="fullscreen">
                <?php loadsvg("img/icon-expand.svg") ?>
            </button>
        </div>


        <div class="btn-group me-2 float-end" role="group">
            <?php
            $pagePreview = false;
            if ($page == "webpages") {
                $pagePreview = "{$CFG->wwwroot}/local/kopere_dashboard/";
            } else if ($page == "aceite") {
                $pagePreview = "{$CFG->wwwroot}/local/kopere_pay/termos.php";
            }
            if ($pagePreview) { ?>
            <form class="form-preview" method="post" target="editor-preview"
                  action="<?php echo $pagePreview ?>">
                <input type="hidden" name="page" value="<?php echo $page ?>">
                <input type="hidden" name="link" value="<?php echo $link ?>">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="hidden" name="sesskey" value="<?php echo sesskey() ?>">
                <input type="hidden" name="htmldata" id="form-htmldata">
                <button datatype="--submit" class="btn btn-info btn-sm btn-icon preview-btn mx-2"
                        data-vvveb-action="openPreview">
                    <?php loadsvg("img/icon-preview.svg") ?>
                    <span>Preview</span>
                </button>
                </form><?php
            } ?>

            <button class="btn btn-primary btn-sm btn-icon save-btn" title="Export (Ctrl + E)" id="save-btn"
                    data-vvveb-action="saveAjax"
                    data-vvveb-url="save.php?page=<?php echo $page ?>&id=<?php echo $id ?>&link=<?php echo $link ?>"
                    data-v-vvveb-shortcut="ctrl+e">
                <span class="loading d-none">
                    <?php loadsvg("img/icon-save.svg") ?>
                    <span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span>
                    <span>Saving </span>
                    ...
                </span>
                <span class="button-text">
                    <?php loadsvg("img/icon-save.svg") ?>
                    <span>Save</span>
                </span>
            </button>
        </div>

        <div class="float-end me-3">
            <div class="btn-group responsive-btns" role="group">
                <button id="desktop-view" data-view="" class="btn btn-light active" title="Desktop view"
                        data-vvveb-action="viewport">
                    <i class="la la-laptop"></i>
                </button>
                <button id="mobile-view" data-view="mobile" class="btn btn-light" title="Mobile view"
                        data-vvveb-action="viewport">
                    <i class="la la-mobile"></i>
                </button>
                <button id="tablet-view" data-view="tablet" class="btn btn-light" title="Tablet view"
                        data-vvveb-action="viewport">
                    <i class="la la-tablet"></i>
                </button>
                <div class="percent">
                    <input type="number" id="zoom" value="100" step="10" min="10" max="100" class="form-control"
                           data-vvveb-action="zoomChange" data-vvveb-on="change">

                </div>
            </div>

        </div>

    </div>


    <div id="left-panel">

        <div id="tree-list">
            <div class="header">
                <div>Navigator</div>
            </div>
            <div class="tree">
                <ol>
                </ol>
            </div>
        </div>


        <div class="drag-elements">

            <div class="header">
                <ul class="nav nav-tabs  nav-fill" id="elements-tabs" role="tablist">
                    <li class="nav-item sections-tab">
                        <a class="nav-link active" id="sections-tab" data-bs-toggle="tab" href="#sections" role="tab"
                           aria-controls="sections" aria-selected="true" title="Sections">
                            <?php loadsvg("img/icon-layers.svg") ?>
                        </a>
                    </li>
                    <li class="nav-item component-tab">
                        <a class="nav-link" id="components-tab" data-bs-toggle="tab" href="#components-tabs" role="tab"
                           aria-controls="components" aria-selected="false" title="Components">
                            <?php loadsvg("img/icon-cube.svg") ?>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane show active" id="sections" role="tabpanel"
                         aria-labelledby="sections-tab">
                        <div class="tab-pane sections show active" id="sections-list" data-section="style"
                             role="tabpanel"
                             aria-labelledby="style-tab">
                            <div class="drag-elements-sidepane sidepane">
                                <div>
                                    <div class="sections-container p-4">

                                        <div class="section-item" draggable="true">
                                            <div class="controls">
                                                <div class="handle"></div>
                                                <div class="info">
                                                    <div class="name">&nbsp;
                                                        <div class="type">&nbsp;</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="section-item" draggable="true">
                                            <div class="controls">
                                                <div class="handle"></div>
                                                <div class="info">
                                                    <div class="name">&nbsp;
                                                        <div class="type">&nbsp;</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="section-item" draggable="true">
                                            <div class="controls">
                                                <div class="handle"></div>
                                                <div class="info">
                                                    <div class="name">&nbsp;
                                                        <div class="type">&nbsp;</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane show" id="components-tabs" role="tabpanel" aria-labelledby="components-tab">
                        <ul class="nav nav-tabs nav-fill sections-tabs" role="tablist">
                            <li class="nav-item content-tab">
                                <a class="nav-link active" data-bs-toggle="tab" href="#sections-new-tab" role="tab"
                                   aria-controls="components" aria-selected="false">
                                    <?php loadsvg("img/icon-albums.svg") ?>
                                    <div><span>Sections</span></div>
                                </a>
                            </li>
                            <li class="nav-item blocks-tab">
                                <a class="nav-link" data-bs-toggle="tab" href="#blocks" role="tab"
                                   aria-controls="components" aria-selected="false">
                                    <?php loadsvg("img/icon-copy.svg") ?>
                                    <div><span>Blocks</span></div>
                                </a>
                            </li>
                            <li class="nav-item components-tab">
                                <a class="nav-link" data-bs-toggle="tab" href="#components" role="tab"
                                   aria-controls="components" aria-selected="true">
                                    <?php loadsvg("img/icon-cube.svg") ?>
                                    <div><span>Components</span></div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane sections show active" id="sections-new-tab" data-section="content"
                                 role="tabpanel" aria-labelledby="content-tab">
                                <div class="search">
                                    <div class="expand">
                                        <button class="text-sm" title="Expand All" data-vvveb-action="expand"><i
                                                    class="la la-plus"></i></button>
                                        <button title="Collapse all" data-vvveb-action="collapse"><i
                                                    class="la la-minus"></i></button>
                                    </div>

                                    <input class="form-control section-search" placeholder="Search sections" type="text"
                                           data-vvveb-action="search" data-vvveb-on="keyup">
                                    <button class="clear-backspace" data-vvveb-action="clearSearch"
                                            title="Clear search">
                                        <i class="la la-times"></i>
                                    </button>
                                </div>
                                <div class="drag-elements-sidepane sidepane">
                                    <div class="block-preview"><img src="" style="display:none"></div>
                                    <div>
                                        <ul class="sections-list clearfix" data-type="leftpanel"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane blocks" id="blocks" data-section="content" role="tabpanel"
                                 aria-labelledby="content-tab">
                                <div class="search">
                                    <div class="expand">
                                        <button class="text-sm" title="Expand All" data-vvveb-action="expand"><i
                                                    class="la la-plus"></i></button>
                                        <button title="Collapse all" data-vvveb-action="collapse"><i
                                                    class="la la-minus"></i></button>
                                    </div>

                                    <input class="form-control block-search" placeholder="Search blocks" type="text"
                                           data-vvveb-action="search" data-vvveb-on="keyup">
                                    <button class="clear-backspace" data-vvveb-action="clearSearch">
                                        <i class="la la-times"></i>
                                    </button>
                                </div>
                                <div class="drag-elements-sidepane sidepane">
                                    <div class="block-preview"><img src=""></div>
                                    <div>
                                        <ul class="blocks-list clearfix" data-type="leftpanel">
                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane components" id="components" data-section="components" role="tabpanel"
                                 aria-labelledby="components-tab">
                                <div class="search">
                                    <div class="expand">
                                        <button class="text-sm" title="Expand All" data-vvveb-action="expand"><i
                                                    class="la la-plus"></i></button>
                                        <button title="Collapse all" data-vvveb-action="collapse"><i
                                                    class="la la-minus"></i></button>
                                    </div>

                                    <input class="form-control component-search" placeholder="Search components"
                                           type="text" data-vvveb-action="search" data-vvveb-on="keyup">
                                    <button class="clear-backspace" data-vvveb-action="clearSearch">
                                        <i class="la la-times"></i>
                                    </button>
                                </div>
                                <div class="drag-elements-sidepane sidepane">
                                    <div>
                                        <ul class="components-list clearfix" data-type="leftpanel"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div id="canvas">
        <div id="iframe-wrapper">
            <div id="iframe-layer">

                <div class="loading-message active">
                    <div class="animation-container">
                        <div class="dot dot-1"></div>
                        <div class="dot dot-2"></div>
                        <div class="dot dot-3"></div>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                        <defs>
                            <filter id="goo">
                                <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"/>
                                <feColorMatrix in="blur" mode="matrix"
                                               values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 21 -7"/>
                            </filter>
                        </defs>
                    </svg>
                    <!-- https://codepen.io/Izumenko/pen/MpWyXK -->
                </div>

                <div id="highlight-box">
                    <div id="highlight-name">
                        <span class="name"></span>
                        <span class="type"></span>
                    </div>

                    <div id="section-actions">
                        <a id="add-section-btn" href="" title="Add element"><i class="la la-plus"></i></a>
                    </div>
                </div>

                <div id="select-box">

                    <div id="wysiwyg-editor" class="default-editor">
                        <!--
                        <a id="bold-btn" href="" title="Bold">
                            <svg height="24" viewBox="0 0 256 256" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" d="M56 40V216H148C176.719 216 200 192.719 200 164C200 147.849 192.637 133.418 181.084 123.88C187.926 115.076 192 104.014 192 92C192 63.2812 168.719 40 140 40H56ZM88 144V184H148C159.046 184 168 175.046 168 164C168 152.954 159.046 144 148 144H88ZM88 112V72H140C151.046 72 160 80.9543 160 92C160 103.046 151.046 112 140 112H88Z" fill="#252525" fill-rule="evenodd"/>
                            </svg>
                        </a>
                        <a id="italic-btn" href="" title="Italic">
                            <svg height="24" viewBox="0 0 256 256" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M202 40H84V64H126.182L89.8182 192H54V216H172V192H129.818L166.182 64H202V40Z" fill="#252525"/>
                            </svg>
                        </a>
                        <a id="underline-btn" href="" title="Underline">
                            <svg height="24" viewBox="0 0 256 256" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" d="M88 40H60V108.004C60 145.56 90.4446 176.004 128 176.004C165.555 176.004 196 145.56 196 108.004V40H168V108C168 130.091 150.091 148 128 148C105.909 148 88 130.091 88 108V40ZM204 216V192H52V216H204Z" fill="#252525" fill-rule="evenodd"/>
                            </svg>
                        </a>
                        -->

                        <a id="bold-btn" class="hint" href="" title="Bold" aria-label="Bold">
                            <svg height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6,4h8a4,4,0,0,1,4,4h0a4,4,0,0,1-4,4H6Z" fill="none" stroke="currentColor"
                                      stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                                <path d="M6,12h9a4,4,0,0,1,4,4h0a4,4,0,0,1-4,4H6Z" fill="none" stroke="currentColor"
                                      stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
                            </svg>
                        </a>
                        <a id="italic-btn" class="hint" href="" title="Italic" aria-label="Italic">
                            <svg height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                <line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" x1="19" x2="10" y1="4" y2="4"/>
                                <line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" x1="14" x2="5" y1="20" y2="20"/>
                                <line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" x1="15" x2="9" y1="4" y2="20"/>
                            </svg>
                        </a>
                        <a id="underline-btn" class="hint" href="" title="Underline" aria-label="Underline">
                            <svg height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6,4v7a6,6,0,0,0,6,6h0a6,6,0,0,0,6-6V4" fill="none" stroke="currentColor"
                                      stroke-linecap="round" stroke-linejoin="round" stroke-width="2" y1="2" y2="2"/>
                                <line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" x1="4" x2="20" y1="22" y2="22"/>
                            </svg>
                        </a>


                        <a id="strike-btn" class="hint" href="" title="Strikeout" aria-label="Strikeout">
                            <del>S</del>
                        </a>

                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="hint" aria-label="Text align"><i class="la la-align-left"></i></span>
                            </button>

                            <div id="justify-btn" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" data-value="Left"><i
                                            class="la la-lg la-align-left"></i> Align Left</a>
                                <a class="dropdown-item" href="#" data-value="Center"><i
                                            class="la la-lg la-align-center"></i> Align Center</a>
                                <a class="dropdown-item" href="#" data-value="Right"><i
                                            class="la la-lg la-align-right"></i> Align Right</a>
                                <a class="dropdown-item" href="#" data-value="Full"><i
                                            class="la la-lg la-align-justify"></i> Align Justify</a>
                            </div>
                        </div>

                        <div class="separator"></div>

                        <a id="link-btn" class="hint" href="" title="Create link" aria-label="Create link">
                            <i class="la la-link">
                            </i></a>

                        <div class="separator"></div>

                        <input id="fore-color" name="color" type="color" aria-label="Text color" pattern="#[a-f0-9]{6}"
                               class="form-control form-control-color hint">
                        <input id="back-color" name="background-color" type="color" aria-label="Background color"
                               pattern="#[a-f0-9]{6}" class="form-control form-control-color hint">

                        <div class="separator"></div>

                        <select id="font-size" class="form-select" aria-label="Font size">
                            <option value="">- Font size -</option>
                            <option value="8px">8 px</option>
                            <option value="9px">9 px</option>
                            <option value="10px">10 px</option>
                            <option value="11px">11 px</option>
                            <option value="12px">12 px</option>
                            <option value="13px">13 px</option>
                            <option value="14px">14 px</option>
                            <option value="15px">15 px</option>
                            <option value="16px">16 px</option>
                            <option value="17px">17 px</option>
                            <option value="18px">18 px</option>
                            <option value="19px">19 px</option>
                            <option value="20px">20 px</option>
                            <option value="21px">21 px</option>
                            <option value="22px">22 px</option>
                            <option value="23px">23 px</option>
                            <option value="24px">24 px</option>
                            <option value="25px">25 px</option>
                            <option value="26px">26 px</option>
                            <option value="27px">27 px</option>
                            <option value="28px">28 px</option>
                        </select>

                        <div class="separator"></div>

                        <select id="font-family" class="form-select" title="Font family">
                            <option value=""> - Font family -</option>
                            <optgroup label="System default">
                                <option value="Arial, Helvetica, sans-serif">Arial</option>
                                <option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif">Lucida Grande
                                </option>
                                <option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif">Palatino Linotype
                                </option>
                                <option value="'Times New Roman', Times, serif">Times New Roman</option>
                                <option value="Georgia, serif">Georgia, serif</option>
                                <option value="Tahoma, Geneva, sans-serif">Tahoma</option>
                                <option value="'Comic Sans MS', cursive, sans-serif">Comic Sans</option>
                                <option value="Verdana, Geneva, sans-serif">Verdana</option>
                                <option value="Impact, Charcoal, sans-serif">Impact</option>
                                <option value="'Arial Black', Gadget, sans-serif">Arial Black</option>
                                <option value="'Trebuchet MS', Helvetica, sans-serif">Trebuchet</option>
                                <option value="'Courier New', Courier, monospace">Courier New</option>
                                <option value="'Brush Script MT', sans-serif">Brush Script</option>
                            </optgroup>
                        </select>
                    </div>

                    <div id="select-actions">
                        <div class="title">Double click to open editor</div>
                        <a id="drag-btn" href="" title="Drag element"><?php loadsvg("img/icon-expand.svg") ?></a>
                        <a id="parent-btn" href=""
                           title="Select parent"><?php loadsvg("img/icon-level-up-alt.svg") ?></a>

                        <a id="up-btn" href="" title="Move element up"><?php loadsvg("img/icon-arrow-up.svg") ?></a>
                        <a id="down-btn" href=""
                           title="Move element down"><?php loadsvg("img/icon-arrow-down.svg") ?></a>
                        <a id="edit-code-btn" href=""
                           title="Edit html code"><?php loadsvg("img/icon-code-slash.svg") ?></a>

                        <a id="clone-btn" href="" title="Clone element"><?php loadsvg("img/icon-copy.svg") ?></a>
                        <a id="delete-btn" href="" title="Remove element"><?php loadsvg("img/icon-trash.svg") ?></i></a>
                    </div>

                    <div class="resize">
                        <!-- top -->
                        <div class="top-left">
                        </div>
                        <div class="top-center">
                        </div>
                        <div class="top-right">
                        </div>
                        <!-- center -->
                        <div class="center-left">
                        </div>
                        <div class="center-right">
                        </div>
                        <!-- bottom -->
                        <div class="bottom-left">
                        </div>
                        <div class="bottom-center">
                        </div>
                        <div class="bottom-right">
                        </div>
                    </div>

                </div>

                <!-- add section box -->
                <div id="add-section-box" class="drag-elements">

                    <div class="header">
                        <ul class="nav nav-tabs" id="box-elements-tabs" role="tablist">
                            <li class="nav-item component-tab">
                                <a class="nav-link px-3 active" id="box-components-tab" data-bs-toggle="tab"
                                   href="#box-components" role="tab" aria-controls="components" aria-selected="true"><i
                                            class="icon-cube-outline"></i>
                                    <small>Components</small>
                                </a>
                            </li>
                            <li class="nav-item sections-tab">
                                <a class="nav-link px-3" id="box-sections-tab" data-bs-toggle="tab" href="#box-blocks"
                                   role="tab" aria-controls="blocks" aria-selected="false"><i
                                            class="icon-copy-outline"></i>
                                    <small>Blocks</small>
                                </a>
                            </li>
                        </ul>

                        <div class="section-box-actions">

                            <div id="close-section-btn" class="btn btn-outline-secondary btn-sm border-0 float-end"><i
                                        class="la la-times la-lg"></i></div>

                            <div class="me-4 float-end">

                                <div class="form-check d-inline-block small me-1">
                                    <input type="radio" id="add-section-insert-mode-after" value="after"
                                           checked="checked" name="add-section-insert-mode" class="form-check-input">
                                    <label class="form-check-label" for="add-section-insert-mode-after">After</label>
                                </div>

                                <div class="form-check d-inline-block small">
                                    <input type="radio" id="add-section-insert-mode-inside" value="inside"
                                           name="add-section-insert-mode" class="form-check-input">
                                    <label class="form-check-label" for="add-section-insert-mode-inside">Inside</label>
                                </div>

                            </div>

                        </div>

                        <div class="tab-content">
                            <div class="tab-pane show active" id="box-components" role="tabpanel"
                                 aria-labelledby="components-tab">

                                <div class="search">
                                    <div class="expand">
                                        <button class="text-sm" title="Expand All" data-vvveb-action="expand"><i
                                                    class="la la-plus"></i></button>
                                        <button title="Collapse all" data-vvveb-action="collapse"><i
                                                    class="la la-minus"></i></button>
                                    </div>

                                    <input class="form-control component-search" placeholder="Search components"
                                           type="text" data-vvveb-action="search" data-vvveb-on="keyup">
                                    <button class="clear-backspace" data-vvveb-action="clearSearch">
                                        <i class="la la-times"></i>
                                    </button>
                                </div>

                                <div>
                                    <div>

                                        <ul class="components-list clearfix" data-type="addbox">
                                        </ul>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="box-blocks" role="tabpanel" aria-labelledby="blocks-tab">

                                <div class="search">
                                    <div class="expand">
                                        <button class="text-sm" title="Expand All" data-vvveb-action="expand"><i
                                                    class="la la-plus"></i></button>
                                        <button title="Collapse all" data-vvveb-action="collapse"><i
                                                    class="la la-minus"></i></button>
                                    </div>

                                    <input class="form-control block-search" placeholder="Search blocks" type="text"
                                           data-vvveb-action="search" data-vvveb-on="keyup">
                                    <button class="clear-backspace" data-vvveb-action="clearSearch">
                                        <i class="la la-times"></i>
                                    </button>
                                </div>

                                <div>
                                    <div>

                                        <ul class="blocks-list clearfix" data-type="addbox">
                                        </ul>

                                    </div>
                                </div>

                            </div>

                            <!-- div class="tab-pane" id="box-properties" role="tabpanel" aria-labelledby="blocks-tab">
                                <div class="component-properties-sidepane">
                                    <div>
                                        <div class="component-properties">
                                            <div class="mt-4 text-center">Click on an element to edit.</div>
                                        </div>
                                    </div>
                                </div>
                            </div -->
                        </div>
                    </div>

                </div>
                <!-- //add section box -->

                <div id="drop-highlight-box">
                </div>
            </div>


            <iframe src="" id="iframe1">
            </iframe>
        </div>


    </div>

    <div id="right-panel">
        <div class="component-properties">

            <ul class="nav nav-tabs nav-fill" id="properties-tabs" role="tablist">
                <li class="nav-item content-tab">
                    <a class="nav-link active" data-bs-toggle="tab" href="#content-tab" role="tab"
                       aria-controls="components" aria-selected="true">
                        <?php loadsvg("img/icon-albums.svg") ?>
                        <div><span>Content</span></div>
                    </a>
                </li>
                <li class="nav-item style-tab">
                    <a class="nav-link" data-bs-toggle="tab" href="#style-tab" role="tab" aria-controls="blocks"
                       aria-selected="false">
                        <?php loadsvg("img/icon-color-fill.svg") ?>
                        <div><span>Style</span></div>
                    </a>
                </li>
                <li class="nav-item advanced-tab">
                    <a class="nav-link" data-bs-toggle="tab" href="#advanced-tab" role="tab" aria-controls="blocks"
                       aria-selected="false">
                        <?php loadsvg("img/icon-settings.svg") ?>
                        <div><span>Advanced</span></div>
                    </a>
                </li>

                <!--li class="nav-item vars-tab">
                    <a class="nav-link" data-bs-toggle="tab" href="#vars-tab" role="tab"
                       aria-controls="components" aria-selected="false">
                        <?php loadsvg("img/icon-brush.svg") ?>
                        <div><span>Variables</span></div>
                    </a>
                </li-->
                <li class="nav-item css-tab">
                    <a class="nav-link" data-bs-toggle="tab" href="#css-tab" role="tab" aria-controls="css"
                       aria-selected="true">
                        <?php loadsvg("img/icon-code-slash.svg") ?>
                        <div><span>Css</span></div>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane show active" id="content-tab" data-section="content" role="tabpanel"
                     aria-labelledby="content-tab">
                    <div class="alert alert-dismissible fade show alert-light m-3" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>No selected element!</strong><br> Click on an element to edit.
                    </div>
                </div>
                <div class="tab-pane show" id="style-tab" data-section="style" role="tabpanel"
                     aria-labelledby="style-tab">
                </div>
                <div class="tab-pane show" id="advanced-tab" data-section="advanced" role="tabpanel"
                     aria-labelledby="advanced-tab">
                </div>

                <div class="tab-pane show" id="css-tab" data-section="css" role="tabpanel"
                     aria-labelledby="css-tab">
                    <div class="drag-elements-sidepane sidepane">
                        <div data-offset="80">
                            <textarea id="css-editor" class="form-control" rows="24"></textarea>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div id="bottom-panel">

        <div>

            <div class="breadcrumb-navigator px-2" style="--bs-breadcrumb-divider: '>';">

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">body</a></li>
                    <li class="breadcrumb-item"><a href="#">section</a></li>
                    <li class="breadcrumb-item"><a href="#">img</a></li>
                </ol>
            </div>


            <div class="btn-group" role="group">

                <div id="toggleEditorJsExecute" class="form-check mt-1" style="display:none">
                    <input type="checkbox" class="form-check-input" id="runjs" name="runjs"
                           data-vvveb-action="toggleEditorJsExecute">
                    <label class="form-check-label" for="runjs">
                        <small>Run javascript code on edit</small>
                    </label>&ensp;
                </div>


                <button id="code-editor-btn" class="btn btn-sm btn-light btn-sm" title="Code editor"
                        data-vvveb-action="toggleEditor">
                    <i class="la la-code"></i> Code editor
                </button>


            </div>

        </div>

        <div id="vvveb-code-editor">
            <textarea class="form-control"></textarea>
            <div>
            </div>
        </div>
    </div>
</div>


<!-- templates -->

<script id="vvveb-input-textinput" type="text/html">

    <div>
        <input name="{%=key%}" type="text" class="form-control"/>
    </div>

</script>
<script id="vvveb-input-textareainput" type="text/html">

    <div>
        <textarea name="{%=key%}" {% if (typeof rows !== 'undefined') { %} rows="{%=rows%}" {% } else { %} rows="3" {% }
        %} class="form-control"/>
    </div>

</script>
<script id="vvveb-input-checkboxinput" type="text/html">

    <div class="form-check{% if (typeof className !== 'undefined') { %} {%=className%}{% } %}">
        <input name="{%=key%}" class="form-check-input" type="checkbox" id="{%=key%}_check">
        <label class="form-check-label" for="{%=key%}_check">{% if (typeof text !== 'undefined') { %} {%=text%} {% }
            %}</label>
    </div>

</script>
<script id="vvveb-input-radioinput" type="text/html">

    <div>

        {% for ( var i = 0; i < options.length; i++ ) { %}

        <label class="form-check-input  {% if (typeof inline !== 'undefined' && inline == true) { %}custom-control-inline{% } %}"
               title="{%=options[i].title%}">
            <input name="{%=key%}" class="form-check-input" type="radio" value="{%=options[i].value%}"
                   id="{%=key%}{%=i%}" {%if (options[i].checked) { %}checked="{%=options[i].checked%}" {% } %}>
            <label class="form-check-label" for="{%=key%}{%=i%}">{%=options[i].text%}</label>
        </label>

        {% } %}

    </div>

</script>
<script id="vvveb-input-radiobuttoninput" type="text/html">

    <div class="btn-group {%if (extraclass) { %}{%=extraclass%}{% } %} clearfix" role="group">
        {% var namespace = 'rb-' + Math.floor(Math.random() * 100); %}

        {% for ( var i = 0; i < options.length; i++ ) { %}

        <input name="{%=key%}" class="btn-check" type="radio" value="{%=options[i].value%}"
               id="{%=namespace%}{%=key%}{%=i%}" {%if (options[i].checked) { %}checked="{%=options[i].checked%}" {% } %}
               autocomplete="off">
        <label class="btn btn-outline-primary {%if (options[i].extraclass) { %}{%=options[i].extraclass%}{% } %}"
               for="{%=namespace%}{%=key%}{%=i%}" title="{%=options[i].title%}">
            {%if (options[i].icon) { %}<i class="{%=options[i].icon%}"></i>{% } %}
            {%=options[i].text%}
        </label>

        {% } %}

    </div>

</script>
<script id="vvveb-input-toggle" type="text/html">

    <div class="form-check form-switch {% if (typeof className !== 'undefined') { %} {%=className%}{% } %}">
        <input
                type="checkbox"
                name="{%=key%}"
                value="{%=on%}"
                {%if (off) { %} data-value-off="{%=off%}" {% } %}
                {%if (on) { %} data-value-on="{%=on%}" {% } %}
                class="form-check-input" type="checkbox" role="switch"
                id="{%=key%}">
        <label class="form-check-label" for="{%=key%}">
        </label>
    </div>

</script>
<script id="vvveb-input-header" type="text/html">

    <h6 class="header">{%=header%}</h6>

</script>
<script id="vvveb-input-select" type="text/html">

    <div>

        <select class="form-select" name="{%=key%}">
            {% var optgroup = false; for ( var i = 0; i < options.length; i++ ) { %}
            {% if (options[i].optgroup) { %}
            {% if (optgroup) { %}
            </optgroup>
            {% } %}
            <optgroup label="{%=options[i].optgroup%}">
                {% optgroup = true; } else { %}
                <option value="{%=options[i].value%}"
                        {%
                        for (attr in options[i]) {
                        if (attr !="value" && attr !="text" ) {
                        %}
                        {%=attr%}={%=options[i][attr]%}
                        {% }
                        } %}>
                    {%=options[i].text%}
                </option>
                {% } } %}
        </select>

    </div>

</script>
<script id="vvveb-input-icon-select" type="text/html">

    <div class="input-list-select">

        <div class="elements">
            <div class="row">
                {% for ( var i = 0; i < options.length; i++ ) { %}
                <div class="col">
                    <div class="element">
                        {%=options[i].value%}
                        <label>{%=options[i].text%}</label>
                    </div>
                </div>
                {% } %}
            </div>
        </div>
    </div>

</script>
<script id="vvveb-input-html-list-select" type="text/html">

    <div class="input-html-list-select">

        <div class="current-element">
        </div>

        <div class="popup">
            <select class="form-select">
                {% var optgroup = false; for ( var i = 0; i < options.length; i++ ) { %}
                {% if (options[i].optgroup) { %}
                {% if (optgroup) { %}
                </optgroup>
                {% } %}
                <optgroup label="{%=options[i].optgroup%}">
                    {% optgroup = true; } else { %}
                    <option value="{%=options[i].value%}">{%=options[i].text%}</option>
                    {% } } %}
            </select>

            <div class="search">
                <input class="form-control search" placeholder="Search elements" type="text">
                <button class="clear-backspace">
                    <i class="la la-times"></i>
                </button>
            </div>

            <div class="elements">
                {%=elements%}
            </div>
        </div>
    </div>

</script>
<script id="vvveb-input-html-list-dropdown" type="text/html">

    <div class="input-html-list-select" {% if (typeof id !== "undefined") { %} id={%=id%} {% } %}>

    <div class="current-element">

    </div>

    <div class="popup">
        <select class="form-select">
            {% var optgroup = false; for ( var i = 0; i < options.length; i++ ) { %}
            {% if (options[i].optgroup) { %}
            {% if (optgroup) { %}
            </optgroup>
            {% } %}
            <optgroup label="{%=options[i].optgroup%}">
                {% optgroup = true; } else { %}
                <option value="{%=options[i].value%}">{%=options[i].text%}</option>
                {% } } %}
        </select>

        <div class="search">
            <input class="form-control search" placeholder="Search elements" type="text">
            <button class="clear-backspace">
                <i class="la la-times"></i>
            </button>
        </div>

        <div class="elements">
            {%=elements%}
        </div>
    </div>
    </div>

</script>
<script id="vvveb-input-dateinput" type="text/html">

    <div>
        <input name="{%=key%}" type="date" class="form-control"
               {% if (typeof min_date=== 'undefined') { %} min="{%=min_date%}" {% } %} {% if (typeof max_date ===
        'undefined') { %} max="{%=max_date%}" {% } %}
        />
    </div>

</script>
<script id="vvveb-input-listinput" type="text/html">

    <div class="sections-container">

        {% for ( var i = 0; i < options.length; i++ ) { %}
        <div class="section-item" draggable="true">
            <div class="controls">
                <div class="handle"></div>
                <div class="info">
                    <div class="name">{%=options[i].name%}
                        <div class="type">{%=options[i].type%}</div>
                    </div>
                </div>
                <div class="buttons">
                    <a class="delete-btn" href="" title="Remove section"><?php loadsvg("img/icon-trash.svg") ?></a>
                </div>
            </div>


            <input class="header_check" type="checkbox" id="section-components-{%=options[i].suffix%}">

            <label for="section-components-{%=options[i].suffix%}">
                <div class="header-arrow"></div>
            </label>

            <div class="tree">
                {%=options[i].name%}
            </div>
        </div>


        {% } %}


        {% if (typeof hide_remove === 'undefined') { %}
        <div class="mt-3">

            <button class="btn btn-sm btn-outline-primary btn-new">
                <i class="la la-plus la-lg"></i> Add new
            </button>

        </div>
        {% } %}

    </div>

</script>
<script id="vvveb-input-grid" type="text/html">

    <div class="row">
        <div class="col-6">

            <label>Extra small</label>
            <select class="form-select" name="col" autocomplete="off">

                <option value="">None</option>
                {% for ( var i = 1; i <= 12; i++ ) { %}
                <option value="{%=i%}" {% if ((typeof col !==
                'undefined') && col == i) { %} selected {% } %}>{%=i%}</option>
                {% } %}

            </select>
        </div>


        <div class="col-6">
            <label>Small</label>
            <select class="form-select" name="col-sm" autocomplete="off">

                <option value="">None</option>
                {% for ( var i = 1; i <= 12; i++ ) { %}
                <option value="{%=i%}" {% if ((typeof col_sm !==
                'undefined') && col_sm == i) { %} selected {% } %}>{%=i%}</option>
                {% } %}

            </select>
        </div>

        <div class="col-6">
            <label>Medium</label>
            <select class="form-select" name="col-md" autocomplete="off">

                <option value="">None</option>
                {% for ( var i = 1; i <= 12; i++ ) { %}
                <option value="{%=i%}" {% if ((typeof col_md !==
                'undefined') && col_md == i) { %} selected {% } %}>{%=i%}</option>
                {% } %}

            </select>
        </div>

        <div class="col-6">
            <label>Large</label>
            <select class="form-select" name="col-lg" autocomplete="off">

                <option value="">None</option>
                {% for ( var i = 1; i <= 12; i++ ) { %}
                <option value="{%=i%}" {% if ((typeof col_lg !==
                'undefined') && col_lg == i) { %} selected {% } %}>{%=i%}</option>
                {% } %}

            </select>
        </div>


        <div class="col-6">
            <label>Extra large </label>
            <select class="form-select" name="col-xl" autocomplete="off">

                <option value="">None</option>
                {% for ( var i = 1; i <= 12; i++ ) { %}
                <option value="{%=i%}" {% if ((typeof col_xl !==
                'undefined') && col_xl == i) { %} selected {% } %}>{%=i%}</option>
                {% } %}

            </select>
        </div>

        <div class="col-6">
            <label>Extra extra large</label>
            <select class="form-select" name="col-xxl" autocomplete="off">

                <option value="">None</option>
                {% for ( var i = 1; i <= 12; i++ ) { %}
                <option value="{%=i%}" {% if ((typeof col_xxl !==
                'undefined') && col_xxl == i) { %} selected {% } %}>{%=i%}</option>
                {% } %}

            </select>
        </div>

        {% if (typeof hide_remove === 'undefined') { %}
        <div class="col-12">

            <button class="btn btn-sm btn-outline-light text-danger">
                <i class="la la-trash la-lg"></i> Remove
            </button>

        </div>
        {% } %}

    </div>

</script>
<script id="vvveb-input-textvalue" type="text/html">

    <div class="row">
        <div class="col-6 mb-1">
            <label>Value</label>
            <input name="value" type="text" value="{%=value%}" class="form-control" autocomplete="off"/>
        </div>

        <div class="col-6 mb-1">
            <label>Text</label>
            <input name="text" type="text" value="{%=text%}" class="form-control" autocomplete="off"/>
        </div>

        {% if (typeof hide_remove === 'undefined') { %}
        <div class="col-12">

            <button class="btn btn-sm btn-outline-light text-danger">
                <i class="la la-trash la-lg"></i> Remove
            </button>

        </div>
        {% } %}

    </div>

</script>
<script id="vvveb-input-rangeinput" type="text/html">

    <div class="input-range">

        <input name="{%=key%}" type="range" min="{%=min%}" max="{%=max%}" step="{%=step%}" class="form-range"
               data-input-value/>
        <input name="{%=key%}" type="number" min="{%=min%}" max="{%=max%}" step="{%=step%}" class="form-control"
               data-input-value/>
    </div>

</script>
<script id="vvveb-input-imageinput" type="text/html">

    <div>
        <input name="{%=key%}" type="text" class="form-control"/>
        <input name="file" type="file" class="form-control"/>
    </div>

</script>
<script id="vvveb-input-imageinput-gallery" type="text/html">

    <div>
        <img id="thumb-{%=key%}" class="img-thumbnail p-0" data-target-input="#input-{%=key%}"
             data-target-thumb="#thumb-{%=key%}" style="cursor:pointer" src="" width="225" height="225">
        <input name="{%=key%}" type="text" class="form-control mt-1" id="input-{%=key%}"/>
        <button name="button" class="btn btn-primary btn-sm btn-icon mt-2 width-100"
                data-target-input="#input-{%=key%}"
                data-target-thumb="#thumb-{%=key%}">
            <i data-target-input="#input-{%=key%}"
               data-target-thumb="#thumb-{%=key%}"
               class="la la-image la-lg"></i>
            <span data-target-input="#input-{%=key%}"
                  data-target-thumb="#thumb-{%=key%}">Set image</span></button>
    </div>

</script>
<script id="vvveb-input-videoinput-gallery" type="text/html">

    <div>
        <video id="thumb-v{%=key%}" class="img-thumbnail p-0" data-target-input="#input-v{%=key%}"
               data-target-thumb="#thumb-v{%=key%}" style="cursor:pointer" src="" width="225" height="225" playsinline
               loop muted controls></video>
        <input name="v{%=key%}" type="text" class="form-control mt-1" id="input-v{%=key%}"/>
        <button name="button" class="btn btn-primary btn-sm btn-icon mt-2" data-target-input="#vinput-v{%=key%}"
                data-target-thumb="#thumb-v{%=key%}"><i class="la la-video la-lg"></i><span>Set video</span></button>
    </div>

</script>
<script id="vvveb-input-colorinput" type="text/html">

    <div>
        <input name="{%=key%}" {% if (typeof palette !== 'undefined') { %} list="{%=key%}-color-palette" {% } %}
        type="color" {% if (typeof value !== 'undefined' && value != false) { %} value="{%=value%}" {% } %}
        pattern="#[a-f0-9]{6}" class="form-control form-control-color"/>
        {% if (typeof palette !== 'undefined') { %}
        <datalist id="{%=key%}-color-palette">
            {% for (const color in palette) { %}
            <option value="{%=color%}">{%=palette[color]%}</option>
            {% } %}
            {% } %}
    </div>

</script>
<script id="vvveb-input-bootstrap-color-picker-input" type="text/html">

    <div>
        <div id="cp2" class="input-group" title="Using input value">
            <input name="{%=key%}" type="text" {% if (typeof value !== 'undefined' && value != false) { %}
            value="{%=value%}" {% } %} class="form-control"/>
            <span class="input-group-append">
			<span class="input-group-text colorpicker-input-addon"><i></i></span>
		  </span>
        </div>
    </div>

</script>
<script id="vvveb-input-numberinput" type="text/html">
    <div>
        <input name="{%=key%}" type="number" value="{%=value%}"
               {% if (typeof min !== 'undefined' && min != false) { %}min="{%=min%}"{% } %}
        {% if (typeof max !== 'undefined' && max != false) { %}max="{%=max%}"{% } %}
        {% if (typeof step !== 'undefined' && step != false) { %}step="{%=step%}"{% } %}
        class="form-control"/>
    </div>
</script>
<script id="vvveb-input-button" type="text/html">
    <div>
        <button class="btn btn-sm btn-primary">
            <i class="la  {% if (typeof icon !== 'undefined') { %} {%=icon%} {% } else { %} la-plus {% } %} la-lg"></i>
            {%=text%}
        </button>
    </div>
</script>
<script id="vvveb-input-cssunitinput" type="text/html">
    <div class="input-group css-unit" id="cssunit-{%=key%}">
        <input name="number" type="number" {% if (typeof value !== 'undefined' && value != false) { %}
        value="{%=value%}" {% } %}
        {% if (typeof min !== 'undefined' && min != false) { %}min="{%=min%}"{% } %}
        {% if (typeof max !== 'undefined' && max != false) { %}max="{%=max%}"{% } %}
        {% if (typeof step !== 'undefined' && step != false) { %}step="{%=step%}"{% } %}
        class="form-control"/>
        <select class="form-select small-arrow" name="unit">
            <option value="em">em</option>
            <option value="rem">rem</option>
            <option value="px">px</option>
            <option value="%">%</option>
            <option value="vw">vw</option>
            <option value="vh">vh</option>
            <option value="ex">ex</option>
            <option value="ch">ch</option>
            <option value="cm">cm</option>
            <option value="mm">mm</option>
            <option value="in">in</option>
            <option value="pt">pt</option>
            <option value="auto">auto</option>
            <option value="">-</option>
        </select>
    </div>

</script>
<script id="vvveb-breadcrumb-navigaton-item" type="text/html">
    <li class="breadcrumb-item"><a href="#" {% if (typeof className !== 'undefined') { %}class="{%=className%}"{% }
        %}>{%=name%}</a></li>
</script>
<script id="vvveb-input-sectioninput" type="text/html">
    <div>
        {% var namespace = '-' + Math.floor(Math.random() * 1000); %}
        <label class="header" data-header="{%=key%}" for="header_{%=key%}{%=namespace%}" {% if (typeof group !==
        'undefined' && group != null) { %}data-group="{%=group%}" {% } %}><span>{%=header%}</span>
        <div class="header-arrow"></div>
        </label>
        <input class="header_check" type="checkbox" {% if (typeof expanded !== 'undefined' && expanded == false) { %} {%
        } else { %}checked="true"{% } %} id="header_{%=key%}{%=namespace%}">
        <div class="section row" data-message="" data-section="{%=key%}" {% if (typeof group !==
        'undefined' && group != null) { %}data-group="{%=group%}" {% } %}>
    </div>
    </div>
</script>
<script id="vvveb-property" type="text/html">

    <div class="mb-3 {% if (typeof col !== 'undefined' && col != false) { %} col-sm-{%=col%} {% } else { %}row{% } %} {% if (typeof inline !== 'undefined' && inline == true) { %}inline{% } %} "
         data-key="{%=key%}" {% if (typeof group !== 'undefined' && group != null) { %}data-group="{%=group%}" {% } %}>

    {% if (typeof name !== 'undefined' && name != false) { %}<label
            class="{% if (typeof inline === 'undefined' ) { %}col-sm-4{% } %} form-label"
            for="input-model">{%=name%}</label>{% } %}

    <div class="{% if (typeof inline === 'undefined') { %}col-sm-{% if (typeof name !== 'undefined' && name != false) { %}8{% } else { %}12{% } } %} input"></div>

    </div>

</script>
<script id="vvveb-input-autocompletelist" type="text/html">

    <div>
        <input name="{%=key%}" type="text" class="form-control"/>

        <div class="form-control autocomplete-list" style="min-height: 150px; overflow: auto;">
        </div>
    </div>

</script>
<script id="vvveb-input-tagsinput" type="text/html">

    <div>
        <div class="form-control tags-input" style="height:auto;">


            <input name="{%=key%}" type="text" class="form-control" style="border:none;min-width:60px;"/>
        </div>
    </div>

</script>
<script id="vvveb-input-noticeinput" type="text/html">
    <div>
        <div class="alert alert-dismissible fade show alert-{%=type%}" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <h6><b>{%=title%}</b></h6>

            {%=text%}
        </div>
    </div>
</script>
<script id="vvveb-section" type="text/html">
    {% var suffix = Math.floor(Math.random() * 10000); %}

    <div class="section-item" draggable="true">
        <div class="controls">
            <div class="handle"></div>
            <div class="info">
                <div class="name">{%=name%}
                    <div class="type">{%=type%}</div>
                </div>
            </div>
            <div class="buttons">
                <a class="delete-btn" href="" title="Remove section"><i class="la la-trash text-danger"></i></a>
                <!--
                <a class="up-btn" href="" title="Move element up"><i class="la la-arrow-up"></i></a>
                <a class="down-btn" href="" title="Move element down"><i class="la la-arrow-down"></i></a>
                -->
                <a class="properties-btn" href="" title="Properties"><?php loadsvg("img/icon-settings.svg") ?></a>
            </div>
        </div>


        <input class="header_check" type="checkbox" id="section-components-{%=suffix%}">

        <label for="section-components-{%=suffix%}">
            <div class="header-arrow"></div>
        </label>

        <div class="tree">
            <ol>
                <!--
                <li data-component="Products" class="file">
                    <label for="idNaN" style="background-image:url(/js/vvvebjs/icons/products.svg)"><span>Products</span></label>
                    <input type="checkbox" id="idNaN">
                </li>
                <li data-component="Posts" class="file">
                    <label for="idNaN" style="background-image:url(/js/vvvebjs/icons/posts.svg)"><span>Posts</span></label>
                    <input type="checkbox" id="idNaN">
                </li>
                -->
            </ol>
        </div>
    </div>

</script>
<!--// end templates -->


<!-- code editor modal -->
<div class="modal modal-full fade" id="codeEditorModal" tabindex="-1" aria-labelledby="codeEditorModal" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <input type="hidden" name="file" value="">

            <div class="modal-header justify-content-between">
                <span class="modal-title"></span>
                <div class="float-end">
                    <button type="button" class="btn btn-light border btn-icon" data-bs-dismiss="modal">
                        <i class="la la-times"></i>
                        Close
                    </button>

                    <button class="btn btn-primary btn-icon save-btn" title="Save changes">
                        <span class="loading d-none">
                            <?php loadsvg("img/icon-save.svg") ?>
                            <span class="spinner-border spinner-border-sm align-middle" role="status"
                                  aria-hidden="true"></span>
                            <span>Saving </span> ...
                        </span>
                        <span class="button-text">
                            <?php loadsvg("img/icon-save.svg") ?>
                            <span>Save changes</span>
                        </span>
                    </button>
                </div>
            </div>

            <div class="modal-body p-0">
                <textarea class="form-control h-100"></textarea>
            </div>
        </div>
    </div>
</div>

<!-- export html modal-->
<div class="modal fade" id="textarea-modal" tabindex="-1" role="dialog" aria-labelledby="textarea-modal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title text-primary"><i class="la la-lg la-save"></i> Export html</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <!-- span aria-hidden="true"><small><i class="la la-times"></i></small></span -->
                </button>
            </div>
            <div class="modal-body">

                <textarea rows="25" cols="150" class="form-control"></textarea>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal"><i
                            class="la la-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- message modal-->
<div class="modal fade" id="message-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title text-primary"><i class="la la-lg la-comment"></i> VvvebJs</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <!-- span aria-hidden="true"><small><i class="la la-times"></i></small></span -->
                </button>
            </div>
            <div class="modal-body">
                <p>Page was successfully saved!.</p>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary">Ok</button> -->
                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal"><i
                            class="la la-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- save toast -->
<div class="toast-container position-fixed end-0 bottom-0 me-3 mb-3" id="top-toast">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header text-white">
            <strong class="me-auto">Page save</strong>
            <button type="button" class="btn-close text-white px-2" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body ">
            <div class="flex-grow-1">
                <div class="message"></div>
            </div>
        </div>
    </div>
</div>

<!-- bootstrap-->
<script src="js/popper.js"></script>
<script src="js/bootstrap.js"></script>

<!-- builder code-->
<script src="libs/builder/builder.js"></script>
<!-- undo manager-->
<script src="libs/builder/undo.js"></script>
<!-- inputs-->
<script src="libs/builder/inputs.js"></script>


<!-- media gallery -->
<link href="libs/media/media.css" rel="stylesheet">
<script>
    Vvveb.uploadUrl = 'files.php?page=<?php echo $page ?>&id=<?php echo $id ?>&link=<?php echo $link ?>';
    Vvveb.themeBaseUrl = '_sections/';
</script>
<script src="libs/media/media.js"></script>

<script src="libs/builder/plugin-media.js"></script>

<!-- bootstrap colorpicker //uncomment bellow scripts to enable
<script src="libs/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<link href="libs/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet">
<script src="libs/builder/plugin-bootstrap-colorpicker.js"></script>
-->

<!-- components-->
<script src="libs/builder/components-server.js"></script>
<script src="libs/builder/plugin-google-fonts.js"></script>
<script src="libs/builder/components-common.js"></script>
<script src="libs/builder/plugin-aos.js"></script>
<script src="libs/builder/components-html.js"></script>
<script src="libs/builder/components-elements.js"></script>
<script src="libs/builder/section.js"></script>
<script src="libs/builder/components-bootstrap5.js"></script>
<script src="libs/builder/components-widgets.js"></script>
<script src="libs/builder/oembed.js"></script>
<script src="libs/builder/components-embeds.js"></script>

<!-- sections-->
<script>
    <?php
    $pastas = glob("./_sections/*");
    foreach ($pastas as $pasta) {
        $files = glob("{$pasta}/*.html");
        $groups = [];
        foreach ($files as $file) {
            $html = file_get_contents($file);
            $html = str_replace("{wwwroot}", $CFG->wwwroot, $html);
            $html = vvveb__changue_langs($html);
            $html = vvveb__change_courses($html);

            preg_match('/\/([a-z0-9\-]*)\/([a-z0-9\-]*)\.html/', $file, $info);
            $name = ucfirst(str_replace("-", " ", $info[2]));
            $groupname = ucfirst(str_replace("-", " ", $info[1]));;
            $groups[] = "{$info[1]}/{$info[2]}";
            echo "
                Vvveb.Sections.add('{$info[1]}/{$info[2]}', {
                    name  : '{$name}',
                    image : '_sections/{$info[1]}/{$info[2]}.jpg',
                    html  : `{$html}`
                });";
        }

        if (isset($groups[0])) {
            $group = implode("', '", $groups);
            echo "
                Vvveb.SectionsGroup['{$groupname}'] = [ '{$group}' ];\n\n\n";
        }
    }?>
</script>
<script src="libs/builder/sections-bootstrap4.js"></script>

<!-- blocks-->
<script src="libs/builder/blocks-bootstrap4.js"></script>


<!-- plugins -->

<!-- code mirror - code editor syntax highlight -->
<link href="libs/codemirror/lib/codemirror.css" rel="stylesheet"/>
<link href="libs/codemirror/theme/material.css" rel="stylesheet"/>
<script src="libs/codemirror/lib/codemirror.js"></script>
<script src="libs/codemirror/lib/xml.js"></script>
<script src="libs/codemirror/lib/css.js"></script>
<script src="libs/codemirror/lib/formatting.js"></script>
<script src="libs/builder/plugin-codemirror.js"></script>


<!--
Tinymce plugin
Clone or copy https://github.com/tinymce/tinymce-dist to libs/tinymce-dist
-->
<script src="libs/tinymce-dist/tinymce.js"></script>
<script src="libs/builder/plugin-tinymce.js"></script>


<!-- autocomplete plugin used by autocomplete input
<script src="libs/autocomplete/jquery.autocomplete.js"></script>
-->
<script>
    let renameUrl = 'save.php?action=rename&page=<?php echo $page ?>&id=<?php echo $id ?>&link=<?php echo $link ?>';
    let deleteUrl = 'save.php?action=delete&page=<?php echo $page ?>&id=<?php echo $id ?>&link=<?php echo $link ?>';
    let oEmbedProxyUrl = 'save.php?action=oembedProxy&page=<?php echo $page ?>&id=<?php echo $id ?>&link=<?php echo $link ?>';

    var url = "loadpage.php?page=<?php echo $page ?>&id=<?php echo $id ?>&link=<?php echo $link ?>";
    Vvveb.Builder.init(url, function() {
        Vvveb.SectionList.loadSections(false);
        Vvveb.TreeList.loadComponents();
        Vvveb.StyleManager.init();
    });

    Vvveb.Gui.init();
    Vvveb.SectionList.init();
    Vvveb.TreeList.init();
    Vvveb.Breadcrumb.init();
    Vvveb.CssEditor.init();
    Vvveb.Breadcrumb.init();
</script>
</body>
</html>
