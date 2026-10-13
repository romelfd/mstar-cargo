<?php 

ob_start();

?>
<div class="p-2">
<?php     

if (count($client) > 0) {
  $client_id = $client->data->ID;
  $cfields = get_user_meta($client_id);

?>
       
            <input type="hidden" value="<?php echo $client_id; ?>" id="client_id_t" />
            <ul class="grid-container">
                 <li class="grid-item text-primary">
                    <label>Name:</label>
                    <?php echo $client->data->display_name; ?>
                </li>
                <li class="grid-item text-primary">
                    <label>Email:</label>
                    <?php echo $client->data->user_email; ?>
                </li>
                <li class="grid-item text-primary">
                    <label>Company Name:</label>
                    <?php echo $cfields['company_name'][0]; ?>
                </li>
                <li class="grid-item text-primary">
                    <label>Contact No:</label>
                    <?php echo $cfields['contact_number'][0]; ?>
                </li>
                <li class="grid-item text-primary">
                    <label>Address:</label>
                    <?php echo $cfields['address'][0]; ?>
                </li>
            </ul>
  
<?php  } else { ?>
        No results found.
<?php } ?>
       </div>
<?php


$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>