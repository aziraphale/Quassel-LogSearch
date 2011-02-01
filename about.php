<?php
//
//      Quassel Backlog Search
//      developed 2009-2011 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//
 require_once('config.php'); 
 require_once('classes/gettext.class.php');
?>
<div style="margin:-18px;float:right;height:70px; width:50px; right:0px;top:0px;position:relative;z-index:10;background-color:white;"><span style="float:right;margin: 3px;" onclick="javascript:document.getElementById('scontent').innerHTML = '<center><?php echo _('Waiting for search ...')?></center>';">[ x ]</span></div><center>
<a style="font-size:13pt;" target="_blank" href="http://m4yer.minad.de/quassel/"><?php echo _('Quassel Backlog Search')?></a><br><br style="line-height:5pt;">
<?php
if(is_file('version.txt')){
    echo _('Version:  ');
    
include('version.txt');

    }else{
        //fallbackversion
        echo 'Version: 0.5.4+';
        }
        
    if(@exec($corebinary.' --version') != ''){
    echo '<br>'._('on').' '.@exec($corebinary.' --version');}

// recommended_php_version warning
if (version_compare(PHP_VERSION, $recommended_php_version) !== 1) {
    echo '<br><br><span style="color:red">'._('Your php-version is below the recommended php-version ').$recommended_php_version.'<br>'._('Please try to update to be able to use all features!<br>The support for your php-version is depreciated and will be removed in future releases!').'</span>';
    }
    
?>
<br><br><br><br>
<?php echo _('A webbased <b>Search-Engine</b> for <a target="_blank" href="http://quassel-irc.org/">Quassel IRC</a>.')?>
<br>
<?php echo _('<a target="_blank" href="http://m4yer.minad.de/quassel/">Quassel Backlog Search</a> is licensed under a <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank">Creative Commons Licence</a>.')?>
<?php echo _('<br>developed and copyright 2009-2011 by <a href="http://m4yer.minad.de/?page=5" target="_blank">m4yer</a>.')?>
<br><br><br><br>
<?php echo _('<a target="_blank" href="http://quassel-irc.org/">Quassel IRC</a> is a modern, cross-platform, distributed IRC client.<br>')?>
<?php echo _('&copy;2005-2011 by the Quassel Project.<br>')?>
<a target="_blank" href="http://quassel-irc.org/">http://quassel-irc.org</a>.
<br><br><br><br>
<?php echo _('Most icons are &copy; by the <a href="http://www.oxygen-icons.org/" target="_blank">Oxygen Team</a>, used under <a href="http://www.gnu.org/licenses/lgpl.html" target="_blank">LGPL</a> and adopted from <a target="_blank" href="http://quassel-irc.org/">Quassel IRC</a>.')?>
<br><br><br><br><b><?php echo _('Special thanks go to:')?></b><br>
<b>brot</b> for bugfixing, testing, helping, finding features and bugs, building the environment, advertising, motivating and a lot more.<br>
<b>kode54</b> for the mySQL-backend.<br>
<b>Dirk Rettschlag</b> for bugfixing.<br>
<b>The Quassel IRC Community</b> for feedback, finding bugs and using.<br>
<b><a target="_blank" href="http://quassel-irc.org/">The Quassel IRC Team</a></b> for developing such a great IRC-Client.<br><br>

</center>