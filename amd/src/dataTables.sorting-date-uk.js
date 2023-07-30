/**
 * DataTables internal date sorting replies on `Date.parse()` which is part of
 * the Javascript language, but you may wish to sort on dates which is doesn't
 * recognise. The following is a plug-in for sorting dates in the format
 * `dd/mm/yy`.
 *
 * An automatic type detection plug-in is available for this sorting plug-in.
 *
 * Please note that this plug-in is **deprecated*. The
 * [datetime](//datatables.net/blog/2014-12-18) plug-in provides enhanced
 * functionality and flexibility.
 *
 *  @name Date (dd/mm/YY)
 *  @summary Sort dates in the format `dd/mm/YY`
 *  @author Andy McMaster
 *  @deprecated
 *
 *  @example
 *    $('#example').dataTable( {
 *       columnDefs: [
 *         { type: 'date-uk', targets: 0 }
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

    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-uk-pre" : function(a) {
            if (a == null || a == "") {
                return 0;
            }
            var ukDatea = a.split('/');
            return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
        },

        "date-uk-asc" : function(a, b) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "date-uk-desc" : function(a, b) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    });

}));