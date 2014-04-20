<?php

namespace QuasselLogSearch\DB;

abstract class AbstractDB
{
    protected $pdo;

    abstract public function __construct($dsn);
}

