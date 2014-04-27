<?php

namespace QuasselLogSearch\DB;

use PDO;

class SQLite extends AbstractDB
{
    protected function connect($dsn)
    {
        $this->pdo = new PDO($dsn);
    }

    public function regexp($pattern, $subject)
    {
        return sprintf('%s REGEXP %s', $subject, $pattern);
    }
}
