"use strict";

$(function(){
    $('.message').each(function(){
        this.innerHTML = Autolinker.link(this.innerHTML);
    });

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
});
