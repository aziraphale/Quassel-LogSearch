<?php

namespace QuasselLogSearch\DB;

use PDO;

class MySQL extends AbstractDB
{
    protected function connect($dsn)
    {
        // The MySQL driver doesn't let us include the username & password in the DSN, so we have to parse that out
        //  here, instead...
        $username = $password = '';

        $replaceCallback = function($matches) use (&$username, &$password) {
            switch (strtolower($matches[1])) {
                case 'user':
                    $username = $matches[2];
                    break;
                case 'password':
                    $password = $matches[2];
                    break;
            }
            return '';
        };

        $dsn = preg_replace_callback('/(?<=[;:])(user|password)=(.*?)(?:;|$)/i', $replaceCallback, $dsn);

        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
        );

        $this->pdo = new PDO($dsn, $username, $password, $options);
    }
}
