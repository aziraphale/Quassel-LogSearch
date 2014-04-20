<?php

namespace QuasselLogSearch\DB;

use PDO,
    PDOStatement,
    PDOException;

abstract class AbstractDB
{
    protected $pdo;

    public function __construct($dsn) {
        $this->connect($dsn);

        if (!$this->pdo instanceof PDO) {
            throw new Exception("Failed to connect to backend database.");
        }

        // Tell PDO to throw exceptions on errors
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    abstract protected function connect($dsn);
}

