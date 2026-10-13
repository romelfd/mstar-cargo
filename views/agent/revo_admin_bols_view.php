<?php 

ob_start();
?>

<div class="card mb-3 border-primary">
    <div class="card-body text-primary">
        <h5 class="card-title">Bill of Lading</h5>
        <?php if ($add_bol_success) { ?>
        <div id="msg-alert-1" class="alert alert-success mt-2" role="alert">
            BL# <?php echo $bol_number . ' ' . $bol_message; ?> 
        </div>
        <?php } ?>

        <a href="#" id="add_new_bol" class="add_bol_btn float-right"><i class="fa fa-plus" aria-hidden="true"></i> Add B/L</a>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    
                                  
        <div class="table-responsive">
        <table class="mt-3 table-sm table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">B/L#</th>
                    <th scope="col">Container Number</th>
                    <th scope="col">Container Type</th>
                    <th scope="col">Date Submitted</th>
                    
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $bols_data = $bols['data'];
                $bols_pagination =$bols['pagination']['pages'];
                $bols_pagination_paged = $bols['pagination']['paged'];
                $bols_pagination_prev = $bols['pagination']['prev'];
                $bols_pagination_next = $bols['pagination']['next'];
                if (count($bols_data) > 0) { ?>
                    <?php foreach ($bols_data as $bol) { 
                        $bols_info = get_post_meta($bol->ID);
                        ?>
                           
                            <tr>
                                <th scope="row"><?php echo $bols_info['bol_number'][0]; ?></th>
                                <td><?php echo $bols_info['container_number'][0]; ?></td>
                                <td><?php echo $bols_info['container_type'][0]; ?></td>
                                <td><?php echo get_the_date( 'M d Y H:i:s', $bol->ID ); ?></td>
                                <td>
                                    <button id="view_bol" data-id="<?php echo $bol->ID; ?>" class="btn btn-sm btn-success view_bol_btn">View</button>
                                    <button id="edit_bol" data-id="<?php echo $bol->ID; ?>" class="btn btn-sm btn-warning edit_bol_btn">Edit</button>
                                    <button id="delete_bol" data-id="<?php echo $bol->ID; ?>" class="btn btn-sm btn-danger delete_bol_btn">Delete</button>
                                </td>
                            </tr>
                    <?php } ?>
                <?php } else { ?> 
                    <tr>
                        
                            <td colspan="8">
                                <div class="alert alert-danger mt-1" role="alert">
                                    No records found.
                                </div>
                            </td>
                        
                    </tr>
                <?php } ?>    

            </tbody>
        </table>

        <div id="bols_view" class="pagination-wrap float-right ">        
            <nav>
                <ul class="pagination">
                    <?php if ( $bols_pagination_prev ) { ?>
                    <li class="page-item" >
                        <a class="page-link prev-link" rel="<?php echo $bols_pagination_prev; ?>" href="#" aria-label="Previous" >
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ( $bols_pagination > 0) { 
                             $n = 1;
                             while ($n <= $bols_pagination)  {  
                                $addclass ='';
                                 if ($n == $bols_pagination_paged) $addclass=" active ";?>
                                <li class="page-item <?php echo $addclass; ?>"><a class="page-link" href="#" rel="<?php echo $n; ?>"><?php echo $n; ?></a></li>
                             <?php  $n++;
                             } 
                         } ?>   
                    <?php if ( $bols_pagination_next ) { ?>
                    <li class="page-item">   
                        <a class="page-link next-link" rel="<?php echo $bols_pagination_next; ?>" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
    <div class="border-secondary mb-3 mt-3" id="bol_upload">
                                  
    </div>
    </div>

<!-- Modal -->
<div class="modal fade upload_documents_modal" id="bolModal" tabindex="-1" role="dialog" aria-labelledby="bolModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bolModalTitle">View BL</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
      </div>
      <div class="modal-footer">
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