<?php

namespace QuasselLogSearch\Controller;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;
use Klein\App;
use QuasselLogSearch\Utility\Authentication;
use QuasselLogSearch\Quassel\User;
use QuasselLogSearch\Router;

class Login
{
    public static function attemptLogin(/** @var Request */ $request, /** @var Response */ $response, /** @var ServiceProvider */ $service, /** @var App */ $app)
    {
        $user = Authentication::attemptLogin($request->param('username'), $request->param('password'));
        if ($user instanceof User) {
            $service->flash("You have been successfully logged in!", "success");
            Router::redirect('/', Router::REDIRECT_SEE_OTHER);
        } else {
            $service->flash("Invalid username and/or password.", "error");
            $service->render('src/View/Login.php');
        }
    }

    public static function logout(/** @var Request */ $request, /** @var Response */ $response, /** @var ServiceProvider */ $service, /** @var App */ $app)
    {
        Authentication::logout();
        $service->flash("You have been logged out.", "success");
        Router::redirect('/', Router::REDIRECT_SEE_OTHER);
    }
}

