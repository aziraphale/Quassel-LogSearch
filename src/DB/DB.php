<?php

namespace QuasselLogSearch\DB;

use Exception;

class DB
{
    private static $inst;

    public static function _init($dsn)
    {
        self::$inst = self::buildFromDsn($dsn);
    }

    /**
     * Returns our Singleton AbstractDB instance
     *
     * @return AbstractDB
     */
    public static function getInstance()
    {
        if (!isset(self::$inst)) {
            throw new Exception("DB instance hasn't been set!");
        }

        return self::$inst;
    }

    public static function regexp($pattern, $subject)
    {
        return self::getInstance()->regexp($pattern, $subject);
    }

    private static function buildFromDsn($dsn)
    {
        $colonPos = strpos($dsn, ':');
        if ($colonPos === false) {
            throw new Exception("Invalid DSN!");
        }

        $dbms = substr($dsn, 0, $colonPos);
        $dbms = strtolower($dbms);

        $dbConn;
        switch ($dbms) {
            case 'sqlite':
                $dbConn = new SQLite($dsn);
                break;
            case 'pgsql':
                $dbConn = new PostgreSQL($dsn);
                break;
            case 'mysql':
                $dbConn = new MySQL($dsn);
                break;
            default:
                throw new Exception("Unknown database backend `$dbms`!");
        }

        return $dbConn;
    }
}
