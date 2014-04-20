<?php

namespace QuasselLogSearch;

class Bootstrap
{
    public static function init()
    {
        // Set an initial timezone (we'll get the "real"/requested one later) so that Monolog doesn't trigger warnings
        date_default_timezone_set('UTC');

        Logger::_init();

        date_default_timezone_set(Config::get(Config::TIMEZONE));
    }
}
