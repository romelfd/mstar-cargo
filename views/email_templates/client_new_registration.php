<?php 
ob_start();
?>
<br/>
Hello <?php echo $client_name; ?>,
<br/>
<br/>
Registration successful.
<br/><br/>
The Administrator will review the information that has been submitted and either approve or deny your request. 
<br/><br/>
You will receive an email with instructions on what you will do next. Thanks for your patience.

<br/>
<br/>
<strong>MStar Agency Ship Agencies, Inc.</strong>

<?php 

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>