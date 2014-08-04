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
        $('#messages-container').data('loading', true).load('ajax/load-buffer/'+bufferId, null, function() {
            var $msgArea = $('#messages-area'),
                $msgContainer = $('#messages-container'),
                $msgUl = $msgContainer.children('ul').first();

            $msgArea[0].scrollTop = $msgContainer[0].offsetHeight;
            $msgArea
                .data('bufferId', bufferId)
                .data('loading', false)
                .data('earliestMessageId', $msgUl.children('li').first().data('messageid'));

            App.linkifyMessages('#messages-area');
        });
    },

    loadMoreMessages : function() {
        var $msgArea = $('#messages-area'),
            $msgContainer = $('#messages-container'),
            bufferId = $msgArea.data('bufferId'),
            earliestMessageId = $msgContainer.children('ul').first().children('li').first().data('messageid'),
            temp,
            scrollOffsetFromBottom;

        if (!$msgArea.data('loading')) {
            if (bufferId) {
                $msgArea.data('loading', true);

                scrollOffsetFromBottom = $msgContainer[0].offsetHeight - $msgArea[0].scrollTop;

                temp = $('<div/>');
                temp.load('ajax/load-more-messages/'+bufferId+'/'+earliestMessageId, null, function() {
                    App.linkifyMessages(temp);
                    temp.children('ul').prependTo($msgContainer);

                    $msgArea
                        .data('loading', false)
                        .data('earliestMessageId', $msgContainer.children('ul').first().children('li').first().data('messageid'));

                    $msgArea[0].scrollTop = $msgContainer[0].offsetHeight - scrollOffsetFromBottom;
                });
            }
        }
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

    $('#messages-area').on('scroll', function(e){
        if (this.scrollTop <= 100) {
            App.loadMoreMessages();
        }
    });
});
