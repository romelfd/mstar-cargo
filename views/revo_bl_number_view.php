<?php 
ob_start();
?>

<?php 
 if ( is_user_logged_in() ) {
    $current_client = $this->current_user->data;
    if ($this->current_user->roles[0] == 'custom_client') { ?>
    <div class="card">
        <h5 class="card-header text-white bg-primary">Please enter B/L number below to see the current process status</h5>
        <div class="card-body">
            <form class="form-inline">
                <input type="text" class="form-control mb-2 mr-sm-2" id="inlineFormInputName2" placeholder="Enter B/L Number">
                <button type="submit" class="btn btn-primary mb-2">Submit</button>
            </form>
                <!--<table class="table table-sm table-dark mt-3">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        </tr>
                        <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                        </tr>
                        <tr>
                        <th scope="row">3</th>
                        <td colspan="2">Larry the Bird</td>
                        <td>@twitter</td>
                        </tr>
                    </tbody>
                </table>-->
            </div>
        </div>            
    <?php } } ?>
<?php

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>