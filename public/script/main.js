"use strict";

var App = {
    linkifyMessages : function(selectorOrigin) {
        if (selectorOrigin === undefined) {
            selectorOrigin = document.body;
        }

        $('.message', selectorOrigin).each(function(){
            this.innerHTML = Autolinker.link(this.innerHTML);
        });
    },

    loadBuffer : function(bufferId) {
        $('#messages-area').load('ajax/load-buffer/'+bufferId, null, function() {
            var $msgArea = $('#messages-area');

            $msgArea[0].scrollTop = $msgArea[0].offsetHeight;
            App.linkifyMessages('#messages-area');
        });
    }
};

$(function(){
    $('#flashes').each(function(){
        // fade out slowly after a delay unless mouse is hovered over.
        // fade out quickly on click
        var $this = $(this),
            fadeOutDuration = 3000,
            fadeOutDelay = 1000,
            fadeOutDelayIntervalID,
            startTimedFadeout;

        startTimedFadeout = function() {
            fadeOutDelayIntervalID = window.setTimeout(function () {
                $this.fadeOut(fadeOutDuration);
            }, fadeOutDelay);
        };

        $this
            .on('click', function(e){
                e.preventDefault();
                $this
                    .fadeOut('fast')
                    .off('.fadeOutPause');
            })
            .on('mouseenter.fadeOutPause', function(e){
                window.clearTimeout(fadeOutDelayIntervalID);
                $this.finish().show();
            })
            .on('mouseleave.fadeOutPause', function(e){
                startTimedFadeout();
            });

        startTimedFadeout();
    });

    $('#buffer-list').on('click', '.buffer-list-item', function(e) {
        e.preventDefault();
        App.loadBuffer($(this).data('bufferid'));
    });
});
