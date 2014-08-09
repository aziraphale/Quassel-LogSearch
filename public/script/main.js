"use strict";

var App = {
    Flashes : {
        fadeOutDuration : 3000,
        fadeOutDelay : 1000
    },

    infiniteScrollThreshold : 100,

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

    loadEarlierMessages : function() {
        /** @todo Some way of realising that we've hit the first message of a buffer and then not issuing any more requests for earlier messages */
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
                temp.load('ajax/load-earlier-messages/'+bufferId+'/'+earliestMessageId, null, function() {
                    App.linkifyMessages(temp);
                    temp.children('ul').prependTo($msgContainer);

                    $msgArea
                        .data('loading', false)
                        .data('earliestMessageId', $msgContainer.children('ul').first().children('li').first().data('messageid'));

                    $msgArea[0].scrollTop = $msgContainer[0].offsetHeight - scrollOffsetFromBottom;
                });
            }
        }
    },

    loadLaterMessages : function() {
        var $msgArea = $('#messages-area'),
            $msgContainer = $('#messages-container'),
            bufferId = $msgArea.data('bufferId'),
            latestMessageId = $msgContainer.children('ul').last().children('li').last().data('messageid'),
            temp,
            scrollOffsetFromTop;

        if (!$msgArea.data('loading')) {
            if (bufferId) {
                $msgArea.data('loading', true);

                scrollOffsetFromTop = $msgArea[0].scrollTop;

                temp = $('<div/>');
                temp.load('ajax/load-later-messages/'+bufferId+'/'+latestMessageId, null, function() {
                    App.linkifyMessages(temp);
                    temp.children('ul').appendTo($msgContainer);

                    $msgArea
                        .data('loading', false)
                        .data('latestMessageId', $msgContainer.children('ul').last().children('li').last().data('messageid'));

                    $msgArea[0].scrollTop = scrollOffsetFromTop;
                });
            }
        }
    },

    searchBuffer : function(bufferId, query) {
        $('#messages-container').data('loading', true).load('ajax/search-buffer/'+bufferId+'/'+encodeURIComponent(query), null, function() {
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
    }
};

$(function(){
    $('#flashes').each(function(){
        // fade out slowly after a delay unless mouse is hovered over.
        // fade out quickly on click
        var $this = $(this),
            fadeOutDelayIntervalID,
            startTimedFadeout;

        startTimedFadeout = function() {
            fadeOutDelayIntervalID = window.setTimeout(function () {
                $this.fadeOut(App.Flashes.fadeOutDuration);
            }, App.Flashes.fadeOutDelay);
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
        var $msgArea = $('#messages-area'),
            $msgContainer = $('#messages-container'),
            scrollOffsetFromTop = $msgArea[0].scrollTop,
            scrollOffsetFromBottom = $msgContainer[0].offsetHeight - ($msgArea[0].scrollTop + $msgArea[0].offsetHeight);

        if (scrollOffsetFromTop <= App.infiniteScrollThreshold) {
            App.loadEarlierMessages();
        }

        if (scrollOffsetFromBottom <= App.infiniteScrollThreshold) {
            App.loadLaterMessages();
        }
    });

    $('#search-form').children('form').on('submit', function(e){
        var $msgArea = $('#messages-area');

        e.preventDefault();
        
        /** @todo no guarantee a buffer has been loaded yet */
        App.searchBuffer($msgArea.data('bufferId'), $('#q').val());
    });
});
