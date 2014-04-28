;"use strict";
$(function(){
    $('.message').each(function(){
        this.innerHTML = Autolinker.link(this.innerHTML);
    });
});
