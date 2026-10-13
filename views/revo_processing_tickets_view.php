<?php 
    ob_start();
    
?>

<?php 
if ( is_user_logged_in() ) {
    $current_client = $this->current_user->data;
    if ($this->current_user->roles[0] == 'custom_client') {
 ?>
<div class="card">
        <h5 class="card-header text-white bg-primary">Tickets Being Process</h5>
            <div class="card-body">
<?php
if (count($processing_tickets) > 0 ) {
?>
                <div class="row row-cols-1 row-cols-3">
                    <?php foreach ( $processing_tickets as $ticket ) {  
                        $ticket_cfields = get_post_custom( $ticket->ID ); 
                        $ticket_department = $ticket_cfields['ticket_department'][0];
                        if ($ticket_cfields['ticket_status'][0] == 'approved') { 
                            if (isset($ticket_cfields['ticket_department_prev'])) {
                                if ($ticket_cfields['ticket_department'][0] != $ticket_cfields['ticket_department_prev']) {
                                    $ticket_department = $ticket_cfields['ticket_department_prev'][0];
                                } else {
                                    $ticket_department = $ticket_cfields['ticket_department'][0];
                                }
                            }
                        }

                       

                    ?>
                    <div class="col mb-4">
                        <div class="card alert-primary">
                            
                            <div class="card-body ">
                              
                                <p class="card-text text-dark font-weight-bold">
                                    Ticket No: <span class="font-weight-normal"><?php echo $ticket_cfields['ticket_number'][0] ?></span><br/>
                                    Txn Type: <span class="font-weight-normal"><?php echo ucfirst($ticket_cfields['ticket_type'][0]) ?></span><br/>
                                    Desk: <span class="font-weight-normal"><?php echo ucfirst( str_replace("_"," ", $ticket_department) ); ?></span><br/>
                                    Status: <span class="font-weight-normal"><?php echo ucfirst(str_replace("_"," ", $ticket_cfields['ticket_status'][0])); ?></span>
                                    <?php if ($ticket_cfields['ticket_status'][0] == 'approved') { ?>
                                    <br/>Next Desk: <span class="font-weight-normal"><?php echo ucfirst( str_replace("_", " ", $ticket_cfields['ticket_department'][0])); ?></span><br/>
                                    <?php } ?>
                                </p>
                                </div>
                                <div class="card-footer bg-dark">
                                <small class="text-white">Last updated, <?php 
                                
                                echo $lastmodified = get_the_modified_time('U', $ticket);
                                echo $this->revo_convert_to_time_ago($lastmodified); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php }  ?>
                </div>        
<?php } else { ?>
    <div class="alert alert-danger" role="alert">
        No ticket being process...
    </div>
<?php } ?>

   </div>
</div>
<?php }} ?>
<?php
$content = ob_get_contents();
ob_end_clean();
$output = $content;



?>