<?php

namespace QuasselLogSearch\Quassel;

use DateTime;
use Exception;
use QuasselLogSearch\Model;
use QuasselLogSearch\DB\DB;
use QuasselLogSearch\Utility\MIRC;
use QuasselLogSearch\Utility\NetsplitHelper;

/**
 *
 * CREATE TABLE `backlog` (
 *  `messageid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 *  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 *  `bufferid` bigint(20) unsigned NOT NULL,
 *  `type` int(11) NOT NULL,
 *  `flags` int(11) NOT NULL,
 *  `senderid` bigint(20) unsigned NOT NULL,
 *  `message` text COLLATE utf8mb4_bin,
 *  PRIMARY KEY (`messageid`),
 *  KEY `backlog_bufferid_idx` (`bufferid`,`messageid`),
 *  CONSTRAINT `backlog_ibfk_1` FOREIGN KEY (`bufferid`) REFERENCES `buffer` (`bufferid`) ON DELETE CASCADE
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin
 *
 * @property-read string $messageId
 * @property-read DateTime $time
 * @property-read string $bufferId
 * @property-read int $type
 * @property-read int $typeAsHexString
 * @property-read int $flags
 * @property-read string $senderId
 * @property-read string $message
 * @property-read Buffer $buffer
 * @property-read Sender $sender
 */
class Message extends Model
{
    // Buffer type list unashamedly copied from the QuasselDroid source:
    //  QuasselDroid/src/main/java/com/iskrembilen/quasseldroid/IrcMessage.java
    const TYPE_PLAIN            = 0x00001;
    const TYPE_NOTICE           = 0x00002;
    const TYPE_ACTION           = 0x00004;
    const TYPE_NICK             = 0x00008;
    const TYPE_MODE             = 0x00010;
    const TYPE_JOIN             = 0x00020;
    const TYPE_PART             = 0x00040;
    const TYPE_QUIT             = 0x00080;
    const TYPE_KICK             = 0x00100;
    const TYPE_KILL             = 0x00200;
    const TYPE_SERVER           = 0x00400;
    const TYPE_INFO             = 0x00800;
    const TYPE_ERROR            = 0x01000;
    const TYPE_DAY_CHANGE       = 0x02000;
    const TYPE_TOPIC            = 0x04000;
    const TYPE_NETSPLIT_JOIN    = 0x08000;
    const TYPE_NETSPLIT_QUIT    = 0x10000;
    const TYPE_INVITE           = 0x20000;

    protected $messageId;
    protected $time;
    protected $bufferId;
    protected $type;
    protected $typeAsHexString;
    protected $flags;
    protected $senderId;
    protected $message;
    protected $buffer;
    protected $sender;

    protected static $publicPropertiesRead = array(
        'messageId',
        'time',
        'bufferId',
        'type',
        'typeAsHexString',
        'flags',
        'senderId',
        'message',
        'buffer',
        'sender',
    );

    private function __construct(
        $messageId,
        $time,
        $bufferId,
        $type,
        $flags,
        $senderId,
        $message,
        Buffer $buffer = null,
        Sender $sender = null
    ) {
        $this->messageId = $messageId;
        $this->time = $time;
        $this->bufferId = $bufferId;
        $this->type = $type;
        $this->flags = $flags;
        $this->senderId = $senderId;
        $this->message = $message;

        if (isset($buffer)) {
            $this->buffer = $buffer;
        }

        if (isset($sender)) {
            $this->sender = $sender;
        }
    }

    private static function fromDbRow(\stdClass $row, Buffer $buffer = null, Sender $sender = null)
    {
        return new Message($row->messageid, $row->time, $row->bufferid, $row->type, $row->flags, $row->senderid,
            $row->message, $buffer, $sender);
    }

    public static function loadAllUnfiltered(Buffer $buffer, $limit, $earlierThanMessageId = null, $laterThanMessageId = null)
    {
        $loadAscending = false;
        $returnAscending = true;

        $sql = "SELECT * FROM backlog WHERE ";
        $args = array();

        $sql .= " bufferid=? ";
        $args[] = $buffer->bufferId;

        if ($earlierThanMessageId) {
            $sql .= ' AND messageid < ? ';
            $args[] = $earlierThanMessageId;

            // When loading "the next block of older messages", we need to request DESC (newest-first) in the query in
            // order to keep the returned messages adjacent to the already-loaded block. However we then need to invert
            // the order before returning them so that the latest messages are displayed at the bottom of the screen.
            $loadAscending = false;
            $returnAscending = true;
        } elseif ($laterThanMessageId) {
            $sql .= ' AND messageid > ? ';
            $args[] = $laterThanMessageId;

            // When loading "the next few newer messages", we need to request ASC (oldest-first) in the query in order
            // to keep the returned messages adjacent to the already-loaded block. This is also the order in which they
            // have to be returned (most recent at the end) so they don't need to be reversed in this case.
            $loadAscending = true;
            $returnAscending = true;
        }

        $sql .= ' ORDER BY messageid ' . ($loadAscending ? 'ASC' : 'DESC') . ' ';

        $sql .= ' LIMIT ' . ((int) $limit);

        $result = array();
        $stmt = DB::getInstance()->prepare($sql);
        if ($stmt->execute($args)) {
            while ($row = $stmt->fetchObject()) {
                $result[] = self::fromDbRow($row, $buffer);
            }
        }

        if ($loadAscending != $returnAscending) {
            $result = array_reverse($result);
        }

        return $result;
    }

