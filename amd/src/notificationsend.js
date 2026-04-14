define(["jquery"], function($) {
    var notificationsend = {

        list_users: function() {
            $("#course").change(notificationsend.create_link);
            $('.notificationsend_criteria input, .notificationsend_criteria select')
                .change(notificationsend.create_link);

            notificationsend.create_link();
        },

        create_link: function() {
            var course_id = $("#course").val();

            let params = new URLSearchParams();
            $('.notificationsend_criteria input, .notificationsend_criteria select').each(function () {
                let name = $(this).attr('name');
                let value = $(this).val();
                if (value) {
                    params.set(name, value);
                }
            });

            var link = `
                    <a href="?classname=notificationsend&method=list_users&course_id=${course_id}&${params.toString()}" 
                       target="notificationsend"
                       class="btn btn-info">
                        ${M.util.get_string("notificationsend_viewstudentswithcriteria", "local_kopere_dashboard")}
                    </a>`;

            $("#notificationsend-link").html(link);
        }
    };

    return notificationsend;
});
