<?php

namespace QuasselLogSearch\Controller;

use Klein\App;
use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;
use QuasselLogSearch\Quassel\Buffer;
use QuasselLogSearch\Quassel\User;
use QuasselLogSearch\Utility\Authentication;

class Ajax
{
    public static function loadBuffer(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        $id = $request->param('id');

        $user = Authentication::loggedIn();
        if (!$user instanceof User) {
            throw new \Exception("You must be logged in!");
        }

        $buffer = Buffer::loadByBufferIdForUser($id, $user);

        $messages = $buffer->getMessagesUnfiltered(60);

        $service->partial('src/View/Fragment/MessagesArea.php', array('searchResults'=>$messages));
    }

    public static function loadMoreMessages(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        $id = $request->param('id');
        $earliestMessageId = $request->param('earliestMessageId');

        $user = Authentication::loggedIn();
        if (!$user instanceof User) {
            throw new \Exception("You must be logged in!");
        }

        $buffer = Buffer::loadByBufferIdForUser($id, $user);

        $messages = $buffer->getMessagesUnfiltered(60, $earliestMessageId);

        $service->partial('src/View/Fragment/MessagesArea.php', array('searchResults'=>$messages));
    }
}
