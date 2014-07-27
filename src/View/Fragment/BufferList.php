<ul class="networks">
    <?php foreach ($this->networks as /** @var \QuasselLogSearch\Quassel\Network */ $network): ?>
        <li>
            <span class="network-name"><?=$network->networkName?></span>
            <ul class="buffers">
                <?php foreach ($network->getBuffers() as /** @var \QuasselLogSearch\Quassel\Buffer */ $buffer): ?>
                    <li data-bufferid="<?=$buffer->bufferId?>">
                        <span class="buffer-name"><?=$buffer->bufferName?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>
