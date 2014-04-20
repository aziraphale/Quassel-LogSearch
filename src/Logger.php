<?php

namespace QuasselLogSearch;

/**
 * Our error logger class
 *
 * @method public static bool emergency(string $message, array $context)
 * @method public static bool alert(string $message, array $context)
 * @method public static bool critical(string $message, array $context)
 * @method public static bool error(string $message, array $context)
 * @method public static bool warning(string $message, array $context)
 * @method public static bool notice(string $message, array $context)
 * @method public static bool info(string $message, array $context)
 * @method public static bool debug(string $message, array $context)
 *
 * @todo We're supposed to be pulling log level and filename from config (and we should probably be pulling more than that, to allow for other logging types)
 */
class Logger
{
    private static $logger;

    public static function _init()
    {
        self::$logger = new \Monolog\Logger('dev');
        self::$logger->pushHandler(new \Monolog\Handler\BrowserConsoleHandler());
        self::$logger->pushHandler(new \Monolog\Handler\ErrorLogHandler());
        self::$logger->pushProcessor(new \Monolog\Processor\IntrospectionProcessor());
        self::$logger->pushProcessor(new \Monolog\Processor\WebProcessor());

        \Monolog\ErrorHandler::register(self::$logger);
    }

    public static function __callStatic($name, $args)
    {
        return call_user_func_array(array(self::$logger, $name), $args);
    }
}
