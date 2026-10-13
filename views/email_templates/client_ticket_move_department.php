<?php
ob_start();
?>

Hello <?php echo $client_name; ?>,
<br/>
<br/>
Your ticket <strong>#<?php echo $ticket_number; ?></strong> has been submitted to <?php echo str_replace("_"," ",$new_department); ?> department.
<br/>
<br/>
<br/>
<strong>MStar Agency Ship Agencies, Inc.</strong>

<?php 

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>