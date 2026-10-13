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
                   <div class="alert alert-danger" role="alert">
                            You don't have access to this section.
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