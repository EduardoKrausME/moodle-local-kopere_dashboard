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
 * TinyMCE version 7.3.0 (2024-08-07)
 */

(function() {
    'use strict';

    var global$2 = tinymce.util.Tools.resolve('tinymce.PluginManager');

    const isSimpleType = type => value => typeof value === type;
    const isFunction = isSimpleType('function');

    var global$1 = tinymce.util.Tools.resolve('tinymce.dom.DOMUtils');

    var global = tinymce.util.Tools.resolve('tinymce.util.Tools');

    const option = name => editor => editor.options.get(name);
    const register$2 = editor => {
        const registerOption = editor.options.register;
        registerOption('save_enablewhendirty', {
            processor : 'boolean',
            default   : true
        });
        registerOption('save_onsavecallback', {processor : 'function'});
        registerOption('save_oncancelcallback', {processor : 'function'});
    };
    const enableWhenDirty = option('save_enablewhendirty');
    const getOnSaveCallback = option('save_onsavecallback');
    const getOnCancelCallback = option('save_oncancelcallback');

    const displayErrorMessage = (editor, message) => {
        editor.notificationManager.open({
            text : message,
            type : 'error'
        });
    };
    const save = editor => {
        Vvveb.WysiwygEditor.saveandclose();

        // const formObj = global$1.DOM.getParent(editor.id, 'form');
        // if (enableWhenDirty(editor) && !editor.isDirty()) {
        //     return;
        // }
        // editor.save();
        // const onSaveCallback = getOnSaveCallback(editor);
        // if (isFunction(onSaveCallback)) {
        //     onSaveCallback.call(editor, editor);
        //     editor.nodeChanged();
        //     return;
        // }
        // if (formObj) {
        //     editor.setDirty(false);
        //     if (!formObj.onsubmit || formObj.onsubmit()) {
        //         if (typeof formObj.submit === 'function') {
        //             formObj.submit();
        //         } else {
        //             displayErrorMessage(editor, 'Error: Form submit field collision.');
        //         }
        //     }
        //     editor.nodeChanged();
        // } else {
        //     displayErrorMessage(editor, 'Error: No form element found.');
        // }
    };
    const cancel = editor => {
        const h = global.trim(editor.startContent);
        const onCancelCallback = getOnCancelCallback(editor);
        if (isFunction(onCancelCallback)) {
            onCancelCallback.call(editor, editor);
            return;
        }
        editor.resetContent(h);
    };

    const register$1 = editor => {
        editor.addCommand('mceSave', () => {
            save(editor);
        });
        editor.addCommand('mceCancel', () => {
            cancel(editor);
        });
    };

    const stateToggle = editor => api => {
        const handler = () => {
            api.setEnabled(true);
            //api.setEnabled(!enableWhenDirty(editor) || editor.isDirty());
        };
        handler();
        editor.on('NodeChange dirty', handler);
        return () => editor.off('NodeChange dirty', handler);
    };
    const register = editor => {
        editor.ui.registry.addButton('save', {
            icon     : 'save',
            tooltip  : 'Save',
            enabled  : false,
            onAction : () => editor.execCommand('mceSave'),
            onSetup  : stateToggle(editor),
            shortcut : 'Meta+S'
        });
        editor.ui.registry.addButton('cancel', {
            icon     : 'cancel',
            tooltip  : 'Cancel',
            enabled  : false,
            onAction : () => editor.execCommand('mceCancel'),
            onSetup  : stateToggle(editor)
        });
        editor.addShortcut('Meta+S', '', 'mceSave');
    };

    var Plugin = () => {
        global$2.add('save', editor => {
            register$2(editor);
            register(editor);
            register$1(editor);
        });
    };

    Plugin();

})();
