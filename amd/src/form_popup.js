define([
    "jquery",
    "local_kopere_dashboard/daterangepicker",
    "local_kopere_dashboard/maskedinput",
    "local_kopere_dashboard/validate",
], function($, daterangepicker, validator) {
    return {
        init : function() {

            console.log("form_popup.js");

            if ($(".modal-content input.single-daterange").length) {
                $(".modal-content input.single-daterange").daterangepicker({
                    locale                : {
                        format           : 'DD/MM/YYYY',
                        separator        : ' - ',
                        applyLabel       : 'Aplicar',
                        cancelLabel      : 'Cancelar',
                        fromLabel        : 'De',
                        toLabel          : 'Para',
                        customRangeLabel : 'Custom',
                        daysOfWeek       : ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                        monthNames       : ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                        firstDay         : 1
                    },
                    "singleDatePicker"    : true,
                    "showDropdowns"       : true,
                    "autoApply"           : true,
                    "alwaysShowCalendars" : true
                });
            }
            if ($(".modal-content input.single-datetimerange").length) {
                console.log("single-datetimerange eba");
                $(".modal-content input.single-datetimerange").daterangepicker({
                    locale                : {
                        format           : 'DD/MM/YYYY HH:mm',
                        separator        : ' - ',
                        applyLabel       : 'Aplicar',
                        cancelLabel      : 'Cancelar',
                        fromLabel        : 'De',
                        toLabel          : 'Para',
                        customRangeLabel : 'Custom',
                        daysOfWeek       : ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                        monthNames       : ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                        firstDay         : 1
                    },
                    "singleDatePicker"    : true,
                    "showDropdowns"       : true,
                    "timePicker"          : true,
                    "timePicker24Hour"    : true,
                    "autoApply"           : true,
                    "alwaysShowCalendars" : true
                });
            }

            if ($(".modal-content form.validate").length) {
                $(".modal-content form.validate").validator();
            }

            $('.modal-content .button-actions').click(function(event) {
                event.stopImmediatePropagation();
            });
        }
    };
});



