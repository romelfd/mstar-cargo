<?php 
ob_start();
?>

        <div class="row m-0">
        <div class="card" style="margin: 0 auto; max-width: 500px">
            <div class="card-body p-0">
                <div class="col-md-12 p-4">
                    <h3>Forgot Your Password?</h3>
                    <?php if ( count( $attributes['errors'] ) > 0 ) { ?>
                        <div class="login-msg alert alert-danger" role="alert">
                        <?php foreach ( $attributes['errors'] as $error ) { ?>
                            <p>
                                <?php echo $error; ?>
                            </p>
                        <?php } ?>
                        </div>
                    <?php } ?>

                    
                    <p class="card-text mb-3">Enter your email address and we'll send you a link you can use to pick a new password.</p>
                    <form id="revo-lost-password-form" action="<?php echo wp_lostpassword_url(); ?>" method="post" >
                        <div class="form-group">
                            <label for="InputEmail">Email Address</label>
                            <input type="email" name="user_login" class="form-control" id="InputEmail" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>            

<?php

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>