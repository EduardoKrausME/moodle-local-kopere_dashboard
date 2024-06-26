define([
    "jquery",
    "local_kopere_dashboard/dataTables",
    "local_kopere_dashboard/dataTables.sorting-currency",
    "local_kopere_dashboard/dataTables.sorting-date-uk",
    "local_kopere_dashboard/dataTables.sorting-file-size",
    "local_kopere_dashboard/dataTables.sorting-numeric-comma",
    "local_kopere_dashboard/dataTables.buttons",
    "local_kopere_dashboard/dataTables.buttons.html5",
    "local_kopere_dashboard/dataTables.buttons.print",
    "local_kopere_dashboard/dataTables.dateTime",
    "local_kopere_dashboard/dataTables.responsive",
    "local_kopere_dashboard/dataTables.select",
], function($) {
    return dataTables_init = {
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
                dataDateRenderer      : function(data, type, row) {
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

                    return date + '/' + month + '/' + year;
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
                },
                dataUserphotoRenderer : function(data, type, row) {
                    return '<img class="media-object" src="' + M.cfg.wwwroot +
                        '/local/kopere_dashboard/profile-image.php?type=photo_user&id=' + data +
                        '" style="width:35px;height:35px" />';
                },
                segundosRenderer      : function(data, type, row) {
                    var tempo = parseInt(data);
                    if (isNaN(tempo) || tempo < 1) {
                        return '00:00:00';
                    }

                    var min = parseInt(tempo / 60);
                    var hor = parseInt(min / 60);

                    min = min % 60;
                    if (min < 10) {
                        min = "0" + min;
                        min = min.substr(0, 2);
                    }

                    var seg = tempo % 60;
                    if (seg <= 9) {
                        seg = "0" + seg;
                    }

                    if (hor <= 9) {
                        hor = "0" + hor;
                    }

                    return hor + ':' + min + ':' + seg;
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
                    case "dataDateRenderer":
                        columnDef.render = renderer.dataDateRenderer;
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
                    case "dataUserphotoRenderer":
                        columnDef.render = renderer.dataUserphotoRenderer;
                        break;
                    case "segundosRenderer":
                        columnDef.render = renderer.segundosRenderer;
                        break;
                }
                newColumnDefs.push(columnDef);
            });
            params.columnDefs = newColumnDefs;
            params.oLanguage = dataTables_oLanguage;
            params.responsive = true;

            if (params.export_title) {
                params.buttons = [
                    {
                        extend : 'print',
                        text   : dataTables_oLanguage.buttons.print_text,
                        title  : params.export_title
                    },
                    //{
                    //     extend : 'copy',
                    //     text   : dataTables_oLanguage.buttons.copy_text,
                    //     title  : params.export_title
                    //},
                    {
                        extend : 'csv',
                        text   : dataTables_oLanguage.buttons.csv_text,
                        title  : params.export_title
                    }];
                params.dom = 'lfrtipB';
                params.select = true;
            }

            var count_error = 0;
            $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
                if (count_error < 20) {
                    var _processing = $("#" + selector + "_processing");
                    setTimeout(function() {
                        _processing.show().html(
                            "<div style='color:#e91e63'>" +
                            dataTables_oLanguage.sErrorMessage.replace("{$a}", "<span class='counter'>30</span>") +
                            "</div>");
                    }, 500);

                    var timer = 30;
                    var _inteval = setInterval(function() {
                        if (--timer <= 0) {
                            _processing.html(dataTables_oLanguage.sProcessing);
                            clearInterval(_inteval);
                            window[selector].ajax.reload();
                        }
                        _processing.find(".counter").html(timer);
                    }, 1000);
                }
                count_error++;
            };

            window[selector] = $("#" + selector).DataTable(params);

            var element = $("<div class='group-controls'>");
            var wrapper = $("#" + selector + "_wrapper");
            wrapper.prepend(element);
            wrapper.find(".dataTables_length").appendTo(element);
            wrapper.find(".dataTables_filter").appendTo(element);
        },

        click : function(selector, clickchave, clickurl) {
            $('#' + selector + ' tbody').on('click', 'tr', function() {
                var data = window[selector].row(this).data();
                dataTables_init._click_internal(data, clickchave, clickurl)
            });
        },

        _click_internal : function(data, clickchave, clickurl) {
            $.each(clickchave, function(id, chave) {
                clickurl = clickurl.replace('{' + chave + '}', data[chave]);
            });

            location.href = clickurl;
        }
    };
});
