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

    public static function searchBuffer(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        $id = $request->param('id');
        $query = $request->param('query');

        $user = Authentication::loggedIn();
        if (!$user instanceof User) {
            throw new \Exception("You must be logged in!");
        }

        $buffer = Buffer::loadByBufferIdForUser($id, $user);

        $matchedMessage = $buffer->getNextSearchResult($query, false);
        $contextBefore = $buffer->getMessagesUnfiltered(self::MESSAGES_TO_LOAD_INITIAL, $matchedMessage->messageId);
        $contextAfter = $buffer->getMessagesUnfiltered(self::MESSAGES_TO_LOAD_INITIAL, null, $matchedMessage->messageId);

        $messages = $contextBefore;
        $messages[] = $matchedMessage;
        foreach ($contextAfter as $ca) {
            $messages[] = $ca;
        }

        $service->partial('src/View/Fragment/MessagesArea.php', array('searchResults'=>$messages));
    }

    public static function searchEarlier(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        $id = $request->param('id');
        $query = $request->param('query');
        $earliestMessageId = $request->param('earliestMessageId');

        $user = Authentication::loggedIn();
        if (!$user instanceof User) {
            throw new \Exception("You must be logged in!");
        }

        $buffer = Buffer::loadByBufferIdForUser($id, $user);

        $messages = $buffer->search($query, self::MESSAGES_TO_LOAD_ADDITIONAL, false, $earliestMessageId);

        $service->partial('src/View/Fragment/MessagesArea.php', array('searchResults'=>$messages));
    }

    public static function searchLater(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        $id = $request->param('id');
        $query = $request->param('query');
        $latestMessageId = $request->param('latestMessageId');

        $user = Authentication::loggedIn();
        if (!$user instanceof User) {
            throw new \Exception("You must be logged in!");
        }

        $buffer = Buffer::loadByBufferIdForUser($id, $user);

        $messages = $buffer->search($query, self::MESSAGES_TO_LOAD_ADDITIONAL, false, null, $latestMessageId);

        $service->partial('src/View/Fragment/MessagesArea.php', array('searchResults'=>$messages));
    }
}
