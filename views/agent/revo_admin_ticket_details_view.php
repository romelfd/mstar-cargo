<?php 
ob_start();
$prev_dept = '';
$next_dept = '';
if ($cfields['ticket_department'][0] == 'accounting') {
    $prev_dept = array() ;
    $next_dept = array('documentation') ;
}

if ($cfields['ticket_department'][0] == 'documentation') {
    $prev_dept = array('accounting') ;
    $next_dept = array('documentation_supervisor') ;
}

if ($cfields['ticket_department'][0] == 'documentation_supervisor') {
    $prev_dept = array('accounting', 'documentation') ;
    $next_dept = array('operations') ;
}

if ($cfields['ticket_department'][0] == 'operations') {
    $prev_dept = array('accounting','documentation','documentation_supervisor') ;
    $next_dept = array();
}
?>
<div class="card-body">
        <h5 class="card-title">Ticket # <?php echo $cfields['ticket_number'][0]; ?></h5>
        <div id="msg-alert-1" class="alert alert-success mt-2" role="alert" style="display: none;">
            
        </div>
        <div class="row" id="ticket_details_row">
            <div class="col-md-6">
                 <div class="card" style="height: 100%;">
                    <div class="card-header bg-light">Ticket Infomation</div>   
                        <dl class="row">
                            <dt class="col-sm-4 bg-secondary text-white pt-1 pb-1">Ticket #</dt>
                            <dd class="col-sm-8 bg-success text-white pt-1 pb-1"><?php echo $cfields['ticket_number'][0]; ?></dd>
                            <dt class="col-sm-4 pt-1 pb-1">Type</dt>
                            <dd class="col-sm-8 bg-light  pt-1 pb-1"><?php echo ucfirst($cfields['ticket_type'][0]); ?></dd>
                            <dt class="col-sm-4 pt-1 pb-1" style="font-size: 12px;">B/L Number</dt>
                            <dd class="col-sm-8 bg-light  pt-1 pb-1"><?php echo $cfields['bol_number'][0]; ?></dd>
                            <dt class="col-sm-4 pt-1 pb-1">Status</dt>
                            <dd class="col-sm-8 bg-light  pt-1 pb-1"><?php echo ucfirst(str_replace("_"," ",$cfields['ticket_status'][0])); ?></dd>
                            <dt class="col-sm-4 pt-1 pb-1" style="font-size: 12px;">Department</dt>
                            <dd class="col-sm-8 bg-light pt-1 pb-1" ><?php echo ucfirst($cfields['ticket_department'][0]); ?></dd>
                            <?php if ($ticket_freetime['freetime'] != ''){ ?>
                                <dt class="col-sm-4 pt-1 pb-1">Free time</dt>
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
                        <form id="form_ticket_details">        
                            <!-- FREETIME -->  
                            <input type="hidden" id="ticket_department" name="ticket_department" value="<?php echo $cfields['ticket_department'][0]; ?>" />
                            <?php  if (in_array('documentation', $ticket_agent_departments)) { ?>
                            <div class="form-group col-md-12">
                                <div class="mr-2 p-0 "> 
                                    <label class="col-form-label-sm" style="justify-content: left;">Set Free time</label>
                                    <select name="ticket_freetime[freetime]" id="ticket_freetime" class="form-control form-control-sm" style="width: 100%">
                                        <option value="0">Choose option</option>
                                    <?php foreach ($ticket_free_time_options as $ticket_free_time_option_key => $ticket_free_time_option) { ?>            
                                            <?php 
                                            $selected = '';
                                            if ($ticket_freetime['freetime'] == $ticket_free_time_option_key) { 
                                                $selected = ' selected="selected" ';
                                            } ?>
                                            <option value="<?php echo $ticket_free_time_option_key; ?>"  <?php echo $selected; ?>><?php echo $ticket_free_time_option; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                                <?php 
                                        $show_dd = false;
                                        $show_cb = false;
                                        if ($ticket_freetime['freetime'] == 'detention_demurrage') { 
                                            $show_dd =  true;
                                        }
                                        if ($ticket_freetime['freetime'] == 'combined') { 
                                            $show_cb =  true;
                                         }
                                
                                ?>
                                <div id="detention_demurrage" class="freetime_fields" style="<?php echo ($show_dd) ? 'display:block;' : 'display:none;';?>">
                                    <div class="mr-2 p-0" >
                                        <label class="col-form-label-sm"  style="justify-content: left;">Detention <small>(No of Days)</small></label>
                                        <input type="text" class="form-control form-control-sm" name="ticket_freetime[detention][no_of_days]" id="freetime_no_of_days"  value="<?php echo $ticket_freetime['detention']['no_of_days']; ?>"  style="width:100%;">
                                    </div>
                                    <div class="mr-2 p-0" >
                                        <label class="col-form-label-sm"  style="justify-content: left;">Demurrage <small>(No of Days)</small></label>
                                        <input type="text" class="form-control form-control-sm" name="ticket_freetime[demurrage][no_of_days]" id="freetime_no_of_days"  value="<?php echo $ticket_freetime['demurrage']['no_of_days']; ?>"  style="width:100%;">
                                    </div>
                                </div>
                                <div id="combined" class="freetime_fields" style="<?php echo ($show_cb) ? 'display:block;' : 'display:none;';?>">
                                    <div class="mr-2 p-0">
                                        <label class="col-form-label-sm"  style="justify-content: left;">Choose date</label>
                                        <input type="date" class="form-control form-control-sm" name="ticket_freetime[date]" id="freetime_date" value="<?php echo $ticket_freetime['date']; ?>" style="width: 100%">
                                    </div>
                                    <div class="mr-2 p-0" >
                                        <label class="col-form-label-sm"  style="justify-content: left;">Combined <small>(No of Days)</small></label>
                                        <input type="text" class="form-control form-control-sm" name="ticket_freetime[combined][no_of_days]" id="freetime_no_of_days_d"  value="<?php echo $ticket_freetime['combined']['no_of_days']; ?>"  style="width:100%;" readonly>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <!-- END FREETIME -->            
                        </form>
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
                <form id="commentform" class="comment-form" style="display: none;">
                    <p class="comment-form-comment">
                        <label for="comment">Write a Comment</label><br>
                        <textarea id="comment" name="comment" aria-required="true" class="form-control"></textarea>
                    </p>
                    <p class="form-submit">
                        <input name="submit" type="submit" id="submit" class="btn btn-success btn-sm" value="Send"> 
                        <input type="hidden" name="comment_post_ID" value="<?php echo $ticket[0]->ID; ?>" id="comment_post_ID">
                        <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                    </p>
                </form>
               </div>
               </div>
            </div>
            <div id="client_documents" class="col-md-12 mt-4">
            <div class="card">
            <div class="card-header bg-light">Documents</div>
            <div class="card-body">  
                <div class="table-responsive table-sm table-striped table-bordered">
                    <form id="form_client_documents">
                <table class="table mt-2 table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th style="width: 300px" scope="col">Filename</th>
                            <th style="width: 100px" scope="col">Date Submitted</th>
                            <th scope="col">Status</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    

                    $ticket_documents= array();
                    if (isset($cfields['ticket_documents'])) {
                        $ticket_documents = unserialize($cfields['ticket_documents'][0]);
                    }
                    if (count($ticket_documents) > 0 )  $n=0; {
                        foreach ($ticket_documents as $document) { $n++; 
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
                        ?>
                        <input type="hidden" name="ticket_documents[<?php echo $n; ?>][filename]" value="<?php echo $document['filename']; ?>">
                        <?php               
                        if ($file_status == 'approved') {
                            $file_status_class = ' file-status-approved-c';
                        } else if ($file_status == 'disapproved') {
                            $file_status_class = ' file-status-disapproved-c';
                        } else if ( $file_status == 'replaced') {
                            $file_status_class = ' file-status-replaced-c';
                        } else {
                            $file_status_class = '';
    
                        } ?>
                        
                        <tr class="document_row<?php echo $file_status_class; ?>">
                            <td>
                            <input type="hidden" value="<?php echo $n; ?>" class="document_number"/>
                            <input type="hidden" name="ticket_documents[<?php echo $n; ?>][filename]" value="<?php echo $document['filename']; ?>" class="document_filename"/>
                            <?php echo $n; ?></td>
                            <td class="document_uploaded_filename"><?php echo $document['filename']; ?></td>
                            <td><?php echo $date_uploaded; ?></td>
                            <td class="text-center">
                                <select name="ticket_documents[<?php echo $n; ?>][file_status]" class="file_status">
                                    <option value="">Choose</option>
                                    <option value="approved" <?php echo ($file_status == 'approved') ? ' selected' : ''; ?>>Approved</option>
                                    <option value="disapproved" <?php echo ($file_status == 'disapproved') ? ' selected' : ''; ?>>Disapproved</option>
                                </select>
                            </td>
                            <td>
                                <textarea class="file_remarks" name="ticket_documents[<?php echo $n; ?>][file_remarks]"><?php echo $file_remarks; ?></textarea>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-success" target="blank" href="<?php echo $file_url;?>">
                                    View
                                </a>
                                <a class="btn btn-sm btn-secondary replace-file upload_douments_button" href="#">Replace</a>
                                          
                                <a class="btn btn-sm btn-primary" target="blank" href="<?php  echo plugins_url("../../_inc/download.php",__FILE__ )."?folder=". $cfields['ticket_number'][0] ."&filename=".$document['filename']; ?>"">
                                    Download
                                </a>
                            </td>
                        </tr>
                           
                       <?php 
                        }
                    }
                    ?>
                    </tbody>
                </table>
                </form>
                </div> 
                <button style="display: none;" id="create_ticket" class="btn btn-primary float-right mr-2" disabled>
                                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                                    <span>Submit</span>
                                                </button> 
                <input type="hidden" value="0" name="files_count"  id="files_count">
                <input type="hidden" id="ticket_number" value="<?php echo $cfields['ticket_number'][0];?>" />
                <button data-id="<?php echo $cfields['ticket_number'][0];?>" class="float-right download_documents_button btn btn-primary">Download Documents</button>  
                </div>
                </div>
            </div>
           
            <div class="card-body col-md-12">
              
                    <?php  if (in_array('operations', $ticket_agent_departments)) { 
                        $ticket_webcro = '';
                        $ticket_filename_cro = '';
                        if (isset($cfields['ticket_web_cro'])) {
                            $ticket_webcro = $cfields['ticket_web_cro'][0];
                        }

                        if (isset($cfields['ticket_web_cro'])) {
                            $ticket_filename_cro = $cfields['ticket_filename_cro'][0];
                        }

                        ?>
                    <div class="row mt-2">  
                        <div class="col-md-12">
                                <div class="card border-warning mb-3" style="max-width: 100%">
                                    <div class="card-body text-dark">
                                    <h5 class="card-title">Container Release Order</h5>
                                    <p class="card-text"> Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                            <form action="<?php  echo plugins_url("../../_inc/fileupload.php",__FILE__ ); ?>" id="ticket_web_cro_form" name="ticket_web_cro_form" enctype="multipart/form-data">
                                                <div class="input-group col-md-6 p-0">
                                                    <select class="custom-select" id="ticket_web_cro" name="ticket_web_cro">
                                                        <option selected="0">Choose</option>
                                                        <option value="https://acts.ictsi.com/" <?php echo ($ticket_webcro == 'https://acts.ictsi.com/') ? 'selected' : ''; ?> )>ACTS.ICTSI.COM</option>
                                                        <option value="https://web.asianterminals.com.ph/" <?php echo ($ticket_webcro == 'https://web.asianterminals.com.ph/') ? 'selected' : ''; ?>>WEB.ASIANTERMINALS.COM.PH</option>
                                                        <option value="https://www.gofast.ph" <?php echo ($ticket_webcro == 'https://www.gofast.ph') ? 'selected' : ''; ?>>GOFAST.PH</option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button id="revo_go_web_cro" class="btn btn-primary" type="button">GO</button>
                                                    </div>
                                                </div>

                                                <div class="web_cro_upload col-md-6 p-0">
                                                    <div class="input-group mt-3">
                                                            <label class="text-dark" style="flex-basis: 100%">Upload CRO</label><br/>
                                                            <?php if($ticket_filename_cro != '') { ?>
                                                            <div class="mb-2 mt-2" style="flex-basis: 100%;">
                                                                  <a href="<?php echo plugins_url("../../uploads/webcro",__FILE__ ); ?>/<?php echo $ticket_filename_cro; ?>" target="_blank"><i class="fas fa-file-pdf" style="font-size: 20px;"></i> <?php echo $ticket_filename_cro; ?></a>
                                                            </div>
                                                            <?php } ?>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-input" name="ticket_file_cro" id="ticket_file_cro">
                                                                <input type="hidden" name="ticket_filename_cro" value="<?php echo $ticket_filename_cro; ?>" id="ticket_filename_cro">
                                                                <input type="hidden" name="ticket_number_f" value="<?php echo $cfields['ticket_number'][0]; ?>">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>  
                                                </div>
                                                </form>    
                                    </div>
                            </div>
                     </div>   
                    <?php } ?>
               

                    <div class="col-sm-12 mt-3 form-inline flex-row justify-content-end ">
                        <input type="hidden" name="ticket_post_id" id="ticket_post_id" value="<?php echo $ticket[0]->ID; ?>" >
                        <div class="input-group mr-2">
                           <select class="custom-select" id="agent_department_option">
                                <option selected="0">Choose Department</option>
                                <?php if (count($prev_dept) > 0) { ?>
                                    <?php foreach ($prev_dept as $dept_p ) { ?>
                                    <option value="<?php echo $dept_p; ?>"><?php echo ucfirst(str_replace("_"," ", $dept_p));  ?></option> 
                                <?php }
                                 } ?>
                                 <?php if (count($next_dept) > 0) { ?>
                                    <?php foreach ($next_dept as $dept_n ) { ?>
                                    <option value="<?php echo $dept_n; ?>"><?php echo ucfirst(str_replace("_"," ",$dept_n));  ?></option> 
                                <?php }
                                 } ?>
                            </select>
                            <div class="input-group-append">
                                <button id="revo_move_ticket" class="btn btn-info" type="button">Submit</button>
                            </div>
                        </div>
                        <div class="input-group">
                            <button id="revo_save_ticket" type="submit" class="btn btn-md btn-primary mr-2">Save</button> 
                            <button  id="revo_cancel" type="button" class="btn btn-md btn-success">Cancel</button>
                        </div>

                      
                     </div>   

                    </div>
                </div>
             


        </div>

                                
</div>

          
                        
<?php

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>