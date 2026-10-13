<?php 
echo realpath('./uploads') . PHP_EOL;
ob_start();



foreach($agent_departments as $agent_department_t) {
    $agent_department_text_r[] =  $agent_department_options[$agent_department_t];
}


$agent_department_text = implode(" - ", $agent_department_text_r);

?>
<div class="card">
      <div class="client-head card-header bg-primary">
        <h5 class="text-white float-left">Welcome  <?php echo $agent_username; ?> | <span class="agent-dept"><?php echo $agent_department_text; ?> Department</span></h5>
        <span class="float-right "><a class="text-white" href="<?php echo wp_logout_url( get_permalink() ); ?>"> <i class="fas fa-power-off"></i>Logout</a></span>
      </div>
    
        <div class="card-body">
           <div class="row">
                <div class="col-md-12">
                    <ul class="agent_nav nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Dashboard</a>
                    </li>
                    <?php if (in_array('admin', $agent_departments)) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false">Clients</a>
                        <div class="dropdown-menu">
                        <a id="all_clients" class="dropdown-item" href="#pills-clients">All</a>
                        <a  id="for_approval_clients"  class="dropdown-item" href="#pills-clients">For Approval</a>
                        <!--<div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Create</a>
                        </div>-->
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="bill_of_lading" data-toggle="pill" href="#pills-bol" role="tab" aria-controls="pills-bol" aria-selected="true">B/L</a>
                    </li>
                    <?php } ?>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="account_profile" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Profile</a>
                    </li>
                    </ul>
                    <div class="card mb-3 tab-content-wrap">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <div class="card mb-3 border-primary">
                                    <div class="card-body text-primary">
                                    <h5 class="card-title">Tickets</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <ul class="nav nav-tabs mt-3" id="ticketsTab">
                                         <?php if (in_array('admin', $agent_departments)) { ?>
                                            <?php foreach ($agent_department_options as $agent_department_k => $agent_department_v) { 
                                            if ( !in_array($agent_department_k, $agent_departments)  && $agent_department_k != 'admin') {  ?>
                                                <li class="nav-item nav-hasaccess" >
                                                <a class="nav-link" rel="<?php echo $agent_id; ?>" data-id="<?php echo $agent_department_k; ?>" href="#"><?php echo ucfirst($agent_department_v); ?></a>
                                                </li>
                                            <?php }} ?>
                                        
                                        <?php } else { ?>
                                          
                                            <?php foreach ($agent_department_options as $agent_department_k => $agent_department_v) { 
                                            if ($agent_department_k != 'admin') { 
                                                $n=0; 
                                                $add_class= '';
                                                if (in_array($agent_department_k, $agent_departments)) { 
                                                    $add_class= ' nav-hasaccess ';
                                                }
                                            ?>

                                                <li class="nav-item <?php echo  $add_class; ?>" >
                                                    <a class="nav-link <?php echo ($n=0) ?  'active' : ''; ?>" rel="<?php echo $agent_id; ?>" data-id="<?php echo $agent_department_k; ?>" href="#"><?php echo ucfirst($agent_department_v); ?></a>
                                                </li>
                                        <?php }
                                            $n++;
                                        }
                                        
                                        } ?>
                                    </ul>

                                    <div class="tab-content" id="ticketsTabContent">
                                        <div class="tab-pane fade show active" id="department-tickets" role="tabpanel" >
                                             
                                        </div>
                                    
                                    </div>

                                  
                                </div>
                                </div>
                                <div class="card border-secondary mb-3 mt-3" id="ticket_detail" style="visibility: hidden;" >
           
                                </div>

                              
                                
                            </div><!-- END HOME -->
                            <div class="tab-pane fade" id="pills-bol" role="tabpanel" aria-labelledby="pills-bol-tab">
                                <div id="agent_bol">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div id="agent_profile">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-clients" role="tabpanel" aria-labelledby="pills-contact-tab">
                                <div id="agent_clients">
                                </div>

                            </div>
                        </div>
                    </div>

      
    </div>
               
</div>



<div class="modal-backdrop fade modal-backdrop2" style="display: none;"></div>

<!-- Modal -->
<div class="modal fade upload_documents_modal " id="uploadModalCenter" tabindex="-1" role="dialog" aria-labelledby="uploadModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload Documents</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="col-sm-12 mt-3">
        <div id="dropzone_container">
            <h6>Drop your files here</h6>
        </div>
        <div id="actions" class="row">
            <div>
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button dz-clickable">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                </span>
                <button type="submit" id="start_ticket_upload" class="btn btn-primary start" style="display: none;">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-danger cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
            </div>
            <div class="col-lg-5">
                <!-- The global file processing state -->
                <span class="fileupload-process">
                <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress=""></div>
                </div>
                </span>
            </div>

        </div><!-- END ACTIONS -->

            <!-- HTML heavily inspired by http://blueimp.github.io/jQuery-File-Upload/ -->
            <div class="table table-striped" id="previews">

                <div id="template" class="file-row">
                    <!-- This is used as the file preview template -->
                    <div>
                        <span class="preview"><img data-dz-thumbnail /></span>
                    </div>
                    <div>
                        <p class="name" data-dz-name></p>
                        <strong class="error text-danger" data-dz-errormessage></strong>
                    </div>
                    <div>
                        <p class="size" data-dz-size></p>
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-primary start" style="display: none;">
                            <i class="glyphicon glyphicon-upload"></i>
                            <span>Start</span>
                        </button>
                        <button data-dz-remove class="btn btn-warning cancel" style="display: none;">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span>Cancel</span>
                        </button>
                        <button data-dz-remove class="btn btn-danger delete">
                        <i class="glyphicon glyphicon-trash"></i>
                        <span>Delete</span>
                        </button>
                    </div>
                </div>
            </div><!-- END PREVIEWS -->

        </div><!-- col -->
      </div>
      <div class="modal-footer">
        <button id="modal_close_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="modal_add_btn" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal-backdrop fade modal-backdrop2" style="display: none;"></div>


<script>
    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template");
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    var myDropzone = new Dropzone("div#dropzone_container", { // Make the whole body a dropzone
    url: '<?php  echo plugins_url("../../_inc/fileupload.php",__FILE__ ); ?>', // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    acceptedFiles: "application/pdf",
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    });

    myDropzone.on("removedfile", function(file) {
        if ( myDropzone.files.length == 0) {
            document.querySelector('#create_ticket').disabled = true;
        }
        document.querySelector("#files_count").value = myDropzone.files.length;
    });

    myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    if ( myDropzone.files.length != 0) {
        document.querySelector('#create_ticket').disabled = false;
    }
    document.querySelector("#files_count").value = myDropzone.files.length;
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
    });

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%";

    });

    myDropzone.on("sending", function(file, xhr, formData) {
        var ticket_folder = document.querySelector("#ticket_number").value;
        formData.append('ticket_folder', ticket_folder);    
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1";
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
    });

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
        document.querySelector("#total-progress").style.opacity = "0";
    });

    myDropzone.on("complete", function(file) {
        if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) {
            //document.querySelector("#total-progress").style.opacity = "0";
            myDropzone.removeAllFiles(true);
            //document.querySelector("#upload-documents-row").classList.toggle("dropzone-hide"); 
        }
    });

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    };
    document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true);
    };
</script>

<?php

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>