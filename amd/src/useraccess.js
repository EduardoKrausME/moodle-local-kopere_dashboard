define([
    "jquery"
], function($) {
    return {
        useraccess_changue_mes : function() {
            $('.bloco_changue_mes').show(300);
            $('#changue_mes').change(function() {
                location.href = "?classname=useraccess&method=dashboard&changue_mes=" + $('#changue_mes').val()
            });
        },
    };
});
