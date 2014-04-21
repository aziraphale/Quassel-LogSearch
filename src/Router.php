<?php

namespace QuasselLogSearch;

use Klein\Klein;
use QuasselLogSearch\Controller;

class Router
{
    /**
     * @var \Klein\Klein
     */
    private static $klein;

    public static function _init()
    {
        self::$klein = new Klein();
        self::_initRoutes();
    }

    private static function _initRoutes()
    {
        // Alias self::$klein to $klein for our callbacks
        $klein = self::$klein;

        $klein->with('!@^/ajax/', function () use ($klein) {
            // All NON-Ajax requests
            $klein->service()->layout('src/View/Layout/Layout.php');

            $klein->respond('GET', '/', array("QuasselLogSearch\\Controller\\Core", "index"));
        });

        $klein->with('/ajax', function () use ($klein) {

        });
    }

    public static function dispatch()
    {
        self::$klein->dispatch();
    }
}
