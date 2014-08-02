<?php
/** @var \Klein\ServiceProvider */ $this;
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?=$this->escape($this->baseUrl)?>">
    <title>Quassel Log Search</title>

    <!-- Bootstrap -->
    <link href="public/vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/vendor/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="public/vendor/jquery/jquery.min.js"></script>
    <!--<script src="public/vendor/jquery/jquery-migrate.min.js"></script>-->
    <link rel="stylesheet" href="public/vendor/jquery-ui/themes/base/minified/jquery-ui.min.css">
    <script src="public/vendor/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="public/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

    <script src="public/vendor/Autolinker.js/dist/Autolinker.min.js"></script>

    <link href="public/style/screen.css" media="screen, projection" rel="stylesheet">
    <link href="public/style/print.css" media="print" rel="stylesheet">
    <!--[if IE]>
        <link href="public/style/ie.css" media="screen, projection" rel="stylesheet">
    <![endif]-->
    <script src="public/script/main.js"></script>
</head>
<body class="main">

<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="search">Quassel Log Search</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="search">Log Search</a></li>
                <li><a href="stats">Statistics</a></li>
                <li><a href="about">About</a></li>
            </ul>

            <?php if ($this->loggedIn): ?>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="logout" class="logout">Log out</a>
                    </li>
                </ul>
                <p id="logged_in" class="navbar-text navbar-right">
                    You are logged in as <span class="logged-in-as"><?=$this->loggedIn->username?></span>
                </p>
            <?php endif; ?>
        </div><!--/.nav-collapse -->
    </div>
</div>

<div class="container">
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

    <div id="content">
        <?php $this->yieldView(); ?>
    </div>
</div>

<?php if ($this->loggedIn): ?>
    <div class="footer">
        <div class="container">
            <p class="text-muted">Place sticky footer content here.</p>
        </div>
    </div>
<?php endif; ?>

</body>
</html>
