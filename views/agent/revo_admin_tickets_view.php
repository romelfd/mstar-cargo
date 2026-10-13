<?php 

ob_start();
$current_user_departments_r = unserialize($current_user_departments);

?>
        <div class="table-responsive">
    <table class="table mt-3 table-sm table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Type</th>
                <th scope="col">Client name</th>
                <th scope="col">Date Submitted</th>
                <th scope="col">Date Updated</th>
                <th scope="col">Status</th>
                <th scope="col">Department</th>
                <th scope="col">Message</th>
                <?php if (in_array($agent_department, $current_user_departments_r)) { ?>
                <th scope="col">Action</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php 
              $client_tickets_data = $client_tickets['data'];
              $client_tickets_pagination = $client_tickets['pagination']['pages'];
              $client_tickets_pagination_paged = $client_tickets['pagination']['paged'];
              $client_tickets_pagination_prev = $client_tickets['pagination']['prev'];
              $client_tickets_pagination_next = $client_tickets['pagination']['next'];
            
            
            
            if (count($client_tickets_data) > 0) { ?>
                <?php foreach ($client_tickets_data as $ticket) { 
                        $ticket_cfields = get_post_custom( $ticket->ID );
                        $all_client_meta = get_user_meta($ticket_cfields['client_id'][0]);
                        $client_username = $all_client_meta['first_name'][0] . ' ' . $all_client_meta['last_name'][0];
                        $latest_comment ='';
                        $args = array(
                            'number' => '1',
                            'post_id' => $ticket->ID
                        );
                        $comments = get_comments($args);
                        if (count($comments) > 0) {
                            $latest_comment =  $comments[0]->comment_content;
                        }
                       
                        if( $ticket_cfields['ticket_status'][0] == 'for_verification' ) {
                            $bclass = 'badge-primary';
                            $bstatus = 'For verification';
                        }

                        if( $ticket_cfields['ticket_status'][0] == 'for_checking' ) {
                            $bclass = 'badge-warning';
                            $bstatus = 'For Checking';
                        }

                        if( $ticket_cfields['ticket_status'][0] == 'for_web_cro' ) {
                            $bclass = 'badge-info';
                            $bstatus = 'For Web CRO';
                        }

                        if( $ticket_cfields['ticket_status'][0] == 'approved' ) {
                            $bclass = 'badge-success';
                            $bstatus = 'Approved';
                        }

                        if( $ticket_cfields['ticket_status'][0] == 'completed' ) {
                            $bclass = 'badge-success';
                            $bstatus = 'Completed';
                        }
                        
                        
                        
                        ?>
                        <tr>
                            <th scope="row"><?php echo  $ticket_cfields['ticket_number'][0]; ?></th>
                            <th scope="row"><?php echo ucfirst($ticket_cfields['ticket_type'][0]); ?></th>
                            <td><?php echo $client_username; ?></td>
                            <td><?php echo get_the_date( 'M d Y H:i:s', $ticket->ID ); ?></td>
                            <td><?php echo date('M d Y H:i:s', strtotime($ticket->post_modified)); ?></td>
                            <td>
                                <span class="p-1 badge <?php echo $bclass; ?>"><?php echo $bstatus; ?></span>
                            </td>
                            <th scope="row"><?php echo str_replace("_", " ", $ticket_cfields['ticket_department'][0]); ?></th>
                            <td><?php echo $latest_comment; ?></td>
                            <?php if (in_array($agent_department, $current_user_departments_r)) { ?>
                            <td>
                                <button data-id="<?php echo $ticket->ID; ?>" class="btn btn-sm btn-success action_view">View</button>
                            </td>
                            <?php } ?>
                        </tr>
                <?php } ?>
            <?php } else { ?> 
                <tr>
                    
                        <td colspan="9">
                            <div class="alert alert-danger mt-1" role="alert">
                                No tickets
                            </div>
                        </td>
                    
                </tr>
            <?php } ?>    

        </tbody>
    </table>

    <div id="agent_tickets_view" class="pagination-wrap float-right">        
            <nav>
                <ul class="pagination">
                    <?php if ( $client_tickets_pagination_prev ) { ?>
                    <li class="page-item" >
                        <a class="page-link prev-link" rel="<?php echo $client_tickets_pagination_prev; ?>" href="#" aria-label="Previous" >
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ( $client_tickets_pagination > 0) { 
                             $n = 1;
                             while ($n <= $client_tickets_pagination)  {  
                                $addclass ='';
                                 if ($n == $client_tickets_pagination_paged) $addclass=" active ";?>
                                <li class="page-item <?php echo $addclass; ?>"><a class="page-link" href="#" rel="<?php echo $n; ?>"><?php echo $n; ?></a></li>
                             <?php  $n++;
                             } 
                         } ?>   
                    <?php if ( $client_tickets_pagination_next ) { ?>
                    <li class="page-item">   
                        <a class="page-link next-link" rel="<?php echo $client_tickets_pagination_next; ?>" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>

        
    </div>

<?php

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>