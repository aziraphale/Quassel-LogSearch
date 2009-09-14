<?
    require("config.php");
    require_once('classes/parser.class.php');
    require_once('classes/'.$backend.'.class.php');
    $backend=new backend();
    
    $bufferid = $_REQUEST['bufferid'];
    $messageid = $_REQUEST['messageid'];
    $state = $_REQUEST['state'];
    $base = $_REQUEST['base'];
    $types = $_REQUEST['types'];
        
$array = $backend ->moremore($bufferid,$messageid,$state,$types);
echo $array[0];
//echo $bufferid,$messageid,$state;
?>
<script type="text/javascript">
document.getElementById('<?=$state.$base?>').innerHTML = "<?=$array[1]?>";
</script>