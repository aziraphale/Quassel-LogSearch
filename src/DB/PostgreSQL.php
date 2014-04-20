<?php

namespace QuasselLogSearch\DB;

use PDO;

class PostgreSQL extends AbstractDB
{
    public function __construct($dsn) {
        $this->pdo = new PDO($dsn);
    }
}

