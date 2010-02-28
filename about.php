<?php
//
//      Quassel Backlog Search
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//
 require_once('config.php'); 
 require_once('classes/gettext.class.php');
?>
<div style="margin:-18px;float:right;height:50px; width:50px; right:0px;top:0px;position:relative;z-index:10;background-color:white;"><span style="float:right;margin: 3px;" onclick="javascript:document.getElementById('scontent').innerHTML = '<center><?=_('Waiting for search ...')?></center>';">[ x ]</span></div><center>
<a style="font-size:13pt;" target="_blank" href="http://m4yer.minad.de/quassel/"><?=_('Quassel Backlog Search')?></a><br><br style="line-height:5pt;">
<?
if(is_dir('.git')){
    echo _('Version: ');
    
if($verzeichnis=opendir('.git/refs/tags')){
while ($file = @readdir ($verzeichnis)) {
$array[] = $file;
}}
    natsort($array);
    $array = array_reverse($array);
    echo $array[0] . '+'. exec("git log --tags $array[0].. --oneline | wc -l");
    echo ' ( git - ';
    include('.git/refs/heads/master');
    echo ' )';
    }else{
        //fallbackversion
        echo 'Version: 0.4.1-rc1+';
        }
        
    if(@exec('quasselcore --version') != ''){
    echo '<br>'._('on').' '.@exec('quasselcore --version');}
?>
<br><br><br><br>
<?=_('A webbased <b>Search-Engine</b> for <a target="_blank" href="http://quassel-irc.org/">Quassel IRC</a>.')?>
<br>
<?=_('<a target="_blank" href="http://m4yer.minad.de/quassel/">Quassel Backlog Search</a> is licensed under a <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank">Creative Commons Licence</a>.')?>
<?=_('<br>developed and copyright 2009 by <a href="http://m4yer.minad.de/?page=5" target="_blank">m4yer</a>.')?>
<br><br><br><br>
<?=_('<a target="_blank" href="http://quassel-irc.org/">Quassel IRC</a> is a modern, cross-platform, distributed IRC client.<br>')?>
<?=_('&copy;2005-2009 by the Quassel Project.<br>')?>
<a target="_blank" href="http://quassel-irc.org/">http://quassel-irc.org</a>.
<br><br><br><br>
<?=_('Most icons are &copy; by the <a href="http://www.oxygen-icons.org/" target="_blank">Oxygen Team</a>, used under <a href="http://www.gnu.org/licenses/lgpl.html" target="_blank">LGPL</a> and adopted from <a target="_blank" href="http://quassel-irc.org/">Quassel IRC</a>.')?>
<br><br><br><br><b><?=_('Special thanks go to:')?></b><br>
<b>brot</b> for bugfixing, testing, helping, finding features and bugs, building the environment, advertising, motivating and a lot more.<br>
<b>The Quassel IRC Community</b> for feedback, finding bugs and using.<br>
<b><a target="_blank" href="http://quassel-irc.org/">The Quassel IRC Team</a></b> for developing such a great IRC-Client.<br><br>

</center>