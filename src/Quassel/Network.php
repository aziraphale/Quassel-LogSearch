<?php

namespace QuasselLogSearch\Quassel;

use QuasselLogSearch\Model;
use QuasselLogSearch\DB\DB;

/**
 *
 * CREATE TABLE `network` (
 *  `networkid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 *  `userid` bigint(20) unsigned NOT NULL,
 *  `networkname` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
 *  `identityid` bigint(20) unsigned DEFAULT NULL,
 *  `encodingcodec` varchar(32) NOT NULL DEFAULT 'ISO-8859-15',
 *  `decodingcodec` varchar(32) NOT NULL DEFAULT 'ISO-8859-15',
 *  `servercodec` varchar(32) DEFAULT NULL,
 *  `userandomserver` tinyint(1) NOT NULL DEFAULT '0',
 *  `perform` text,
 *  `useautoidentify` tinyint(1) NOT NULL DEFAULT '0',
 *  `autoidentifyservice` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
 *  `autoidentifypassword` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
 *  `usesasl` tinyint(1) NOT NULL DEFAULT '0',
 *  `saslaccount` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
 *  `saslpassword` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
 *  `useautoreconnect` tinyint(1) NOT NULL DEFAULT '1',
 *  `autoreconnectinterval` int(11) NOT NULL DEFAULT '0',
 *  `autoreconnectretries` int(11) NOT NULL DEFAULT '0',
 *  `unlimitedconnectretries` tinyint(1) NOT NULL DEFAULT '0',
 *  `rejoinchannels` tinyint(1) NOT NULL DEFAULT '0',
 *  `connected` tinyint(1) NOT NULL DEFAULT '0',
 *  `usermode` varchar(32) DEFAULT NULL,
 *  `awaymessage` varchar(256) DEFAULT NULL,
 *  `attachperform` text,
 *  `detachperform` text,
 *  PRIMARY KEY (`networkid`),
 *  UNIQUE KEY `userid` (`userid`,`networkname`),
 *  KEY `network_ibfk_2` (`identityid`),
 *  CONSTRAINT `network_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `quasseluser` (`userid`) ON DELETE CASCADE,
 *  CONSTRAINT `network_ibfk_2` FOREIGN KEY (`identityid`) REFERENCES `identity` (`identityid`) ON DELETE SET NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
 *
 * @property-read string $networkId
 * @property-read string $userId
 * @property-read string $networkName
 * @property-read bool $connected
 * @property-read \QuasselLogSearch\Quassel\User $user
 */
class Network extends Model
{
    protected $networkId;
    protected $userId;
    protected $networkName;
    protected $connected;
    protected $user;

    protected static $publicPropertiesRead = array(
        'networkId',
        'userId',
        'networkName',
        'connected',
        'user',
    );

    private function __construct($networkId, $userId, $networkName, $connected, User $user=null)
    {
        $this->networkId    = $networkId;
        $this->userId       = $userId;
        $this->networkName  = $networkName;
        $this->connected    = $connected;

        if (isset($user)) {
            $this->user = $user;
        }
    }

    private static function fromDbRow(\stdClass $row, User $user=null) {
        return new Network($row->networkid, $row->userid, $row->networkname, $row->connected, $user);
    }

    public static function loadAllForUser(User $user)
    {
        $result = array();
        $stmt = DB::getInstance()->prepare("SELECT * FROM network WHERE userid=?");
        if ($stmt->execute(array($user->userId))) {
            while ($row = $stmt->fetchObject()) {
                $result[] = self::fromDbRow($row, $user);
            }
        }
        return $result;
    }

    public static function loadByNetworkId($networkId)
    {
        $stmt = DB::getInstance()->prepare("SELECT * FROM network WHERE networkid=?");
        if ($stmt->execute(array($networkId))) {
            $row = $stmt->fetchObject();
            return self::fromDbRow($row);
        }
        return null;
    }

    public function getBuffers()
    {
        return Buffer::loadAllForNetwork($this, $this->user);
    }

    public function __get($name)
    {
        // Lazy-load the user object before the parent class tries to access it
        switch ($name) {
            case 'user':
                if (!isset($this->user)) {
                    $this->user = User::loadByUserId($this->userId);
                }
                break;
        }

        return parent::__get($name);
    }
}
