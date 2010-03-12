<br><br><br><br><br><br><br><br><br><br><br><center>
<span style="font-size: 11pt;font-weight:bold;color:white;margin:3px;"><?php echo _('Quassel Backlog Search')?></span><br><br>
<div id="login" style="height: 130px;">
<form id="postform" action="index.php" method="post">
<?php
if(isset($error)){
echo $error;}
?>
<input type="hidden" name="login" value="true">
<?php echo _('Quasseluser:')?><br>
<input size="30" id="input" type="text" name="quasseluser" value="<?php
if(isset($usern)){
echo $usern;}
?>"><br>
<?php echo _('Password:')?><br>
<input size="30" type="password" name="quasselpwd"><br>
<label for="cookie"><?php echo _('remember me:')?></label><input type="checkbox" name="cookie" id="cookie"><br>
<input type="submit" value="<?php echo _('Login'); ?>" class="button">
<br></form><br></div>
<br><span id="footer"><?php echo _('developed by')?> <a href="http://m4yer.minad.de/?page=5" target="_blank" style="color:#33333">m4yer</a> 2009-2010</span><br>