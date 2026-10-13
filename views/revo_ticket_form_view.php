<?php 
echo realpath('./uploads') . PHP_EOL;
ob_start();
?>
<div class="card">
    <div class="client-head card-header bg-primary">
        <h5 class="text-white float-left">Welcome  <?php echo $client_username; ?></h5>
        <span class="float-right "><a class="text-white" href="<?php echo wp_logout_url( get_permalink() ); ?>"><i class="fas fa-power-off"></i>Logout</a></span>
    </div>
    
        <div class="card-body">

              <div class="row">
                   <div class="col-md-12">

                    
                        <ul class="client_nav nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Dashboard</a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="account_profile" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Profile</a>
                            </li>
                        </ul>

                        <div class="card mb-3 tab-content-wrap">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active " id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                        <div class="card mb-3 border-primary">
                                            <div class="card-body text-primary">
                                                <h5 class="card-title">Your Tickets</h5>
                                                <a href="#" class="add_ticket_btn float-right"><i class="fa fa-plus" aria-hidden="true"></i> Add new ticket</a>
                                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                            <div id="client_tickets">
                                               
                                            </div>
                                    </div>
                                </div>

                                     <div class="card border-secondary mb-3" id="ticket_detail" style="visibility: hidden;" >
           
                    </div>

                                            </div>   
                                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                    <div id="client_profile">
                                                        
                                                    </div>
                                            </div>
                                 </div><!-- END TAB PANE HOME -->
                                 
                            </div><!-- END TAB CONTENT -->                                
                        </div>
                    </div>
                    
                    <div id="add_new_ticket" class="card border-secondary mb-3" style="display: none;">
                            <div class="card-body text-secondary">
                                <h5 class="card-title">Add New Ticket</h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <div id="msg-alert-1" class="alert alert-success mt-2" role="alert" style="display: none;">
                                            Your ticket has been submitted successfully! <span class="font-weight-bold" id="display_ticket_number"></span>
                                </div>
                               
                                   <div class="row" id="upload-documents-row">
                                     <div class="col-sm-8 mt-3">
                                         <form id="create_ticket_form" class="needs-validation">
                                            <h6>Type: </h6>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input ticket_type" type="radio" name="ticket_type" value="import" checked>
                                                <label class="form-check-label">
                                                    Import
                                                </label>
                                             </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input ticket_type" type="radio" name="ticket_type" value="export">
                                                <label class="form-check-label">
                                                    Export
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input ticket_type" type="radio" name="ticket_type" value="refund">
                                                <label class="form-check-label">
                                                    Refund
                                                </label>
                                            </div>
                                            <div class="mt-2 mb-2 form-seach-bol " > 
                                                <h6 class="mt-3">B/L Number: </h6>
                                                <div class="form-inline">
                                                <input type="text" name="bol_number_search" class="form-control mb-2 mr-sm-2" id="bol_number" placeholder="Enter B/L Number" required>
                                                <button id="search_bol" type="button" class="search_bol_btn btn btn-primary mb-2">Search</button>
                                                   <div class="invalid-feedback">
                                                    Please enter B/L number.
                                                    </div>
                                                </div>
                                                <div id="bol_results">
                                               
                                                 </div>

                                            </div>

                                            

                                            <h6 class="mt-3">Documents: </h6>
                                            <div class="table-responsive">
                                                <table class="table file_upload_list">
                                                    <thead>
                                                       <th>Filename</th>
                                                       <th>Size</th>
                                                   </thead>
                                                   <tbody>
                                                       <tr>
                                                           <td colspan="2">No documents</td>
                                                       </tr>
                                                   </tbody>
                                                </table>
                                            </div>
                                            <br/><br/>
                                            <button type="button" class="btn btn-primary upload_douments_button" disabled>Add Documents</button>
                                                
                                         </form>
                                    </div>
                                    
                                     <div class="col-sm-12 mt-3">
                                         
                                                <!-- The fileinput-button span is used to style the file input field as button -->
                                                <input type="hidden" value="0" name="ticket_number"  id="ticket_number">
                                                <input type="hidden" value="0" name="files_count"  id="files_count">
                                                <button  id="revo_agent_cancel" type="button" class="btn btn-md btn-warning float-right">Cancel</button>
                                                <button id="create_ticket" class="btn btn-primary float-right mr-2" disabled>
                                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                                    <span>Submit</span>
                                                </button>
                                            
                                    </div>
                                </div><!-- row -->
                            </div><!-- card body -->    
                      </div><!-- card -->
           </div><!-- card-body -->
</div>            

<!-- Modal -->
<div class="modal fade upload_documents_modal " id="uploadModalCenter" tabindex="-1" role="dialog" aria-labelledby="uploadModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload Documents <span style="font-size: 12px; color: #856404;"> Please upload PDF format only</span></h5>
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
    url: '<?php  echo plugins_url("../_inc/fileupload.php",__FILE__ ); ?>', // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
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