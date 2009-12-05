<?php
//
//      Quassel Backlog Search
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//
 require_once('config.php'); 
 require_once('classes/gettext.class.php');
?>
<div style="margin:-18px;float:right;height:50px; width:50px; right:0px;top:0px;position:relative;z-index:10;background-color:white;"><span style="float:right;margin: 3px;" onclick="javascript:document.getElementById('scontent').innerHTML = '<center>Waiting for search ...</center>';">[ x ]</span></div><center>
<span style="font-size:13pt;"><?=_('Frequently Asked Questions')?></span></center>

<div style="padding-left:150px;text-align:justify;">
<br><br><span><a name="andor" style="margin-left:-40px;"><?=_('Can I search for multiple strings with AND/OR?')?></a></span><br>
<?=_('No, not with the syntax <b>AND</b>/<b>OR</b>.')?><br>
<?=_('But whitespaces will automaticly interpreted as <b>AND</b>.')?><br>
<?php if($backend == "postgresql"){ ?>
<?=_('In addition AND/OR can be searche via <b>regex</b>.')?><br>
<?php } ?>
<?=_('e.g.: "foo bar" will search for every line that contains "foo" <b>AND</b> "bar".')?>

<br><br><br><span><a name="andor" style="margin-left:-40px;"><?=_('Can I search for messages from specific nick/sender?')?></a></span><br>
<?=_('Yes, the syntax <b>sender=</b> will search for messages from the specific sender (or nicks that contain the given sender).<br>Mulitple <i>sender-syntax</i> can be given in one search.')?><br>
<?=_('e.g.: "sender=foo sender=bar foobar" searches lines from "foo" or "bar" that contains the string "foobar".')?>

<br><br><br><span><a name="andor" style="margin-left:-40px;"><?=_('Can I search for multiple chats at once?')?></a></span><br>
<?=_('Yes, the botton <b>switch multiple</b> in <i>advanced search</i> turns the <i>chat selection</i> into multiline,<br> so more than one chat can be selected and searched.')?>

<?php if($backend == "postgresql"){ ?>
<br><br><br><span><a name="andor" style="margin-left:-40px;"><?=_('Can I search with <i>regex</i>?')?></a></span><br>
<?=_('Yes, the checkbox <b>regex</b> in <i>advanced search</i> turns the support of <i>regex-strings</i> on.')?>

<br><br><br><span><a name="andor" style="margin-left:-40px;"><?=_('Can I only search for messages?')?></a></span><br>
<?=_('Or in other words: <b>Can I also search for quits, joins, topic-changes, kicks ...?</b><br>
The <i>only messages filter</i> can be turned of in <i>advanced search</i>.')?>
<?php } ?>

<br><br><br><span><a name="andor" style="margin-left:-40px;"><?=_('Can I search messages just in a given timeperiod?')?></a></span><br>
<?=_('Yes, in <i>advanced search</i> there are two inputboxes where a starttime and a endtime can be given.<br>
<u>Empty</u> or <u>wrong</u> input will be ignored.<br>
The input can contain <u>any</u> <b>US English</b> timeformat. (For help please take a look at the <a href="http://php.net/manual/function.strtotime.php" target="_blank">php-manual</a>)<br>
e.g.: "yesterday", "today", "last week", "march", "12/24/2009"')?>
</div><br><br>