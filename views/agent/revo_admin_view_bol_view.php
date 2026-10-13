<?php 

ob_start();

?>
 <div class="p-2">
 <?php     
$bol_number = 0;
if (count($bol) > 0) {
$bol_id = $bol[0]->ID;
$cfields = get_post_meta($bol_id);
$bol_number = $cfields['bol_number'][0];

?>
       
            <h5 class="card-title">B/L# <?php echo $bol_number; ?></h5>
            
            <ul class="grid-container">
            <?php foreach ($cfields as $bol_info_key => $bol_info_v) { ?>
                <?php if ($bol_info_key != 'bol_number' && $bol_info_key != '_edit_lock' && $bol_info_key != '_edit_last'  ) { ?>
                <li class="grid-item text-primary">
                    <label><?php echo str_replace("_", " ", ucfirst($bol_info_key)); ?>:</label>
                    <?php echo $bol_info_v[0]; ?>
                </li>
            <?php }} ?>    
            </ul>
  
<?php  } else { ?>
        No results found.
<?php } ?>
       <input type="hidden" name="bol_number" id="bol_number_result" value="<?php echo $bol_number; ?>">
       </div>
<?php


$content = ob_get_contents();
ob_end_clean();
$output = $content;

?>