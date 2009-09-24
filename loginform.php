<br><br><br><br><br><br><br><br><br><br><br><br><br><center><div id="login" style="height: 200px;">
<form id="postform" action="index.php" method="post"><!-- onsubmit="Request('bodyid','login.php?'+$(this).serialize()); return false"-->
<?=$error?>
<input type="hidden" name="login" value="true">
Quasseluser:<br>
<input size="30" id="input" type="text" name="quasseluser" value="<?=$usern?>"><br>
Passwort:<br>
<input size="30" type="password" name="quasselpwd"><br>
<input type="submit" value="Login" class="button"></form><br></div>