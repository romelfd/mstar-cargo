<?php 
ob_start();
?>
<br/>
Hello <?php echo $client_name; ?>,
<br/>
<br/>

Your MStar account has been approved, you can now login to your online account. Your details are as follows:
<br/><br/>
Email Address: <?php $client_email_address; ?><br/>
Password: The password you originally set up for your account
<br/><br/>
To Login to our site visit us at http://mstar-cargo.com/online-processing/
<br/><br/>
Thank you!

<br/>
<br/>
<strong>MStar Agency Ship Agencies, Inc.</strong>

<?php 

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>