<div class="row">
    <div class="col-sm-3 col-md-2 col-lg-2" id="buffer-list">
        <?php $this->partial('src/View/Fragment/BufferList.php'); ?>
    </div>

    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 col-lg-10 col-lg-offset-2" id="messages-area">
        <div id="messages-container">
            <?php $this->partial('src/View/Fragment/MessagesArea.php'); ?>
        </div>
    </div>
</div>
