<?php

namespace QuasselLogSearch\DB;

use PDO;
use PDOStatement;
use PDOException;
use Exception;

/**
 * @method public static bool beginTransaction()
 * @method public static bool commit()
 * @method public static int exec(string $statement)
 * @method public static bool inTransaction()
 * @method public static string lastInsertId(string $name=NULL)
 * @method public static PDOStatement prepare(string $statement)
 * @method public static PDOStatement query(string $statement, int $fetchType)
 * @method public static string quote(string $string, int $paramter_type=PDO::PARAM_STR)
 * @method public static bool rollBack()
 */
abstract class AbstractDB
{
    /**
     * Database backend PDO connection
     *
     * @var PDO
     */
    protected $pdo;

    public function __construct($dsn)
    {
        $this->connect($dsn);

        if (!$this->pdo instanceof PDO) {
            throw new Exception("Failed to connect to backend database.");
        }

        // Tell PDO to throw exceptions on errors
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    abstract protected function connect($dsn);

    abstract public function regexp($pattern, $subject);

    public function __call($name, $args)
    {
        switch (strtolower($name)) {
            case 'begintransaction':
            case 'commit':
            case 'exec':
            case 'intransaction':
            case 'lastinsertid':
            case 'prepare':
            case 'query':
            case 'quote':
            case 'rollback':
                return call_user_func_array(array($this->pdo, $name), $args);
            default:
                throw new Exception("Invalid method name `$name()`");
        }
    }
}
