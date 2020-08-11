/**
 * When dealing with computer file sizes, it is common to append a post fix
 * such as B, KB, MB or GB to a string in order to easily denote the order of
 * magnitude of the file size. This plug-in allows sorting to take these
 * indicates of size into account.
 *
 * A counterpart type detection plug-in is also available.
 *
 *  @name File size
 *  @summary Sort abbreviated file sizes correctly (8MB, 4KB, etc)
 *  @author Allan Jardine - datatables.net
 *
 *  @example
 *    $('#example').DataTable( {
 *       columnDefs: [
 *         { type: 'file-size', targets: 0 }
 *       ]
 *    } );
 */

/* jshint unused:false, newcap:false, maxlen:10000 */
/* eslint-disable */
/* globals require:false, jQuery:false */

(function(factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery', 'local_kopere_dashboard/dataTables'], function($) {
            return factory($, window, document);
        });
    }
    else if (typeof exports === 'object') {
        // CommonJS
        module.exports = function(root, $) {
            if (!root) {
                root = window;
            }

            if (!$ || !$.fn.dataTable) {
                $ = require('local_kopere_dashboard/dataTables')(root, $).$;
            }

            return factory($, root, root.document);
        };
    }
    else {
        // Browser
        factory(jQuery, window, document);
    }
}(function($, window, document, undefined) {
    'use strict';

    jQuery.fn.dataTable.ext.type.order['file-size-pre'] = function(data) {
        var matches = data.match(/^(\d+(?:\.\d+)?)\s*([a-z]+)/i);
        var multipliers = {
            b     : 1,
            bytes : 1,
            kb    : 1000,
            kib   : 1024,
            mb    : 1000000,
            mib   : 1048576,
            gb    : 1000000000,
            gib   : 1073741824,
            tb    : 1000000000000,
            tib   : 1099511627776,
            pb    : 1000000000000000,
            pib   : 1125899906842624
        };

        if (matches) {
            var multiplier = multipliers[matches[2].toLowerCase()];
            return parseFloat(matches[1]) * multiplier;
        } else {
            return -1;
        }
    };

    function rendererFilesize(data, type, row) {
        if (data == null)
            return '0 b';

        if (data < 1000)
            return data + ' b';

        if (data < 1000 * 1000) {
            data = data / (1000);
            return data.toFixed(2) + ' Kb';
        }
        if (data < 1000 * 1000 * 1000) {
            data = data / (1000 * 1000);
            return data.toFixed(2) + ' Mb';
        }
        if (data < 1000 * 1000 * 1000 * 1000) {
            data = data / (1000 * 1000 * 1000);
            return data.toFixed(2) + ' Gb';
        }
        if (data < 1000 * 1000 * 1000 * 1000 * 1000) {
            data = data / (1000 * 1000 * 1000 * 1000);
            return data.toFixed(2) + ' Tb';
        }
    }

}));