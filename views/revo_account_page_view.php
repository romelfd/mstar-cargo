`<?php 

ob_start();
?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3 border-primary">
        <div class="card-body text-primary">
        <h5 class="card-title">Profile</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <div class="row mt-3">
                <div class="col-sm-6 col-md-4 ">
                    <img src="<?php echo $user_avatar_url; ?>" alt="" class="img-rounded img-responsive" />
                </div>
                <div class="col-sm-6 col-md-8">
                    <h4><?php echo $user_username; ?></h4>
                    <i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $user_email; ?>
                    <br/>
                    <?php if (isset($user_department)) { ?>
                       <i class="fa fa-users" aria-hidden="true"></i></i> <?php echo ucfirst($user_department); ?>
                    <?php } ?>
                        
                    
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