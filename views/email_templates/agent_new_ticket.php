<?php 
ob_start();
?>
<br/>
Hello <?php echo $agent_name; ?>,
<br/>
<br/>
<?php echo $client_name; ?> has submitted a new ticket.
<br/>
<br/>
Ticket Number: <?php echo $ticket_number; ?>
<br/>
Ticket Type: <?php echo $ticket_type; ?>
<br/>
<?php if ($ticket_type == 'import') { ?>
B/L Number: <?php echo $bol_number; ?>
<?php } ?>
<br/>
<br/>
<strong>MStar Agency Ship Agencies, Inc.</strong>

<?php 

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>