define(["jquery", "jqueryui"], function($, ui) {
    return {

        load_pages : function() {

            function webpages_menu_open_click() {

                $(".webpages_menu_open:not(.loaded)")
                    .show()
                    .addClass('loaded')
                    .click(function() {
                        var menu = $(this);
                        var menuid = menu.attr('data-id');

                        if (menu.hasClass("webpages_menu_opened")) {
                            menu.removeClass("webpages_menu_opened");

                            $("#menuid-" + menuid).remove();

                            var srcopen = menu.attr("src-open");
                            menu.attr("src", srcopen);
                        } else {
                            menu.addClass("webpages_menu_opened");

                            var srcclose = menu.attr("src-close");
                            menu.attr("src", srcclose);
                            $("#menuid-" + menuid).remove();
                            var img = "<img src='" + M.cfg.wwwroot + "/local/kopere_dashboard/assets/dashboard/img/loading2.svg' " +
                                "           style='height:21px;'>";
                            menu.parent().parent().after("<tr id='menuid-" + menuid + "'><td colspan='4'>" + img + "</td></tr>");

                            var url = M.cfg.wwwroot + "/local/kopere_dashboard/view-ajax.php?classname=webpages&method=menu_get_itens";
                            $.post(
                                url, {
                                    menuid : menuid
                                }, function(data) {
                                    $("#menuid-" + menuid + " td").html(data);

                                    webpages_menu_open_click();
                                }, 'text');
                        }
                    });
            }

            webpages_menu_open_click();

        },

        webpages_page_ajax_get_url : function() {
            $('#title').focusout(function() {
                var url = M.cfg.wwwroot + "/local/kopere_dashboard/view-ajax.php?classname=webpages&method=page_ajax_get_url";
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
                var url = M.cfg.wwwroot + "/local/kopere_dashboard/view-ajax.php?classname=webpages&method=menu_ajax_get_url";
                var postData = {
                    title : $(this).val(),
                    id    : $('#id').val()
                };
                $.post(url, postData, function(data) {
                    $('#link').val(data).focus();
                }, 'text');
            });
        },

        jqueryui : function() {
            $(".jquery-ui-accordion").accordion();
            $(".jquery-ui-tabs").tabs();
        }
    };
});
