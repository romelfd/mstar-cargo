<?php
ob_start();
?>

Hello <?php echo $client_name; ?>,
<br/>
<br/>
Your ticket <strong>#<?php echo $ticket_number; ?></strong> status has been changed to <strong><?php echo $ticket_status; ?></strong>.
<br/>
<br/>
<br/>
<strong>MStar Agency Ship Agencies, Inc.</strong>

<?php 

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>