<?php 
ob_start();
?>
<br/>
Hello <?php echo $agent_name; ?>,
<br/>
<br/>
<?php echo $client_name; ?> has submitted a membership request.

<br/>
<br/>
<strong>MStar Agency Ship Agencies, Inc.</strong>

<?php 

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>