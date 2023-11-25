define([
    "jquery"
], function($) {
    return {

        backup_animate_scrollTop : function(key) {
            jQuery('html,body').animate({scrollTop : $(key).offset().top}, 0);
        },

    };
});

