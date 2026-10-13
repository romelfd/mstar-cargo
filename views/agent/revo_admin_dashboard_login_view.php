<?php 
ob_start();
?>
<div class="card col-md-5 p-0 mx-auto">
    <h5 class="card-header text-white bg-primary">Please log in</h5>
        <div class="card-body p-4">
        <div class="row m-0">
            <div class="col-md-12">
                <h3>Log in</h3>
                <form id="login-form" action="/wp-login.php" method="post" >
                    <div class="form-group">
                        <label for="InputEmail">Username or Email Address</label>
                        <input type="text" name="log" class="form-control" id="InputEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="InputPassword">Password</label>
                        <input type="password" name="pwd" class="form-control" id="InputPassword" required>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="remeberCheck" name="rememberme">
                        <label class="form-check-label" for="remeberCheck">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
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