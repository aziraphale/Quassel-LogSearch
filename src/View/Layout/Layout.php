<?php
/** @var \Klein\ServiceProvider */ $this;
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<base href="<?=$this->escape($this->baseUrl)?>">
<title>Quassel Log Search</title>
<link rel="stylesheet" href="public/style/main.css">
<script src="public/script/main.js"></script>
</head>
<body>

<h1>Quassel Log Search</h1>

<?php if ($errorFlashes = $this->flashes('error')): ?>
    <aside id="error_flashes">
        <ul>
            <?php foreach ($errorFlashes as $flashMessage): ?>
                <li><?=$flashMessage?></li>
            <?php endforeach; ?>
        </ul>
    </aside>
<?php endif; ?>

<?php if ($successFlashes = $this->flashes('success')): ?>
    <aside id="success_flashes">
        <ul>
            <?php foreach ($successFlashes as $flashMessage): ?>
                <li><?=$flashMessage?></li>
            <?php endforeach; ?>
        </ul>
    </aside>
<?php endif; ?>

<?php if ($this->loggedIn): ?>
    <aside id="logged_in" style="float: right;">
        <p>You are logged in as: <strong><?=$this->loggedIn->username?></strong></p>
    </aside>
<?php else: ?>
    <aside id="login_dialog" style="float: right;">
        <?php $this->partial('src/View/Fragment/LoginForm.php'); ?>
    </aside>
<?php endif; ?>

<div id="content">
    <?php $this->yieldView(); ?>
</div>

</body>
</html>
