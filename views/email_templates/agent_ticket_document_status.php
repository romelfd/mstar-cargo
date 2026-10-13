<?php 
ob_start();
?>
<br/>
Hello <?php echo $agent_name; ?>,
<br/>
<br/>
Ticket <strong>#<?php echo $ticket_number; ?></strong> document status has changed.
<br/>
<br/>

<?php if (count($document_updates) > 0 ) { 
    foreach ($document_updates as $document_update) {
    ?>
    <strong>Status: </strong> <?php echo $document_update['text']; ?><br/>
    <?php if ($document_update['remarks'] != '') { ?>
    <strong>Remarks: </strong> <?php echo $document_update['remarks']; ?>
    <?php } ?>
    <br/><br/>      
<?php } ?>
<?php } ?>
<br/>
<strong>MStar Agency Ship Agencies, Inc.</strong>

<?php 

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>