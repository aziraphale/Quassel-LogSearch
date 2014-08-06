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
    const MESSAGES_TO_LOAD_INITIAL = 60;
    const MESSAGES_TO_LOAD_ADDITIONAL = 60;

    public static function loadBuffer(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        $id = $request->param('id');

        $user = Authentication::loggedIn();
        if (!$user instanceof User) {
            throw new \Exception("You must be logged in!");
        }

        $buffer = Buffer::loadByBufferIdForUser($id, $user);

        $messages = $buffer->getMessagesUnfiltered(self::MESSAGES_TO_LOAD_INITIAL);

        $service->partial('src/View/Fragment/MessagesArea.php', array('searchResults'=>$messages));
    }

    public static function loadEarlierMessages(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        $id = $request->param('id');
        $earliestMessageId = $request->param('earliestMessageId');

        $user = Authentication::loggedIn();
        if (!$user instanceof User) {
            throw new \Exception("You must be logged in!");
        }

        $buffer = Buffer::loadByBufferIdForUser($id, $user);

        $messages = $buffer->getMessagesUnfiltered(self::MESSAGES_TO_LOAD_ADDITIONAL, $earliestMessageId);

        $service->partial('src/View/Fragment/MessagesArea.php', array('searchResults'=>$messages));
    }

    public static function loadLaterMessages(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        $id = $request->param('id');
        $latestMessageId = $request->param('latestMessageId');

        $user = Authentication::loggedIn();
        if (!$user instanceof User) {
            throw new \Exception("You must be logged in!");
        }

        $buffer = Buffer::loadByBufferIdForUser($id, $user);

        $messages = $buffer->getMessagesUnfiltered(self::MESSAGES_TO_LOAD_ADDITIONAL, null, $latestMessageId);

        $service->partial('src/View/Fragment/MessagesArea.php', array('searchResults'=>$messages));
    }
}