    public static function search(Buffer $buffer, $query, $limit, $queryIsRegex = false, $earlierThanMessageId = null)
    {
        $sql = "SELECT * FROM backlog WHERE ";
        $args = array();

        $sql .= " bufferid=? ";
        $args[] = $buffer->bufferId;

        if ($queryIsRegex) {
            $sql .= ' ' . DB::regexp('message', '?') . ' ';
            $args[] = $query;
        } else {
            $sql .= ' AND message LIKE ? ';
            $args[] = '%' . $query . '%';
        }

        if ($earlierThanMessageId) {
            $sql .= ' AND messageid < ? ';
            $args[] = $earlierThanMessageId;
        }

        $sql .= ' ORDER BY messageid DESC ';

        $sql .= ' LIMIT ' . ((int) $limit);

        $result = array();
        $stmt = DB::getInstance()->prepare($sql);
        if ($stmt->execute($args)) {
            while ($row = $stmt->fetchObject()) {
                $result[] = self::fromDbRow($row, $buffer);
            }
        }

        // We needed to specify "ORDER BY messageid DESC" in the query in order to start our search with the most recent
        // messages, but we're not likely to want to DISPLAY them in that order - we'll want the most recent messages at
        // the BOTTOM of the screen - so we reverse the output here
        $result = array_reverse($result);
        return $result;
    }

    public function asHtml(array $config = array())
    {
        $mirc = new MIRC(htmlspecialchars($this->message, ENT_QUOTES, 'UTF-8'));
        return $mirc->toHtml();
    }

    public function getJoinedChannel()
    {
        if (($this->type & self::TYPE_JOIN) == 0) {
            throw new Exception("getJoinedChannel() can only be called on TYPE_JOIN messages");
        }

        return $this->message;
    }

    public function getKickedUser()
    {
        if (($this->type & self::TYPE_KICK) == 0) {
            throw new Exception("getKickedUser() can only be called on TYPE_KICK messages");
        }

        $parts = explode(' ', $this->message, 2);
        return $parts[0];
    }

    public function getKickMessage()
    {
        if (($this->type & self::TYPE_KICK) == 0) {
            throw new Exception("getKickMessage() can only be called on TYPE_KICK messages");
        }

        $parts = explode(' ', $this->message, 2);
        if (count($parts) > 1) {
            $mirc = new MIRC(htmlspecialchars($parts[1], ENT_QUOTES, 'UTF-8'));
            return $mirc->toHtml();
        }
        return "";
    }

    public function getNetsplitJoinString()
    {
        if (($this->type & self::TYPE_NETSPLIT_JOIN) == 0) {
            throw new Exception("getNetsplitJoinString() can only be called on TYPE_NETSPLIT_JOIN messages");
        }

        $helper = new NetsplitHelper($this->message);
        return $helper->formatJoinMessage();
    }

    public function getNetsplitQuitString()
    {
        if (($this->type & self::TYPE_NETSPLIT_JOIN) == 0) {
            throw new Exception("getNetsplitQuitString() can only be called on TYPE_NETSPLIT_JOIN messages");
        }

        $helper = new NetsplitHelper($this->message);
        return $helper->formatQuitMessage();
    }

    public function __get($name)
    {
        // Lazy-load the sender & buffer objects before the parent class tries to access them
        switch ($name) {
            case 'typeAsHexString':
                if (!isset($this->typeAsHexString)) {
                    $this->typeAsHexString = sprintf('%x', $this->type);
                }
                break;
            case 'time':
                if (!$this->time instanceof \DateTime) {
                    $this->time = new \DateTime($this->time);
                }
                break;
            case 'buffer':
                if (!isset($this->buffer)) {
                    $this->buffer = Buffer::loadByBufferId($this->bufferId);
                }
                break;
            case 'sender':
                if (!isset($this->sender)) {
                    $this->sender = Sender::loadBySenderId($this->senderId);
                }
                break;
        }

        return parent::__get($name);
    }
}
