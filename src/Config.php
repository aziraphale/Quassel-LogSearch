<?php

namespace QuasselLogSearch;

class Config
{
    const BACKEND               = 'backend';
    const ENABLE_HASH_LOGIN     = 'enableHashLogin';
    const TIMEZONE              = 'timezone';
    const LANGUAGE              = 'language';
    const DATE_FORMAT           = 'dateFormat';
    const CORE_BINARY           = 'coreBinary';
    const LOG_LEVEL             = 'logLevel';
    const LOG_FILE              = 'logFile';
    const DEFAULT_RESULT_COUNT  = 'defaultResultCount';
    const LIVE_SEARCH           = 'liveSearch';

    private static $defaults = array(
        self::BACKEND                => "sqlite:/var/lib/quassel/quassel-storage.sqlite",
        self::ENABLE_HASH_LOGIN      => false,
        self::TIMEZONE               => 'UTC',
        self::LANGUAGE               => "en_GB",
        self::DATE_FORMAT            => "Y-m-d H:i:s",
        self::CORE_BINARY            => "/usr/bin/quasselcore",
        self::LOG_LEVEL              => "warning",
        self::LOG_FILE               => "./logs/log_%Y-%m-%d.log",
        self::DEFAULT_RESULT_COUNT   => 20,
        self::LIVE_SEARCH            => true,
    );

    private static $values;

    public static function _init()
    {
        if (isset(self::$values)) {
            return;
        }

        if (!defined('CONFIG_FILENAME')) {
            define('CONFIG_FILENAME', dirname(dirname(__FILE__)) . '/config.ini');
        }

        if (file_exists(CONFIG_FILENAME)) {
            $iniValues = parse_ini_file(CONFIG_FILENAME);
            self::$values = array_merge(self::$defaults, $iniValues);
        } else {
            Logger::warning("Unable to find the configuration file `" . CONFIG_FILENAME . "`");
            self::$values = self::$defaults;
        }
    }

    public static function get($key)
    {
        self::_init();

        return self::$values[$key];
    }
}
