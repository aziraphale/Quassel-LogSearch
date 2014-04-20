<?php

namespace QuasselLogSearch\DB;

use PDO;

class SQLite extends AbstractDB
{
    public function __construct($dsn) {
        $this->pdo = new PDO($dsn);
    }
}

