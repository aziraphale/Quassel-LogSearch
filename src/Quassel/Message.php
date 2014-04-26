<?php

namespace QuasselLogSearch\Quassel;

use QuasselLogSearch\Model;
use QuasselLogSearch\DB\DB;

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
 * @property-read int $flags
 * @property-read string $senderId
 * @property-read string $message
 * @property-read Buffer $buffer
 * @property-read Sender $sender
 */
class Message extends Model
{
    private $messageId;
    private $time;
    private $bufferId;
    private $type;
    private $flags;
    private $senderId;
    private $message;
    private $buffer;
    private $sender;

    protected static $publicPropertiesRead = array(
        'messageId',
        'time',
        'bufferId',
        'type',
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
        $this->time = new DateTime('@'.$time);
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

    public function __get($name)
    {
        // Lazy-load the sender & buffer objects before the parent class tries to access them
        switch ($name) {
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
