define([
    "jquery"
], function($) {
    return {

        useraccess_changue_mes : function() {
            $('.area_changue_mes').show();
            $('#changue_mes').change(function() {
                location.href = "?classname=useraccess&method=dashboard&changue_mes=" + $('#changue_mes').val()
            });
        },

        notifications_add_form_extra : function() {
            $('#module').change(function() {
                var data = {
                    module : $(this).val()
                };
                $('#restante-form').load('load-ajax.php?classname=notificationsutil&method=add_form_extra', data);
            });
        },

        notifications_settings_load_template : function() {
            $('#notificacao-template').change(notificacao_template_change);

            function notificacao_template_change() {
                var data = {
                    template : $('#notificacao-template').val()
                };
                $('#area-mensagem-preview').load('load-ajax.php?classname=notifications&method=settings_load_template', data);
            }

            notificacao_template_change();
        },

        userenrolment_status : function() {
            function timeend_status_change(delay) {
                if (delay != 0) {
                    delay = 400;
                }

                if ($('#timeend-status').val() == 1) {
                    $('.area_timeend').show(delay);
                } else {
                    $('.area_timeend').hide(delay);
                }
            }

            function status_change(delay) {
                if (delay != 0) {
                    delay = 400;
                }

                if ($('#status').val() == 0) {
                    $('.area-inscricao-times').show(delay);
                } else {
                    $('.area-inscricao-times').hide(delay);
                }
            }

            timeend_status_change(0);
            status_change(0);

            $('#timeend-status').change(timeend_status_change);
            $('#status').change(status_change);
        },

        useronline_status : function() {
            function timeend_status_change(delay) {
                if (delay != 0) {
                    delay = 400;
                }
                if ($('#timeend-status').val() == 1) {
                    $('.area_timeend').show(delay);
                } else {
                    $('.area_timeend').hide(delay);
                }
            }

            function status_change(delay) {
                if (delay != 0) {
                    delay = 400;
                }
                if ($('#status').val() == 0) {
                    $('.area-inscricao-times').show(delay);
                } else {
                    $('.area-inscricao-times').hide(delay);
                }
            }

            timeend_status_change(0);
            status_change(0);

            $('#timeend-status').change(timeend_status_change);
            $('#status').change(status_change);
        },

        webpages_page_ajax_get_url : function() {
            $('#title').focusout(function() {
                var url = 'load-ajax.php?classname=webpages&method=page_ajax_get_url';
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
                var url = 'load-ajax.php?classname=webpages&method=menu_ajax_get_url';
                var postData = {
                    title : $(this).val(),
                    id    : $('#id').val()
                };
                $.post(url, postData, function(data) {
                    $('#link').val(data).focus();
                }, 'text');
            });
        },

        animate_scrollTop : function(key) {
            jQuery('html,body').animate({scrollTop : $(key).offset().top}, 0);
        },

        form_close_and_auto_submit_input : function(campo) {
            $('#' + campo).change(function() {
                $('#submit_' + campo).click();
            });
        }

    };
});

