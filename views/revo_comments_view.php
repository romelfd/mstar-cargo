<?php 
ob_start();
?>
<?php 
if (count($comments) > 0 ) {
    foreach($comments as $comment) {
      ?>
        <div class="card bg-light bg-info mb-2" >
          <div class="card-body text-info">
              <h6 class="card-title"><span class="revo-icon-user"></span><?php  echo($comment->comment_author); ?></h6>
              <p class="card-text"><?php echo($comment->comment_content); ?></p>
              <p><small class="text-muted">
               <?php 
            
                $lastmodified = date ('U', strtotime($comment->comment_date));
                echo $this->revo_convert_to_time_ago($lastmodified);
               ?>
               </small></p>
          </div>
        </div>
    <?php 
    }
} else { ?>
 <div class="card border-danger mb-3">
  <div class="card-body text-danger">
    <p class="card-text">0 Comments</p>
  </div>
</div>
<?php } ?> 

<?php

$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>