<?php

namespace QuasselLogSearch\Quassel;

use QuasselLogSearch\Model;
use QuasselLogSearch\DB\DB;

/**
 *
 * CREATE TABLE `buffer` (
 *  `bufferid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 *  `userid` bigint(20) unsigned NOT NULL,
 *  `groupid` int(11) DEFAULT NULL,
 *  `networkid` bigint(20) unsigned NOT NULL,
 *  `buffername` varchar(128) NOT NULL,
 *  `buffercname` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
 *  `buffertype` int(11) NOT NULL DEFAULT '0',
 *  `lastseenmsgid` int(11) NOT NULL DEFAULT '0',
 *  `markerlinemsgid` int(11) NOT NULL DEFAULT '0',
 *  `key` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
 *  `joined` tinyint(1) NOT NULL DEFAULT '0',
 *  PRIMARY KEY (`bufferid`),
 *  UNIQUE KEY `userid` (`userid`,`networkid`,`buffercname`),
 *  KEY `networkid` (`networkid`),
 *  CONSTRAINT `buffer_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `quasseluser` (`userid`) ON DELETE CASCADE,
 *  CONSTRAINT `buffer_ibfk_2` FOREIGN KEY (`networkid`) REFERENCES `network` (`networkid`) ON DELETE CASCADE
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
 *
 * @property-read string $bufferId
 * @property-read string $userId
 * @property-read string $networkId
 * @property-read string $bufferName
 * @property-read int $bufferType
 * @property-read bool $joined
 * @property-read \QuasselLogSearch\Quassel\User $user
 * @property-read \QuasselLogSearch\Quassel\Network $network
 */
class Buffer extends Model
{
    // Buffer type list unashamedly copied from the QuasselDroid source:
    //  QuasselDroid/src/main/java/com/iskrembilen/quasseldroid/BufferInfo.java
    const BUFFER_TYPE_INVALID   = 0x00;
    const BUFFER_TYPE_STATUS    = 0x01;
    const BUFFER_TYPE_CHANNEL   = 0x02;
    const BUFFER_TYPE_QUERY     = 0x04;
    const BUFFER_TYPE_GROUP     = 0x08;

    protected $bufferId;
    protected $userId;
    protected $networkId;
    protected $bufferName;
    protected $bufferType;
    protected $joined;
    protected $user;
    protected $network;

    protected static $publicPropertiesRead = array(
        'bufferId',
        'userId',
        'networkId',
        'bufferName',
        'bufferType',
        'joined',
        'user',
        'network',
    );

    private function __construct(
        $bufferId,
        $userId,
        $networkId,
        $bufferName,
        $bufferType,
        $joined,
        User $user=null,
        Network $network=null
    ) {
        $this->bufferId = $bufferId;
        $this->userId = $userId;
        $this->networkId = $networkId;
        $this->bufferName = $bufferName;
        $this->bufferType = $bufferType;
        $this->joined = $joined;

        if (isset($user)) {
            $this->user = $user;
        }

        if (isset($network)) {
            $this->network = $network;
        }
    }

    private static function fromDbRow(\stdClass $row, User $user = null, Network $network = null)
    {
        return new Buffer($row->bufferid, $row->userid, $row->networkid, $row->buffername, $row->buffertype, $row->joined, $user, $network);
    }

    public static function loadAllForUser(User $user)
    {
        $result = array();
        $stmt = DB::getInstance()->prepare("SELECT * FROM buffer WHERE userid=?");
        if ($stmt->execute(array($user->userId))) {
            while ($row = $stmt->fetchObject()) {
                $result[] = self::fromDbRow($row, $user);
            }
        }
        return $result;
    }

    public static function loadAllForNetwork(Network $network, User $user = null)
    {
        $result = array();
        $stmt = DB::getInstance()->prepare("SELECT * FROM buffer WHERE networkid=?");
        if ($stmt->execute(array($network->networkId))) {
            while ($row = $stmt->fetchObject()) {
                $result[] = self::fromDbRow($row, $user, $network);
            }
        }
        return $result;
    }

    public static function loadByBufferId($bufferId)
    {
        $stmt = DB::getInstance()->prepare("SELECT * FROM buffer WHERE bufferid=?");
        if ($stmt->execute(array($bufferId))) {
            $row = $stmt->fetchObject();
            return self::fromDbRow($row);
        }
        return null;
    }

    public static function loadByBufferIdForUser($bufferId, User $user)
    {
        $stmt = DB::getInstance()->prepare("SELECT * FROM buffer WHERE bufferid=? AND userid=?");
        if ($stmt->execute(array($bufferId, $user->userId))) {
            $row = $stmt->fetchObject();
            return self::fromDbRow($row);
        }
        return null;
    }

    public function search($query, $limit, $queryIsRegex = false, $earlierThanMessageId = null)
    {
        return Message::search($this, $query, $limit, $queryIsRegex, $earlierThanMessageId);
    }

    public function __get($name)
    {
        // Lazy-load the user and network objects before the parent class tries to access them
        switch ($name) {
            case 'user':
                if (!isset($this->user)) {
                    $this->user = User::loadByUserId($this->userId);
                }
                break;
            case 'network':
                if (!isset($this->network)) {
                    $this->network = Network::loadByNetworkId($this->networkId);
                }
                break;
        }

        return parent::__get($name);
    }
}
