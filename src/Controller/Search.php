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
}
