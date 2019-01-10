/**
 * Created by kraus on 16/05/17.
 */

function dataVisibleRenderer(data, type, row) {
    if (data == 0) {
        return '<div class="status-pill grey"  title="' + lang_invisible + '"  data-toggle="tooltip"></div>';
    } else {
        return '<div class="status-pill green" title="' + lang_visible + '" data-toggle="tooltip"></div>';
    }
}

function dataStatusRenderer(data, type, row) {
    if (data == 1) {
        return '<div class="status-pill grey"  title="' + lang_inactive + '" data-toggle="tooltip"></div>';
    } else {
        return '<div class="status-pill green" title="' + lang_active + '"   data-toggle="tooltip"></div>';
    }
}

function centerRenderer(data, type, row) {
    return '<div class="text-center" data-toggle="tooltip">' + data + '</div>';
}

function currencyRenderer (data, type, row) {
    return '<div class="text-center" data-toggle="tooltip">R$ ' + data + '</div>';
}

function dataDatetimeRenderer(data, type, row) {
    var a = new Date(data * 1000);
    var year = a.getFullYear();
    var month = twoDigit(a.getMonth() + 1);
    var date = twoDigit(a.getDate());
    var hour = twoDigit(a.getHours());
    var min = twoDigit(a.getMinutes());

    return date + '/' + month + '/' + year + ' ' + hour + ':' + min;
}

function twoDigit($value) {
    if ($value < 10) {
        return '0' + $value;
    }
    return $value;
}

function dataTrueFalseRenderer(data, type, row) {
    if (data == 0 || data == false || data == 'false') {
        return lang_no;
    } else {
        return lang_yes;
    }
}
