<?php

namespace QuasselLogSearch\Quassel;

use QuasselLogSearch\Model;
use QuasselLogSearch\DB\DB;

/**
 *
 * CREATE TABLE `sender` (
 *  `senderid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 *  `sender` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
 *  PRIMARY KEY (`senderid`),
 *  UNIQUE KEY `sender` (`sender`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
 *
 * @property-read string $senderId
 * @property-read string $sender
 */
class Sender extends Model
{
    protected $senderId;
    protected $sender;

    protected static $publicPropertiesRead = array(
        'senderId',
        'sender',
    );

    private static $senderCache = array();

    private function __construct($senderId, $sender)
    {
        $this->senderId = $senderId;
        $this->sender = $sender;
    }

    private static function fromDbRow(\stdClass $row)
    {
        return new Sender($row->senderid, $row->sender);
    }

    public static function loadBySenderId($senderId)
    {
        // It's quite likely that we'll be finding ourselves trying to load the same sender multiple times on a single
        //  execution (e.g. multiple messages by the same sender in the same search result), so we cache sender objects
        //  here to save us having duplicate objects kicking around everywhere
        if (isset(self::$senderCache[$senderId])) {
            return self::$senderCache[$senderId];
        }

        $stmt = DB::getInstance()->prepare("SELECT * FROM sender WHERE senderid=?");
        if ($stmt->execute(array($senderId))) {
            $row = $stmt->fetchObject();
            $sender = self::fromDbRow($row);
            self::$senderCache[$senderId] = $sender;
            return $sender;
        }
        return null;
    }
}
