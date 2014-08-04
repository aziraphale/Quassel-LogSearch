<ul class="networks list-group">
    <?php foreach ($this->networks as /** @var \QuasselLogSearch\Quassel\Network */ $network): ?>
        <li class="list-group-item">
            <h4 class="network-name"><?=$network->networkName?></h4>
            <div class="buffers list-group">
                <?php foreach ($network->getBuffers() as /** @var \QuasselLogSearch\Quassel\Buffer */ $buffer): ?>
                    <a href="" class="list-group-item buffer-list-item" data-bufferid="<?=$buffer->bufferId?>">
                        <span class="buffer-name"><?=$buffer->bufferName?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
