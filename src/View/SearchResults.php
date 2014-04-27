<?php $this->partial('src/View/Fragment/SearchForm.php'); ?>

<h1>Search Results</h1>
<h2>&ldquo;<?=$this->escape($this->searchQuery)?>&rdquo;</h2>

<?php if ($this->searchResults): ?>
    <ul>
        <?php foreach ($this->searchResults as /** @var \QuasselLogSearch\Quassel\Message */ $msg): ?>
            <li>
                [<?=$msg->time->format('Y-m-d H:i:s')?>]
                <?php if ($msg->sender->isBareNick): ?>
                    &lt;<?=$msg->sender->senderNick?>&gt;
                <?php else: ?>
                    &lt;<span title="<?=$msg->sender->sender?>"><?=$msg->sender->senderNick?></span>&gt;
                <?php endif; ?>
                <?=$this->escape($msg->message)?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><em>No results found</em></p>
<?php endif; ?>
