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
