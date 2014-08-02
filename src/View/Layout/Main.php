<?php
/** @var \Klein\ServiceProvider */ $this;
?><!DOCTYPE html>
<html lang="en">
<?php $this->partial('src/View/Fragment/_LayoutHead.php'); ?>
<body class="main">

<?php $this->partial('src/View/Fragment/NavBar.php'); ?>

<?php $this->partial('src/View/Fragment/AlertFlashes.php'); ?>

<div class="container-fluid">
    <div id="content">
        <?php $this->yieldView(); ?>
    </div>
</div>

<?php if ($this->loggedIn): ?>
    <div class="footer">
        <div class="container">
            <?php $this->partial('src/View/Fragment/SearchForm.php'); ?>
        </div>
    </div>
<?php endif; ?>

</body>
</html>
