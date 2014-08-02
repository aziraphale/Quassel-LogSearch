<?php

namespace QuasselLogSearch\Controller;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;
use Klein\App;
use QuasselLogSearch\Router;

class Stats
{
    public static function index(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        if (!$service->loggedIn) {
            Router::redirect('/', Router::REDIRECT_TEMPORARY_REDIRECT);
            return;
        }

        $service->render('src/View/Page/Stats/Index.php');
    }
}
