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
 * @property-read string $senderNick
 * @property-read string $senderIdent
 * @property-read string $senderHost
 * @property-read string $sender
 * @property-read bool $isBareNick
 */
class Sender extends Model
{
    protected $senderId;
    protected $senderNick;
    protected $senderIdent;
    protected $senderHost;

    protected static $publicPropertiesRead = array(
        'senderId',
        'senderNick',
        'senderIdent',
        'senderHost',
        'sender',
        'isBareNick',
    );

    private static $senderCache = array();

    private function __construct($senderId, $sender)
    {
        $this->senderId = $senderId;

        // Split the nick!ident@host string into its component parts
        if (($exclPos = strpos($sender, '!')) !== false && ($atPos = strpos($sender, '@', $exclPos)) !== false) {
            // Got the positions of both the ! and the @, so we can proceed
            $this->senderNick = substr($sender, 0, $exclPos);
            $this->senderIdent = substr($sender, $exclPos + 1, ($atPos - ($exclPos + 1)));
            $this->senderHost = substr($sender, $atPos + 1);
        } else {
            // Can't find the '!' and/or the '@'. This is the case for network messages, network services, and for one's
            //  own nick
            $this->senderNick = $sender;
        }
    }

    private static function fromDbRow($row)
    {
        if ($row === false) {
            // There's something broken in Quassel (possibly only in my core or the MySQL driver) which results in the
            // `backlog` table referencing records in the `sender` table that *don't exist*. This leads to our query to
            // find the sender failing and thus in having `false` passed to this method. As a bit of an ugly workaround
            // here, we create an empty Sender object that doesn't contain anything but empty strings, but does at least
            // avoid nasty PHP errors everywhere.
            // I mean, seriously, wtf is with this query result?
            // +-----------+---------------------+----------+------+-------+----------+----------+
            // | messageid | time                | bufferid | type | flags | senderid | message  |
            // +-----------+---------------------+----------+------+-------+----------+----------+
            // |  21132230 | 2014-08-06 03:23:38 |       29 |   32 |     0 |        0 | #....... |
            // +-----------+---------------------+----------+------+-------+----------+----------+
            return new Sender(0, '');
        }
        if (!is_object($row)) {
            throw new Exception("Argument passed to Sender::fromDbRow() must be an object! (Or, in exceptional circumstances, `false`).");
        }

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

    public function __get($name)
    {
        switch ($name) {
            case 'sender':
                if (isset($this->senderIdent, $this->senderHost)) {
                    return sprintf('%s!%s@%s', $this->senderNick, $this->senderIdent, $this->senderHost);
                } else {
                    return $this->senderNick;
                }
                break;
            case 'isBareNick':
                return !isset($this->senderIdent, $this->senderHost);
                break;
        }

        return parent::__get($name);
    }
}
