<form action="search" method="get">
    <fieldset>
        <label for="buffer">Channel/Query:</label>
        <select id="buffer" name="buffer">
            <?php foreach ($this->networks as /** @var \QuasselLogSearch\Quassel\Network */ $network): ?>
                <optgroup label="<?=$network->networkName?>">
                    <?php foreach ($network->getBuffers() as /** @var \QuasselLogSearch\Quassel\Buffer */ $buffer): ?>
                        <option value="<?=$buffer->bufferId?>"><?=$buffer->bufferName?></option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select><br>

        <label for="q">Search for:</label>
        <input type="search" id="q" name="q" autocomplete="on" placeholder="Search query..." required><br>

        <button type="submit">Search</button>
    </fieldset>
</form>
