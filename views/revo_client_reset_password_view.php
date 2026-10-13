<?php 
ob_start();
?>

        <div class="row m-0">
        <div class="card" style="margin: 0 auto; max-width: 500px;">
            <div class="card-body p-0">
                <div class="col-md-12 p-4">
                    <h3>Pick a New Password</h3>
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
                    <form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
                        <input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
                        <input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />
                    
                        <div class="form-group">
                            <label for="pass1">New password</label>
                            <input type="password" name="pass1" class="form-control" id="pass1" autocomplete="off"  required>
                        </div>
                        <div class="form-group">
                            <label for="pass1">Repeat new password</label>
                            <input type="password" name="pass2" class="form-control" id="pass2" autocomplete="off" required>
                        </div>

                        
                        <p class="description mb-3"><?php echo wp_get_password_hint(); ?></p>
                        
                        <button type="submit" class="btn btn-primary" id="resetpass-button">Reset Password</button>
                       
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