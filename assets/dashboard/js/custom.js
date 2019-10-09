/**
 * Created by kraus on 15/05/17.
 */

$(function() {
    startForm();
});

function startForm() {

    if ($("input.single-daterange").length) {
        $("input.single-daterange").daterangepicker({
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
    if ($("input.single-datetimerange").length) {
        $("input.single-datetimerange").daterangepicker({
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

    if ($(".select2").length) {
        $(".select2").select2();
    }

    if ($("form.validate").length) {
        $("form.validate").validator();
    }

    $('[data-toggle="popover"]').popover();

    $('.button-actions').click(function(event) {
        event.stopImmediatePropagation();
    });

    mackInputs();
    loadValidateAll();
}


function loadRemoteModal(url) {
    event.stopImmediatePropagation();

    $target = $('#modal-edit');

    $target.modal();
    $target.find('.modal-content').load(url);

    $target.one('hidden.bs.modal', function() {
        $('.modal-content').html('<div class="loader"></div>');
    });
}