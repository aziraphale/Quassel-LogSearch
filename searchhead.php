<div style="width:100%; background: #6699cc;color:white; border-bottom:1px #000000 solid;" ><div style="padding: 20px;">
<img style="float:left;margin-top:-15px;margin-bottom:-50px; margin-right:100px;" alt="" src="style/quassel.png"><span style="font-size: 20pt;color:white;">Quassel Backlog Search</span><br><br>
<form onsubmit="javascript:such(); return false" action="#">
<input value="" id="input" size="60" type="text"">&nbsp;<input type="submit" value="Start searching!" class="button">
<br><img src="style/channel.png" style="z-index:1;position:relative;margin:2px;float:left;" alt="Buffer" title="Buffer to search">
<script type="text/javascript">
    <!--
        javascript:document.getElementById('input').focus();
    //-->
</script>
<select  title="Buffer to search" style="float:left;" id="buffer" name="buffername" size="1">

<?php
require_once("config.php");
 $dbconn = pg_connect ("dbname=$dbname user=$user password='$password' port=$port host=$host");
 
// get bufferids und buffernames for user 
   $result = pg_query($dbconn,"SELECT buffername,bufferid FROM buffer WHERE userid = $userid;");

while($search_ary = pg_fetch_array($result)) {
$array[] = $search_ary[0] .'||'. $search_ary[1];
}
natcasesort($array);

foreach($array as $string){
$array2 = explode('||',$string);
if(!empty($array2[0])){
   echo '<option'.$echob.' value="'.$array2[1].'">'.$array2[0].'</option>';    
   }}

pg_close($dbconn); 
?>
</select><img src="style/lines.png" title="Number of results" alt="Lines" style="margin:2px; margin-left:20px;float:left;"><input title="Number of results" value="100" id="number" size="3" type="text" maxlength="4"  autocomplete="off"><br>
</form>
</div></div><center>
<div style="clear:both;width:100%;"><div id="scontent" style="overflow:auto;padding: 20px; text-align:left; vertical-align:top;"><font style="font-size:8pt;"><center>Waiting for search ...</center></font></div></div>