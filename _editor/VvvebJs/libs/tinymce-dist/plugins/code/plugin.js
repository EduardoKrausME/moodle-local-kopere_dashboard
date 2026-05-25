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
 * plugin.js
 *
 * @package   local_kopere_dashboard
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * TinyMCE version 7.5.1 (TBD)
 */

(function() {
    'use strict';

    var global = tinymce.util.Tools.resolve('tinymce.PluginManager');

    const setContent = (editor, html) => {
        editor.focus();
        editor.undoManager.transact(() => {
            editor.setContent(html);
        });
        editor.selection.setCursorLocation();
        editor.nodeChanged();
    };
    const getContent = editor => {
        return editor.getContent({source_view : true});
    };

    const open = editor => {
        const editorContent = getContent(editor);
        editor.windowManager.open({
            title       : 'Source Code',
            size        : 'large',
            body        : {
                type  : 'panel',
                items : [{
                    type : 'textarea',
                    name : 'code'
                }]
            },
            buttons     : [
                {
                    type : 'cancel',
                    name : 'cancel',
                    text : 'Cancel'
                },
                {
                    type    : 'submit',
                    name    : 'save',
                    text    : 'Save',
                    primary : true
                }
            ],
            initialData : {code : editorContent},
            onSubmit    : api => {
                setContent(editor, api.getData().code);
                api.close();
            }
        });
    };

    const register$1 = editor => {
        editor.addCommand('mceCodeEditor', () => {
            open(editor);
        });
    };

    const register = editor => {
        const onAction = () => editor.execCommand('mceCodeEditor');
        editor.ui.registry.addButton('code', {
            icon    : 'sourcecode',
            tooltip : 'Source code',
            onAction
        });
        editor.ui.registry.addMenuItem('code', {
            icon : 'sourcecode',
            text : 'Source code',
            onAction
        });
    };

    var Plugin = () => {
        global.add('code', editor => {
            register$1(editor);
            register(editor);
            return {};
        });
    };

    Plugin();

})();
