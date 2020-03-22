define([
    "jquery",
    "local_kopere_dashboard/dataTables",
    "local_kopere_dashboard/dataTables.sorting-currency",
    "local_kopere_dashboard/dataTables.sorting-date-uk",
    "local_kopere_dashboard/dataTables.sorting-file-size",
    "local_kopere_dashboard/dataTables.sorting-numeric-comma"
], function($, datatables) {
    return {
        init : function(selector, params) {

            var renderer = {
                dataVisibleRenderer   : function(data, type, row) {
                    if (data == 0) {
                        return '<div class="status-pill grey"  title="' + lang_invisible + '"  data-toggle="tooltip"></div>';
                    } else {
                        return '<div class="status-pill green" title="' + lang_visible + '" data-toggle="tooltip"></div>';
                    }
                },
                dataStatusRenderer    : function(data, type, row) {
                    if (data == 1) {
                        return '<div class="status-pill grey"  title="' + lang_inactive + '" data-toggle="tooltip"></div>';
                    } else {
                        return '<div class="status-pill green" title="' + lang_active + '"   data-toggle="tooltip"></div>';
                    }
                },
                centerRenderer        : function(data, type, row) {
                    return '<div class="text-center" data-toggle="tooltip">' + data + '</div>';
                },
                currencyRenderer      : function(data, type, row) {
                    return '<div class="text-center" data-toggle="tooltip">R$ ' + data + '</div>';
                },
                dataDatetimeRenderer  : function(data, type, row) {
                    function twoDigit($value) {
                        if ($value < 10) {
                            return '0' + $value;
                        }
                        return $value;
                    }

                    var a = new Date(data * 1000);
                    var year = a.getFullYear();
                    var month = twoDigit(a.getMonth() + 1);
                    var date = twoDigit(a.getDate());
                    var hour = twoDigit(a.getHours());
                    var min = twoDigit(a.getMinutes());

                    return date + '/' + month + '/' + year + ' ' + hour + ':' + min;
                },
                dataTrueFalseRenderer : function(data, type, row) {
                    if (data == 0 || data == false || data == 'false') {
                        return lang_no;
                    } else {
                        return lang_yes;
                    }
                },
                rendererFilesize      : function(data, type, row) {
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
            };

            var newColumnDefs = [];
            $.each(params.columnDefs, function(id, columnDef) {
                switch (columnDef.render) {
                    case "centerRenderer":
                        columnDef.render = renderer.centerRenderer;
                        break;
                    case "currencyRenderer":
                        columnDef.render = renderer.currencyRenderer;
                        break;
                    case "rendererFilesize":
                        columnDef.render = renderer.rendererFilesize;
                        break;
                    case "dataDatetimeRenderer":
                        columnDef.render = renderer.dataDatetimeRenderer;
                        break;
                    case "dataVisibleRenderer":
                        columnDef.render = renderer.dataVisibleRenderer;
                        break;
                    case "dataStatusRenderer":
                        columnDef.render = renderer.dataStatusRenderer;
                        break;
                    case "dataTrueFalseRenderer":
                        columnDef.render = renderer.dataTrueFalseRenderer;
                        break;
                }
                newColumnDefs.push(columnDef);
            });
            params.columnDefs = newColumnDefs;
            params.oLanguage = dataTables_oLanguage;

            window[selector] = $("#" + selector).DataTable(params);
        },

        click : function(selector, clickchave, clickurl) {
            $('#' + selector + ' tbody').on('click', 'tr', function() {
                var data = window[selector].row(this).data();

                $.each(clickchave, function(id, chave) {
                    clickurl = clickurl.replace('{' + chave + '}', data[chave]);
                });

                location.href = clickurl;
            });
        }
    };
});



