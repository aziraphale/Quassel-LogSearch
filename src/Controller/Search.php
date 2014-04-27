<?php

namespace QuasselLogSearch\Controller;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;
use Klein\App;
use QuasselLogSearch\Utility\Authentication;
use QuasselLogSearch\Quassel\User;
use QuasselLogSearch\Quassel\Buffer;
use QuasselLogSearch\Quassel\Network;
use QuasselLogSearch\Quassel\Message;
use QuasselLogSearch\Quassel\Sender;
use QuasselLogSearch\Router;

class Search
{
    public static function form(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        if (!$service->loggedIn) {
            Router::redirect('/', Router::REDIRECT_TEMPORARY_REDIRECT);
            return;
        }

        $service->render('src/View/Search.php');
    }

    public static function perform(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        if (!$service->loggedIn) {
            Router::redirect('/', Router::REDIRECT_TEMPORARY_REDIRECT);
            return;
        }

        $buffer = Buffer::loadByBufferIdForUser($request->param('buffer'), $service->loggedIn);

        $messages = $buffer->search($request->param('q'), 5, false, null);

        $service->searchQuery = $request->param('q');
        $service->searchResults = $messages;
        $service->render('src/View/SearchResults.php');
    }
}
