<?php use QuasselLogSearch\Quassel\Message;

if ($this->searchResults): ?>
    <ul>
        <?php foreach ($this->searchResults as /** @var \QuasselLogSearch\Quassel\Message */ $msg): ?>
            <li data-messageid="<?=$msg->messageId?>" data-messageurlenc="<?=urlencode($msg->message)?>">
                <span class="timestamp">[<?=$msg->time->format('Y-m-d H:i:s')?>]</span>

                <?php
                if ($msg->sender->isBareNick) {
                    $senderTag = sprintf(
                        '<span class="senderName">%s</span>',
                        $msg->sender->senderNick
                    );
                } else {
                    $senderTag = sprintf(
                        '<span class="senderName" title="%s">%s</span>',
                        $msg->sender->sender,
                        $msg->sender->senderNick
                    );
                }
                ?>

                <?php if ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_ACTION): ?>
                    <span class="sender">-*-</span>
                    <span class="message"><?=$senderTag?> <?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PLAIN): ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php else: ?>
                    <span class="sender">&lt;<?=$senderTag?>&gt;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><em>No messages to display</em></p>
<?php endif; ?>
