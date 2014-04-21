<?php

namespace QuasselLogSearch\Controller;

use Klein;

class Core
{
    public static function index(/** @var Klein\Request */ $request, /** @var Klein\Response */ $response, /** @var Klein\ServiceProvider */ $service, /** @var Klein\App */ $app) {
        $service->render('src/View/Index.php');
    }
}

