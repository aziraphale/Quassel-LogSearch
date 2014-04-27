<?php

namespace QuasselLogSearch\DB;

use PDO;

class PostgreSQL extends AbstractDB
{
    protected function connect($dsn)
    {
        $this->pdo = new PDO($dsn);
    }

    public function regexp($pattern, $subject)
    {
        return sprintf('%s ~ %s', $subject, $pattern);
    }
}
