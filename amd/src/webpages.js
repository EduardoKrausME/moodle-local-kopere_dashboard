define([
    "jquery"
], function($) {
    return {

        webpages_page_ajax_get_url : function() {
            $('#title').focusout(function() {
                var url = M.cfg.wwwroot + "/local/kopere_dashboard/load-ajax.php?classname=webpages&method=page_ajax_get_url";
                var postData = {
                    title : $(this).val(),
                    id    : $('#id').val()
                };
                $.post(url, postData, function(data) {
                    $('#link').val(data);
                    $('#theme').focus();
                }, 'text');
            });
        },

        webpages_menu_ajax_get_url : function() {
            $('#title').focusout(function() {
                var url = M.cfg.wwwroot + "/local/kopere_dashboard/load-ajax.php?classname=webpages&method=menu_ajax_get_url";
                var postData = {
                    title : $(this).val(),
                    id    : $('#id').val()
                };
                $.post(url, postData, function(data) {
                    $('#link').val(data).focus();
                }, 'text');
            });
        },

    };
});
