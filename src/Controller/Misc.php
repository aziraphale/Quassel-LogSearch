<?php

namespace QuasselLogSearch\Controller;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;
use Klein\App;


class Misc
{
    public static function about(Request $request, Response $response, ServiceProvider $service, App $app)
    {
        $service->render('src/View/Page/Misc/About.php');
    }
}
