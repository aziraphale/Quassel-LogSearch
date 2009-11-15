<?php
//
//      Quassel Backlog Search
//      developed 2009 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//

    require_once('debuger.php');
    require("config.php");
    require_once('classes/parser.class.php');
    require_once('classes/'.$backend.'.class.php');
    $backend=new backend();
    
    $bufferid = $_REQUEST['bufferid'];
    $messageid = $_REQUEST['messageid'];
    $state = $_REQUEST['state'];
    $base = $_REQUEST['base'];
    $types = $_REQUEST['types'];
    $sorting = $_REQUEST['sorting'];
        
$array = $backend ->moremore($bufferid,$messageid,$state,$types);
        if($sorting == 1){
        $array[0] = array_reverse($array[0]);}
        
        $array[0] = implode('',$array[0]);
echo $array[0];
//echo $bufferid,$messageid,$state;
?>
<script type="text/javascript">
document.getElementById('<?=$state.$base?>').innerHTML = "<?=$array[1]?>";
</script>