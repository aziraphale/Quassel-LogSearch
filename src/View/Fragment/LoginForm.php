<form action="login" method="post" class="form-login">
    <h2>Please log in</h2>

    <input type="text" name="username" class="form-control" placeholder="Quassel username" required autofocus
        value="<?=isset($_POST['username'])?$this->escape($_POST['username']):''?>">
    <input type="password" name="password" class="form-control" placeholder="Quassel password" required><br>

    <button type="submit" class="btn btn-lg btn-primary btn-block">Log In</button>
</form>
