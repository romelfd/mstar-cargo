<?php 
ob_start();
?>
<br/>
Hello <?php echo $agent_name; ?>,
<br/>
<br/>
Ticket <strong>#<?php echo $ticket_number; ?></strong> has been assigned to <?php echo str_replace("_"," ",$new_department); ?> department.
<br/>
<br/>
<br/>
<strong>MStar Agency Ship Agencies, Inc.</strong>

<?php 

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>