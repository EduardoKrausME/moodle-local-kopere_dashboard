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

    const Cell = initial => {
        let value = initial;
        const get = () => {
            return value;
        };
        const set = v => {
            value = v;
        };
        return {
            get,
            set
        };
    };

    var global = tinymce.util.Tools.resolve('tinymce.PluginManager');

    const fireVisualBlocks = (editor, state) => {
        editor.dispatch('VisualBlocks', {state});
    };

    const toggleVisualBlocks = (editor, pluginUrl, enabledState) => {
        const dom = editor.dom;
        dom.toggleClass(editor.getBody(), 'mce-visualblocks');
        enabledState.set(!enabledState.get());
        fireVisualBlocks(editor, enabledState.get());
    };

    const register$2 = (editor, pluginUrl, enabledState) => {
        editor.addCommand('mceVisualBlocks', () => {
            toggleVisualBlocks(editor, pluginUrl, enabledState);
        });
    };

    const option = name => editor => editor.options.get(name);
    const register$1 = editor => {
        const registerOption = editor.options.register;
        registerOption('visualblocks_default_state', {
            processor : 'boolean',
            default   : false
        });
    };
    const isEnabledByDefault = option('visualblocks_default_state');

    const setup = (editor, pluginUrl, enabledState) => {
        editor.on('PreviewFormats AfterPreviewFormats', e => {
            if (enabledState.get()) {
                editor.dom.toggleClass(editor.getBody(), 'mce-visualblocks', e.type === 'afterpreviewformats');
            }
        });
        editor.on('init', () => {
            if (isEnabledByDefault(editor)) {
                toggleVisualBlocks(editor, pluginUrl, enabledState);
            }
        });
    };

    const toggleActiveState = (editor, enabledState) => api => {
        api.setActive(enabledState.get());
        const editorEventCallback = e => api.setActive(e.state);
        editor.on('VisualBlocks', editorEventCallback);
        return () => editor.off('VisualBlocks', editorEventCallback);
    };
    const register = (editor, enabledState) => {
        const onAction = () => editor.execCommand('mceVisualBlocks');
        editor.ui.registry.addToggleButton('visualblocks', {
            icon    : 'visualblocks',
            tooltip : 'Show blocks',
            onAction,
            onSetup : toggleActiveState(editor, enabledState),
            context : 'any'
        });
        editor.ui.registry.addToggleMenuItem('visualblocks', {
            text    : 'Show blocks',
            icon    : 'visualblocks',
            onAction,
            onSetup : toggleActiveState(editor, enabledState),
            context : 'any'
        });
    };

    var Plugin = () => {
        global.add('visualblocks', (editor, pluginUrl) => {
            register$1(editor);
            const enabledState = Cell(false);
            register$2(editor, pluginUrl, enabledState);
            register(editor, enabledState);
            setup(editor, pluginUrl, enabledState);
        });
    };

    Plugin();

})();
