define(["jquery"], function($) {
    return {
        courses_enrol_new : function() {
            $(".bt-courses_enrol_new").click(function() {

                $(".table-new-enrol").show(300);
                $(".table-list-enrol").hide(300);

                $(".usuarioemail").focus();
            })
        }
    };
});
