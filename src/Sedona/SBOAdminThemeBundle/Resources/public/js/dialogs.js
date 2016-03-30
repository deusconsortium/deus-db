;(function($, window){
    "use strict";

    var doConfirm = function(event) {
        var $t = $(event.currentTarget) , msg = $t.attr('data-confirm'), href = $t.attr('href');
        event.preventDefault();
        bootbox.confirm(msg, function(result){
            if(result === true) {
                window.location = href;
            }
        });
    },
    doConfirmButton = function(event) {
        var $t = $(event.currentTarget) , msg = $t.attr('data-confirm');
        return confirm(msg);
    },
    onInit = function(){
        $('a[data-confirm]').off('click').on('click', doConfirm);
        $('button[data-confirm]').on('click', doConfirmButton);
    };

    onInit();
    $(window).on('ajax.reloaded', onInit);

})(jQuery, window);