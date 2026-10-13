<?php 
ob_start();
//print_r($cfields);
$prev_dept = '';
$next_dept = '';
if ($cfields['ticket_department'][0] == 'accounting') {
    $prev_dept = array() ;
    $next_dept = array('documents') ;
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
            <div class="col-md-12">
                <div class="card-title">Ticket Information</div>
                
                    <form id="form_ticket_details">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Ticket #</label>
                                <input type="text" class="form-control form-control-sm" value="<?php echo $cfields['ticket_number'][0]; ?>" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Type</label>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ucfirst($cfields['ticket_type'][0]); ?>" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Client</label>
                                <input type="text" class="form-control form-control-sm" value="<?php echo $cfields['client_name'][0]; ?>" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8 form-inline">
                                <div class="mr-2 col-sm-4 p-0 "> 
                                <label class="col-form-label-sm" style="justify-content: left;">Free Time</label>
                                <select name="ticket_freetime[freetime]" class="form-control form-control-sm" style="width: 100%">
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
                                <div class="mr-2 col-sm-5 p-0">
                                <label class="col-form-label-sm"  style="justify-content: left;">Choose date</label>
                                <input type="date" class="form-control form-control-sm" name="ticket_freetime[date]" id="freetime_date" value="<?php echo $ticket_freetime['date']; ?>" style="width: 100%">
                                </div>
                                <div class="col-sm-2 p-0" >
                                <label class="col-form-label-sm"  style="justify-content: left;">Days</label>
                                <input type="text" class="form-control form-control-sm" name="ticket_freetime[no_of_days]" id="freetime_no_of_days"  value="<?php echo $ticket_freetime['no_of_days']; ?>"  style="width:100%;" readonly>
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="col-form-label-sm">Status</label>
                                <input type="text" class="form-control form-control-sm"  value="<?php echo ucfirst(str_replace("_"," ",$cfields['ticket_status'][0])); ?>" disabled/>
                                <input type="hidden" name="ticket_status" value="<?php echo $cfields['ticket_status'][0]; ?>" disabled/>
                            
                            </div>
                        
                            <div class="form-group col-md-2">
                                <label class="col-form-label-sm">Department</label>
                                <input type="text" class="form-control form-control-sm" value="<?php echo ucfirst($cfields['ticket_department'][0]); ?>" disabled>
                           
                                <select name="ticket_department" class="form-control form-control-sm" style="display:none;" id="agent_department">
                                <?php foreach ($agent_department_options as $agent_department_key => $agent_department_option) { ?>            
                                        <?php 
                                        if ( $agent_department_key != 'admin') { 
                                        $selected = '';
                                        if ($cfields['ticket_department'][0] ==  $agent_department_key) { 
                                            $selected = ' selected="selected" ';
                                        } ?>
                                        <option value="<?php echo $agent_department_key; ?>"  <?php echo $selected; ?>><?php echo $agent_department_option; ?></option>
                                <?php }} ?>
                                </select>
                            </div>
                        </div>
                   </form>
                </div>
           
            <div id="client_messages" class="col-md-4 mt-4">
            <div class="card">
                 <div class="card-header bg-light">
                Comments & Messages</div>   
                <div class="card-body">                         
                <ul id="comments_list" class="comment-list comments">

                </ul>
                <hr/>
                <form id="commentform" class="comment-form">
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
            <div id="client_documents" class="col-md-8 mt-4">
            <div class="card">
            <div class="card-header bg-light">Documents</div>
            <div class="card-body">  
                <div class="table-responsive table-sm table-striped table-bordered">
                    <form id="form_client_documents">
                <table class="table mt-2 table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Filename</th>
                            <th scope="col">Date Submitted</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($documents_f) > 0 )  $n=0; {
                        foreach ($documents_f as $document) { $n++; ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $document['filename']; ?></td>
                            <td><?php echo $document['date_uploaded']; ?></td>
                            <td class="text-center">
                                <?php 
                                /* ADD CHECKED */
                                $checked = '';
                                $checked2 = '';
                                $checked_s = '';
                                $checked2_s = '';
                                ?>
                                <span class="file-status-text">
                                <?php    
                                if (in_array($document['filename'], $file_status_r['approved'] )) {
                                    $checked = ' checked ';
                                    $checked_s = ' selected ';
                                } else if (in_array($document['filename'], $file_status_r['disapproved'] )) {
                                    $checked2 = ' checked ';
                                    $checked2_s = ' selected ';
                                }
                                ?>
                                <select name="file_status" class="file_status">
                                    <option>Choose</option>
                                    <option value="approved" <?php echo $checked_s; ?>>Approved</option>
                                    <option value="disapproved" <?php echo $checked2_s; ?>>Disapproved</option>
                                </select>
                                </span>
                                <div style="display:block;">
                                    <input class="file-status file-approved" type="checkbox" name="file_status[approved][]" value="<?php echo $document['filename']; ?>" <?php echo $checked; ?>>
                                    <input class="file-status file-disapproved" type="checkbox" name="file_status[disapproved][]" value="<?php echo $document['filename']; ?>" <?php echo $checked2; ?>>
                                </div>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-success" target="blank" href="<?php echo $document['file_url'];?>">
                                    View
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
                </div>
                </div>
            </div>
           
            <div class="card-body col-md-12">
              
                    <?php  if ($this->current_user_meta['agent_departments'][0] == 'operations') { ?>
                    <div class="row mt-2">  
                        <div class="col-md-12">
                                <div class="card border-warning mb-3" style="max-width: 100%">
                                    <div class="card-body text-warning">
                                    <h5 class="card-title">Container Release Order</h5>
                                    <p class="card-text"> Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                            <div class="input-group col-md-6">
                                            <select class="custom-select" id="ticket_web_cro">
                                                    <option selected="0">Choose</option>
                                                    <option value="https://acts.ictsi.com/">ACTS.ICTSI.COM</option>
                                                    <option value="https://web.asianterminals.com.ph/">WEB.ASIANTERMINALS.COM.PH</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <button id="revo_go_web_cro" class="btn btn-primary" type="button">GO</button>
                                                </div>
                                            </div>


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
                                    <option value="<?php echo $dept_p; ?>"><?php echo ucfirst($dept_p);  ?></option> 
                                <?php }
                                 } ?>
                                 <?php if (count($next_dept) > 0) { ?>
                                    <?php foreach ($next_dept as $dept_n ) { ?>
                                    <option value="<?php echo $dept_n; ?>"><?php echo ucfirst($dept_n);  ?></option> 
                                <?php }
                                 } ?>
                            </select>
                            <div class="input-group-append">
                                <button id="revo_move_ticket" class="btn btn-info" type="button">Submit</button>
                            </div>
                        </div>
                        <div class="input-group">
                            <button id="revo_save_ticket" type="submit" class="btn btn-md btn-primary mr-2">Save</button> 
                            <button  id="revo_cancel" type="button" class="btn btn-md btn-warning">Cancel</button>
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