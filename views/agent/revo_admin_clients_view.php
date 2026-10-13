<?php 

ob_start();
?>
           <div class="row">
                <div class="col-md-12">
                <table class="table mt-3 table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Client name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Date Registered</th>
                            <th scope="col">Date Approved</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($clients) > 0) { $n=0; ?>
                            <?php foreach ($clients as $client) { 
                                $n++; 
                                $user_meta = get_user_meta($client->ID);
                                $client_name = $user_meta['first_name'][0] . ' ' . $user_meta['last_name'][0];
                                $client_approved = 0;
                                if (isset($user_meta['client_approved'])) {
                                    $client_approved = $user_meta['client_approved'][0];
                                }

                                if( $client_approved == null ) {
                                    $bclass = 'badge-warning';
                                    $bstatus = 'For Approval';
                                }

                                if( $client_approved == 0 ) {
                                    $bclass = 'badge-danger';
                                    $bstatus = 'Denied';
                                }

                                if( $client_approved == 1 ) {
                                    $bclass = 'badge-success';
                                    $bstatus = 'Approved';
                                }
                                    
                                ?>
                                    <tr>
                                       <td><?php echo $n; ?></td>
                                       <td><?php echo $client_name; ?></td>
                                       <td><?php echo $client->user_email; ?></td>
                                       <td><?php echo date('M d Y ', strtotime($client->user_registered)); ?></td>
                                       <td><?php echo date('M d Y ', strtotime($client->user_registered)); ?></td>
                                       <td><span class="p-1 badge <?php echo $bclass; ?>"><?php echo $bstatus; ?></span></td>
                                       <td><button id="view_client" data-id="<?php echo $client->ID; ?>" class="btn btn-sm btn-success view_client_btn">View</button> <button data-id="<?php echo $client->ID; ?>" class="btn btn-sm btn-warning approve_client">Approve</button> <button data-id="<?php echo $client->ID; ?>" class="btn btn-sm btn-danger deny_client">Deny</button> </td>

                                    </tr>
                            <?php } ?>
                        <?php } else { ?> 
                            <tr>
                                
                                    <td colspan="7">
                                        <div class="alert alert-danger mt-1" role="alert">
                                            No Clients
                                        </div>
                                    </td>
                                
                            </tr>
                        <?php } ?>    

                    </tbody>
                </table>
                </div>
                
           </div>

           <!-- Modal -->
<div class="modal fade upload_documents_modal" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="clientModalTitle">Client Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
      </div>
      <div class="modal-footer">
        <button id="approve_client_btn" data-id="" class="btn btn-warning approve_client">Approve</button> 
        <button id="deny_client_btn" data-id="" class="btn btn-danger deny_client">Deny</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>