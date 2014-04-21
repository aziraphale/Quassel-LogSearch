<?php

namespace QuasselLogSearch\Controller;

use Klein\Klein;
use QuasselLogSearch\Utility\Authentication;
use QuasselLogSearch\Router;

class Layout
{
    public static function globalVariables(Klein $klein)
    {
        $service = $klein->service();

        $service->loggedIn = Authentication::loggedIn();
        $service->baseUrl = Router::baseUrl();
        $service->baseDir = Router::baseDir();
    }
}
