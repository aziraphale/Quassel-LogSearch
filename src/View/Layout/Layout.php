<?php
/** @var \Klein\ServiceProvider */ $this;
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<base href="<?=$this->escape($this->baseUrl)?>">
<title>Quassel Log Search</title>

<script src="public/vendor/jquery/jquery.min.js"></script>
<!--<script src="public/vendor/jquery/jquery-migrate.min.js"></script>-->
<link rel="stylesheet" href="public/vendor/jquery-ui/themes/base/minified/jquery-ui.min.css">
<script src="public/vendor/jquery-ui/ui/minified/jquery-ui.min.js"></script>

<script src="public/vendor/Autolinker.js/dist/Autolinker.min.js"></script>

<link href="/public/style/screen.css" media="screen, projection" rel="stylesheet">
<link href="/public/style/print.css" media="print" rel="stylesheet">
<!--[if IE]>
    <link href="/public/style/ie.css" media="screen, projection" rel="stylesheet">
<![endif]-->
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
        <p>You are logged in as: <strong><?=$this->loggedIn->username?></strong>. <a href="logout">Log out</a></p>
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
