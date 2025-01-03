define(["jquery"], function($) {
    return {

        notifications_add_form_extra: function() {
            $('#module').change(function() {
                var data = {
                    module: $(this).val()
                };

                var url = M.cfg.wwwroot + "/local/kopere_dashboard/view-ajax.php?classname=notificationsutil&method=add_form_extra";
                $('#restante-form').load(url, data);
            });
        },

        notifications_settings_load_template: function() {

            var loaded_data = {tags: {}};

            function replace_tags(content) {

                content = content.split('{[moodle.fullname]}').join(loaded_data.tags.moodle_fullname);
                content = content.split('{[moodle.shortname]}').join(loaded_data.tags.moodle_shortname);
                content = content.split('{[message]}').join(loaded_data.tags.message);
                content = content.split('{[date.year]}').join(loaded_data.tags.date_year);
                content = content.split('{[manager]}').join(loaded_data.tags.manager);

                return content;
            }

            $('#notificacaotemplate').change(notificacaotemplate_change);

            function notificacaotemplate_change() {
                var template = $('#notificacaotemplate').val();
                var url = M.cfg.wwwroot + "/local/kopere_dashboard/view-ajax.php?classname=notifications&method=settings_load_template&template=" + template;

                $.getJSON(url, function(data) {
                    loaded_data = data;
                    $('#area-mensagem-preview').html(replace_tags(data.content));
                    $('#notificacaotemplatehtml').val(data.content);
                });
            }

            notificacaotemplate_change();

            $('#notificacaotemplatehtml')
                .on("change", function() {
                    $('#area-mensagem-preview').html(replace_tags($('#notificacaotemplatehtml').val()));
                })
                .on("keyup", function() {
                    $('#area-mensagem-preview').html(replace_tags($('#notificacaotemplatehtml').val()));
                });
        },
    };
});
