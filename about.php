<span style="float:right;margin: -10px;" onclick="javascript:document.getElementById('scontent').innerHTML = '<center>Waiting for search ...</center>';">[ x ]</span><center>
<b style="font-size:13pt;">Quassel Backlog Search</b><br><br style="line-height:5pt;">
<?
if(is_dir('.git')){
    echo 'Version: ';
    
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
        echo 'Version: 0.3.4+';
        }
    echo '<br>on '.@exec('quasselcore --version');
?>
<br><br><br><br>
A webbased <b>Quassel-Search-Engine</b> for <a target="_blank" href="http://quassel-irc.org/">Quassel IRC</a>.
<br>
<b>Quassel Backlog Search</b> is licensed under a <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank">Creative Commons Licence</a>.
<br>developed and copyright 2009 by <a href="http://m4yer.minad.de/?page=5" target="_blank">m4yer</a>.
<br><br><br><br>
<a target="_blank" href="http://quassel-irc.org/">Quassel IRC</a> is a modern, cross-platform, distributed IRC client.<br>
©2005-2009 by the Quassel Project.<br>
<a target="_blank" href="http://quassel-irc.org/">http://quassel-irc.org</a>.
<br><br><br><br>
Most icons are &copy; by the <a href="http://www.oxygen-icons.org/" target="_blank">Oxygen Team</a>, used under <a href="http://www.gnu.org/licenses/lgpl.html" target="_blank">LGPL</a> and adopted from <a target="_blank" href="http://quassel-irc.org/">Quassel IRC</a>.
<br><br><br><br><b>Special thanks go to:</b><br>
<b>brot</b> for bugfixing, testing, helping, finding features and bugs, building the environment, advertising, motivating and a lot more.<br>
<b>The Quassel IRC Community</b> for feedback, finding bugs and using.<br>
<b><a target="_blank" href="http://quassel-irc.org/">The Quassel IRC Team</a></b> for developing such a great IRC-Client.<br><br>

</center>