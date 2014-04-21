<form action="login" method="post">
    <label for="username">Quassel username:</label>
    <input type="text" name="username" id="username" value="<?=isset($_POST['username'])?$this->escape($_POST['username']):''?>"><br>

    <label for="password">Quassel password:</label>
    <input type="password" name="password" id="password"><br>

    <button type="submit">Log In</button>
</form>
