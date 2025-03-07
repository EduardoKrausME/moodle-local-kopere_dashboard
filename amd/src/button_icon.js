define(["jquery", "jqueryui"], function($) {
    return {
        action: function(button_id, link) {
            $("#btn-" + button_id).show().click(function() {
                $("#confirm-" + button_id).dialog({
                    resizable: false,
                    height: "auto",
                    width: "auto",
                    maxWidth: 400,
                    modal: true,
                    classes: {
                        "ui-dialog": "kopere-dashboard-modal"
                    },
                    show: {
                        effect: "blind",
                        duration: 200
                    },
                    hide: {
                        duration: 300
                    },
                    buttons: [
                        {
                            "text": M.util.get_string("yes"),
                            "class": "btn btn-danger",
                            "click": function() {
                                location.href = link;
                            }
                        },
                        {
                            text: M.util.get_string("yes"),
                            click: function() {
                                $(this).dialog("close");
                            }
                        }
                    ]
                });
            });
        }
    };
});
