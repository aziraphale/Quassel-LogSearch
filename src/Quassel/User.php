<?php

namespace QuasselLogSearch\Quassel;

use QuasselLogSearch\Model;
use QuasselLogSearch\DB\DB;

/**
 *
 * CREATE TABLE `quasseluser` (
 *  `userid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 *  `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
 *  `password` char(40) NOT NULL,
 *  PRIMARY KEY (`userid`),
 *  UNIQUE KEY `username` (`username`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
 *
 * @property-read string $userId
 * @property-read string $username
 * @property-read string $passwordHash
 */
class User extends Model
{
    protected $userId;
    protected $username;
    protected $passwordHash;

    protected static $publicPropertiesRead = array(
        'userId',
        'username',
        'passwordHash',
    );

    private function __construct($userId, $username, $password)
    {
        $this->userId       = $userId;
        $this->username     = $username;
        $this->passwordHash = $password;
    }

    private static function fromDbRow(\stdClass $row) {
        return new User($row->userid, $row->username, $row->password);
    }

    public static function loadByUserId($userId)
    {
        $stmt = DB::getInstance()->prepare("SELECT * FROM quasseluser WHERE userid=?");
        if ($stmt->execute(array($userId))) {
            $row = $stmt->fetchObject();
            return self::fromDbRow($row);
        }
        return null;
    }

    public static function loadByUsername($username)
    {
        $stmt = DB::getInstance()->prepare("SELECT * FROM quasseluser WHERE username=?");
        if ($stmt->execute(array($username))) {
            $row = $stmt->fetchObject();
            return self::fromDbRow($row);
        }
        return null;
    }

    public static function loadByUsernameAndPasswordHash($username, $passwordHash)
    {
        $stmt = DB::getInstance()->prepare("SELECT * FROM quasseluser WHERE username=? AND password=?");
        if ($stmt->execute(array($username, $passwordHash)) && $stmt->rowCount()) {
            $row = $stmt->fetchObject();
            return self::fromDbRow($row);
        }
        return null;
    }
}
