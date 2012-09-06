<?php $wpdb->query("UPDATE ".$wpdb->prefix."oops SET oops_status = 0");?>
<div id="message" class="updated fade">
 
Online Offline Checker (OOPS) <strong>Reseted</strong>.</div>
<div class="wrap">
<h2>OOPS Admin</h2>
<?php
$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."oops");
foreach($results as $result)
{
    echo $result->oops_status." : ";
    echo $result->lastmoddttm."";
}
?>
 
<a href="?page=<?php echo $_GET['page']; ?>&oops=reset">
    Reset Table
</a>
</div>
