<h1>Search Results</h1>
<h2>&ldquo;<?=$this->escape($this->searchQuery)?>&rdquo;</h2>

<?php if ($this->searchResults): ?>
    <ul>
        <?php foreach ($this->searchResults as /** @var \QuasselLogSearch\Quassel\Message */ $msg): ?>
            <li data-messageid="<?=$msg->messageId?>" data-messageurlenc="<?=urlencode($msg->message)?>">
                <span class="timestamp">[<?=$msg->time->format('Y-m-d H:i:s')?>]</span>
                <?php if ($msg->sender->isBareNick): ?>
                    &lt;<span class="sender"><?=$msg->sender->senderNick?></span>&gt;
                <?php else: ?>
                    &lt;<span class="sender" title="<?=$msg->sender->sender?>"><?=$msg->sender->senderNick?></span>&gt;
                <?php endif; ?>
                <span class="message"><?=$msg->asHtml()?></span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><em>No results found</em></p>
<?php endif; ?>
