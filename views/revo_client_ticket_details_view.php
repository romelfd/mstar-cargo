<?php 
ob_start();
//print_r($cfields);
?>
<div class="card-body">
        <h5 class="card-title">Ticket # <?php echo $cfields['ticket_number'][0]; ?></h5>
        <div id="msg-alert-2" class="alert alert-success mt-2" role="alert" style="display: none;">
            Ticket has been successfully updated! <span class="font-weight-bold" id="display_ticket_number"></span>
        </div>
        <div class="row" id="ticket_details_row">
                <div class="col-md-6">
                <div class="card" style="height: 100%;">
                     <div class="card-header bg-light">Ticket Infomation</div>   
                        <dl class="row">
                            <dt class="col-sm-4 bg-secondary text-white pt-1 pb-1">Ticket #</dt>
                            <dd class="col-sm-8 bg-success text-white pt-1 pb-1"><?php echo $cfields['ticket_number'][0]; ?></dd>
                            <dt class="col-sm-4 bg-secondary text-white pt-1 pb-1">Type</dt>
                            <dd class="col-sm-8 text-dark bg-light pt-1 pb-1"><?php echo ucfirst($cfields['ticket_type'][0]); ?></dd>
                            <dt class="col-sm-4 bg-secondary text-white pt-1 pb-1">B/L Number</dt>
                            <dd class="col-sm-8 text-dark bg-light pt-1 pb-1"><?php echo $cfields['bol_number'][0]; ?></dd>
                            <dt class="col-sm-4 bg-secondary text-white pt-1 pb-1">Status</dt>
                            <dd class="col-sm-8 text-dark bg-light pt-1 pb-1"><?php echo ucfirst(str_replace("_"," ",$cfields['ticket_status'][0])); ?></dd>
                            <dt class="col-sm-4 bg-secondary text-white pt-1 pb-1">Department</dt>
                            <dd class="col-sm-8 text-dark bg-lightpt-1 pb-1"><?php echo ucfirst( str_replace("_", " ", $cfields['ticket_department'][0])); ?></dd>
                            <?php if ($ticket_freetime['freetime'] != ''){ ?>
                                <dt class="col-sm-4 pt-1 pb-1 bg-secondary text-white">Free time</dt>
                                <dd class="col-sm-8 bg-light pt-1 pb-1">
                                <?php  if ($ticket_freetime['freetime'] == 'detention_demurrage') { ?>
                                            Detention (<?php echo $ticket_freetime['detention']['no_of_days']; ?> days)<br/>
                                            Demurrage (<?php echo $ticket_freetime['demurrage']['no_of_days']; ?> days)
                                <?php } ?>     
                                <?php  if ($ticket_freetime['freetime'] == 'combined') { ?>
                                            Date (<?php echo $ticket_freetime['date']; ?>)<br/>
                                            Combined (<?php echo $ticket_freetime['combined']['no_of_days']; ?> days)
                                <?php } ?>        
                                </dd>
                            <?php } ?>
                        </dl>
                    </div>
                </div>
                <div id="client_messages" class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                        Ticket History</div>   
                        <div class="card-body">                         
                        <ul id="comments_list" class="comment-list comments">

                        </ul>
                        <hr/>
                        <form id="commentform" class="comment-form" style="display:none;">
                            <p class="comment-form-comment">
                                <label for="comment">Write a Comment</label><br>
                                <textarea id="comment" name="comment" aria-required="true" class="form-control" required></textarea>
                            </p>
                            <p class="form-submit" >
                                <input name="submit" type="submit" id="submit" class="btn btn-success btn-sm" value="Send"> 
                                <input type="hidden" name="comment_post_ID" value="<?php echo $ticket[0]->ID; ?>" id="comment_post_ID">
                                <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                            </p>
                        </form>
                    </div>
                </div><!-- END COL -->
                
                
                    
                   
            </div>
           
           
            <div id="client_documents" class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header bg-light">Documents</div>
                <div class="card-body">  
                        <div class="table-responsive">
                            <form id="form_client_documents">
                                <table class="table-striped table-bordered mt-2 table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Filename</th>
                                            <th scope="col">Date Submitted</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Remarks</th>
                                            <th scope="col" style="width: 140px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $ticket_documents= array();
                                    if (isset($cfields['ticket_documents'])) {
                                        $ticket_documents = unserialize($cfields['ticket_documents'][0]);
                                    }
                                    if (count( $ticket_documents ) > 0 )  $n=0; {
                                        foreach ( $ticket_documents  as $document) { $n++; 
                                            $file_status =  $document['file_status'];
                                            $file_remarks = $document['file_remarks'];

                                            if (count($documents_f) > 0) {
                                                foreach ($documents_f as $ticket_document_f) {
                                                    if ( $document['filename'] ==  $ticket_document_f['filename']) {
                                                        $date_uploaded = $ticket_document_f['date_uploaded'];
                                                        $file_url = $ticket_document_f['file_url'];
                                                    }
                                                }
                                            }
                                       
                                        if ($file_status == 'approved') {
                                            $file_status_class = 'file-status-approved-c';
                                        } else if ($file_status == 'disapproved') {
                                            $file_status_class = 'file-status-disapproved-c';
                                        } else {
                                            $file_status_class = '';

                                        } ?>
                                        
                                        <tr class="<?php echo $file_status_class; ?>">
                                            <td>
                                            <input type="hidden" value="<?php echo $n; ?>" class="document_number"/>
                                            <input type="hidden" name="ticket_documents[<?php echo $n; ?>][filename]" value="<?php echo $document['filename']; ?>" class="document_filename"/>
                                            <input type="hidden" name="ticket_documents[<?php echo $n; ?>][file_status]" value="<?php echo $file_status; ?>" class="document_file_status"/>
                                            <input type="hidden" name="ticket_documents[<?php echo $n; ?>][file_remarks]" value="<?php echo $file_remarks; ?>" class="document_file_remarks"/>
                                            
                                            <?php echo $n; ?></td>
                                            <td class="document_uploaded_filename"><?php echo $document['filename']; ?></td>
                                            <td><?php echo $date_uploaded; ?></td>
                                            <td class="text-center">
                                                <?php 
                                                /* ADD CHECKED */
                                                ?>
                                                <span class="file-status-text">
                                                <?php    
                                                    if ($file_status == '') {
                                                        echo 'For approval';
                                                    } else {
                                                        echo $file_status;
                                                    }
                                                ?>
                                                </span>    
                                            </td>
                                            <td>
                                                <?php echo $file_remarks; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" target="blank" href="<?php echo $file_url;?>">View</a>
                                            <?php if ( $file_status == 'disapproved') { ?>
                                            <a class="btn btn-sm btn-secondary replace-file upload_douments_button" href="#">Replace</a>
                                            <?php } ?>
                                            </td>
                                        </tr>
                                        
                                    <?php 
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </form>
                        </div><!-- END TABLE -->   
                        <?php if ($cfields['ticket_status'][0] != 'completed') {?>
                        <div class="float-right">
                                <button class="upload_douments_button ticket-edit btn btn-warning">Add documents</button>
                        </div> 
                        <?php } ?>
                    </div><!-- END CARD -->    
                </div>
            </div>
                          
           <div class="card-body">
              
                <div class="row mt-2">
                   
                    
                    <div class="col-sm-12 mt-3">
                    <input type="hidden" name="ticket_post_id" id="ticket_post_id" value="<?php echo $ticket[0]->ID; ?>" >
                    <button id="revo_cancel" type="button" class="btn btn-lg btn-success float-right">Cancel</button>
                    <button id="revo_client_save_ticket" type="submit" class="btn btn-lg btn-primary float-right mr-2">Save</button> 
                     
                        
                </div>
                </div>
      </div>
</div>
<?php

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>