<?php

namespace QuasselLogSearch\DB;

use PDO;

class SQLite extends AbstractDB
{
    protected function connect($dsn) {
        $this->pdo = new PDO($dsn);
    }
}
