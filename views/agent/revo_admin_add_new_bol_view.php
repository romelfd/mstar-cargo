<?php 

ob_start();
$bol_id = 0;
?>

<div class="card mb-3 border-primary">
    <div class="card-body text-primary">
                <?php if (!isset($bol)) { ?>
                <h3 class="card-title">XML Upload</h3>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur finibus vestibulum porttitor.</p>
                <div id="bol-msg-alert-1" class="alert alert-danger mt-2" role="alert" style="display: none;">
                    
                </div>
               
                <div class="row mt-2">
                    <div class="col-md-6">
                        <form method="POST" enctype="multipart/form-data" id="fileUploadForm">
                            <div class="input-group">
                                    <input type="file" name="file"/>
                                    <input type="submit" value="Submit" id="btnSubmit" class="btn btn-success" />
                            </div>
                        </form>
                    </div>
                </div>
                <?php } else { 
                        $bol_id = $bol[0]->ID;
                        $cfields = get_post_meta($bol_id);
                    ?>
                    <h3 class="card-title">Edit B/L#<?php echo $cfields['bol_number'][0]; ?></h4>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur finibus vestibulum porttitor.</p>
                
                <?php } ?>
                <div class="row mt-2">
                    
                    <form id="cargo_info_form" class="mt-2 col-md-12">
                        <input type="hidden" name="bol_id" value="<?php echo $bol_id; ?>" />
                    <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">B/L number</label>
                                <input name="cargo_info[bol_number]" id="bol_number" type="text" class="form-control form-control-sm" value="<?php echo $cfields['bol_number'][0]; ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Registry number</label>
                                <input name="cargo_info[registry_number]" id="registry_number" type="text" class="form-control form-control-sm" value="<?php echo $cfields['registry_number'][0]; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Code (Customs office segment)</label>
                                <input name="cargo_info[cos_code]" id="cos_code" type="text" class="form-control form-control-sm" value="<?php echo $cfields['cos_code'][0]; ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Total number of containers</label>
                                <input name="cargo_info[total_number_of_containers]" id="total_number_of_containers" type="text" class="form-control form-control-sm" value="<?php echo $cfields['total_number_of_containers'][0]; ?>">
                            </div> 
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Total gross mass manifested</label>
                                <input name="cargo_info[total_gross_mass_manifested]" id="total_gross_mass_manifested" type="text" class="form-control form-control-sm" value="<?php echo $cfields['total_gross_mass_manifested'][0]; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Volume in Cubic meters</label>
                                <input name="cargo_info[volume_in_cubic_meters]" id="volume_in_cubic_meters" type="text" class="form-control form-control-sm" value="<?php echo $cfields['volume_in_cubic_meters'][0]; ?>">
                            </div>
                          
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Place of unloading segment</label>
                                <input name="cargo_info[place_of_unloading_segment]"  id="place_of_unloading_segment" type="text" class="form-control form-control-sm" value="<?php echo $cfields['place_of_unloading_segment'][0]; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Tagging time</label>
                                <input name="cargo_info[tagging_time]" id="tagging_time" type="text" class="form-control form-control-sm" value="<?php echo $cfields['tagging_time'][0]; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Number of packages</label>
                                <input name="cargo_info[number_of_packages]" id="number_of_packages" type="text" class="form-control form-control-sm" value="<?php echo $cfields['number_of_packages'][0]; ?>">
                            </div>
                        </div>   
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Package type code</label>
                                <input name="cargo_info[package_type_code]" id="package_type_code" type="text" class="form-control form-control-sm" value="<?php echo $cfields['package_type_code'][0]; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Container Number</label>
                                <input name="cargo_info[container_number]" id="container_number" type="text" class="form-control form-control-sm" value="<?php echo $cfields['container_number'][0]; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label-sm">Container Type</label>
                                <input name="cargo_info[container_type]" id="container_type" type="text" class="form-control form-control-sm" value="<?php echo $cfields['container_type'][0]; ?>">
                            </div>
                        </div>
                        <button id="upload_xml_cancel" type="button" class="btn btn-secondary btn-lg float-right mt-2">Cancel</button>
                        <button id="upload_xml_save"  type="submit" class="btn btn-primary btn-lg float-right mt-2 mr-2">Save</button>
                       
                    </form>
                </div>
        
    </div>
</div>

    
        
<?php

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>