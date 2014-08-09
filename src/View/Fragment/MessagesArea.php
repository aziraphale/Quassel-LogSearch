<?php use QuasselLogSearch\Quassel\Message;

if ($this->searchResults): ?>
    <ul>
        <?php foreach ($this->searchResults as /** @var \QuasselLogSearch\Quassel\Message */ $msg): ?>
            <li
                data-messageid="<?=$msg->messageId?>"
                data-messageurlenc="<?=urlencode($msg->message)?>"
                class="
                    msgtype_<?=$msg->typeAsHexString?>
                    <?=$msg->isSearchHighlight ? 'msg_searchhighlight' : ''?>
                    "
                >
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
                <?php elseif ($msg->type == Message::TYPE_DAY_CHANGE): ?>
                    <span class="sender">-</span>
                    <span class="message">{Day changed to <?=$msg->asHtml()?>}</span>
                <?php elseif ($msg->type == Message::TYPE_ERROR): ?>
                    <span class="sender">*</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_INFO): ?>
                    <span class="sender">*</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_SERVER): ?>
                    <span class="sender">*</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_INVITE): ?>
                    <span class="sender">-!-</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_JOIN): ?>
                    <span class="sender">--&gt;</span>
                    <span class="message"><?=$senderTag?> has joined <?=$msg->getJoinedChannel()?></span>
                <?php elseif ($msg->type == Message::TYPE_KICK): ?>
                    <span class="sender">&lt;-*</span>
                    <span class="message">
                        <?=$senderTag?> has kicked <?=$msg->getKickedUser()?>
                        <?=$msg->getKickMessage()?' ('.$msg->getKickMessage.')':''?>
                    </span>
                <?php elseif ($msg->type == Message::TYPE_KILL): ?>
                    <span class="sender">&lt;--</span>
                    <span class="message"><?=$senderTag?> was killed (<?=$msg->asHtml()?>)</span>
                <?php elseif ($msg->type == Message::TYPE_MODE): ?>
                    <span class="sender">***</span>
                    <span class="message">Mode <?=$msg->asHtml()?> by <?=$senderTag?></span>
                <?php elseif ($msg->type == Message::TYPE_NETSPLIT_JOIN): ?>
                    <span class="sender">=&gt;</span>
                    <span class="message"><?=$msg->getNetsplitJoinString()?></span>
                <?php elseif ($msg->type == Message::TYPE_NETSPLIT_QUIT): ?>
                    <span class="sender">&lt;=</span>
                    <span class="message"><?=$msg->getNetsplitQuitString()?></span>
                <?php elseif ($msg->type == Message::TYPE_NICK): ?>
                    <span class="sender">&lt;-&gt;</span>
                    <?php if ($msg->sender->senderNick == $msg->message): ?>
                        You are now known as <?=$msg->message?>
                    <?php else: ?>
                        <span class="message"><?=$senderTag?> is now known as <?=$msg->asHtml()?></span>
                    <?php endif; ?>
                <?php elseif ($msg->type == Message::TYPE_NOTICE): ?>
                    <span class="sender">[<?=$senderTag?>]</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php elseif ($msg->type == Message::TYPE_PART): ?>
                    <span class="sender">&lt;--</span>
                    <span class="message"><?=$senderTag?> has left (<?=$msg->asHtml()?>)</span>
                <?php elseif ($msg->type == Message::TYPE_QUIT): ?>
                    <span class="sender">&lt;--</span>
                    <span class="message"><?=$senderTag?> has quit (<?=$msg->asHtml()?>)</span>
                <?php elseif ($msg->type == Message::TYPE_TOPIC): ?>
                    <span class="sender">*</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php else: ?>
                    <span class="sender">&iquest;<?=$senderTag?>&quest;</span>
                    <span class="message"><?=$msg->asHtml()?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><em>No messages to display</em></p>
<?php endif; ?>
