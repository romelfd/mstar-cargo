<?php 
ob_start();
?>

      
        <div class="row justify-content-center" id="client_login_form_wrap">
            <div class="col-md-6">
                <div class="card">
                    <h5 class="card-header text-white bg-primary">Log in</h5>
                    <article class="card-body">
                    <?php if ( $attributes['password_updated'] ) { ?>
                        <div class="login-msg alert alert-danger" role="alert" >
                                <?php _e( 'Your password has been changed. You can sign in now.', 'personalize-login' ); ?>
                        </div>
                    <?php } else if ( $attributes['lost_password_sent'] ) { ?>
                            <div class="login-msg alert alert-danger" role="alert" >
                                <?php _e( 'Check your email for a link to reset your password.', 'personalize-login' ); ?>
                        </div>
                    <?php } else { ?>
                    <div class="login-msg alert alert-danger" role="alert" style="display:none;">
                    </div>
                    <?php }  ?> 
               

                    <form id="revo-login-form" action="/wp-login.php" method="post" class="p-4" >
                        <div class="form-group">
                            <label for="InputEmail">Email Address</label>
                            <input type="email" name="log" class="form-control" id="InputEmail" required>
                        </div>
                        <div class="form-group">
                            <label for="InputPassword">Password</label>
                            <input type="password" name="pwd" class="form-control" id="InputPassword" required>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="remeberCheck" name="rememberme">
                            <label class="form-check-label" for="remeberCheck">Remember me</label>
                        </div>
                        <div class="forgot mb-3">
                            Forgot password? <a href="/wp-login.php?action=lostpassword">Click here</a>.
                            <br/>
                            New user? <a href="#" id="client_new_account">Create new account</a>.
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    </form>
                </article>
              </div>
            </div>
       </div>    
       
    <div id="client_registration_form_wrap" class="row justify-content-center" style="display:none;">
        <div class="col-md-8 text-white">
            <div class="card">
                <header class="card-header">
                    <a href="#" id="client_login" class="float-right btn btn-outline-primary mt-1">Log in</a>
                    <h4 class="card-title">Sign up</h4>
                </header>
                 <article class="card-body bg-info ">        
                    <div class="register-msg alert alert-success" role="alert" style="display:none;">
                        <p>Registration successful.</p>

                        <p>An email has been sent to the site Administrator. The Administrator will review the information that has been submitted and either approve  or deny your request. You will receive an email with instructions on what you will do next. Thanks for your patience.</p>
                    </div>
                    <form id="revo-register-form">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label>First name</label>
                                <input type="text" name="first_name" class="form-control" id="input_first_name">
                                
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Last name</label>
                                <input type="text" name="last_name"  class="form-control" id="input_last_name" >
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Email address</label>
                                <input name="email" type="email" class="form-control" id="input_email">
                            </div>
                        </div>
                        
                        <div class="form-row">
                           
                            <div class="col-md-6 mb-3">
                                <label>Company Name</label>
                                <input type="text" name="company_name"  class="form-control" id="input_company_name" >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Contact No.</label>
                                <input type="number" name="contact_number"  class="form-control" id="input_contact_number" >
                            </div>
                            
                        </div>

                        <div class="form-group">
                                <label>Address</label>
                                <textarea name="address"  class="form-control" id="input_address" >
                                </textarea>
                       </div>

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label>Password</label>
                                <input name="password" type="password" class="form-control" id="input_password">
                            </div>
                        
                            <div class="col-md-6 mb-3">
                                <label>Confirm Password</label>
                                <input name="password_confirmation" type="password" class="form-control" id="input_password_confirmation">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary">Submit</button>
                    </form>
              </article>
            </div>
        </div>
    </div>            

<?php

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>