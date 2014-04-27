<?php $this->partial('src/View/Fragment/SearchForm.php'); ?>

<h1>Search Results</h1>
<h2>&ldquo;<?=$this->escape($this->searchQuery)?>&rdquo;</h2>

<?php if ($this->searchResults): ?>
    <ul>
        <?php foreach ($this->searchResults as /** @var \QuasselLogSearch\Quassel\Message */ $msg): ?>
            <li>[<?=$msg->time->format('Y-m-d H:i:s')?>] &lt;<?=$msg->sender->sender?>&gt; <?=$this->escape($msg->message)?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><em>No results found</em></p>
<?php endif; ?>
