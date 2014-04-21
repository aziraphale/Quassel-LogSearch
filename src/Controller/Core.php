<?php

namespace QuasselLogSearch\Controller;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;
use Klein\App;
use QuasselLogSearch\Utility\Authentication;
use QuasselLogSearch\Quassel\User;
use QuasselLogSearch\Router;

class Core
{
    public static function index(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        $service->render('src/View/Index.php');
    }
}

