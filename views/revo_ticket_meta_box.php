<style>
    #ticket_details label {
        font-weight: bold;
        display: block;
       
    }

    #ticket_details input, #ticket_details select, #ticket_details textarea {
        width: 100%;
        height: 30px;
        border: 1px solid #7e8993;
        margin-bottom: 10px;
    }

    #ticket_details textarea {
        height: 50px;
        margin-bottom: 10px;
    }
</style>

<div>
    <label>Ticket Type:</label>
    <select name="ticket_type">
            <?php foreach ($ticket_type_options as $ticket_type_option_key => $ticket_type_option) { ?>            
                    <?php 
                    $selected = '';
                    if ($ticket_type == $ticket_type_option_key ) { 
                        $selected = ' selected="selected" ';
                    } ?>
                    <option value="<?php echo $ticket_type_option_key; ?>"  <?php echo $selected; ?>><?php echo $ticket_type_option; ?></option>
            <?php } ?>
    </select>
</div>

<div>
    <label>B/L Number:</label>
    <input name="bol_number" value="<?php echo $bol_number; ?>" />
</div>

<div>
    <label>Ticket Number:</label>
    <input name="ticket_number" value="<?php echo $ticket_number; ?>" />
</div>

<div>
    <label>Client Name:</label>
    <input name="client_name" value="<?php echo $client_name; ?>" disabled />
</div>

<div>
    <label>Ticket Department : </label>
    <select name="ticket_department">
            <?php foreach ($ticket_department_options as $ticket_department_option_key => $ticket_department_option) { ?>            
                    <?php 
                    $selected = '';
                    if ($ticket_department == $ticket_department_option_key) { 
                        $selected = ' selected="selected" ';
                    } ?>
                    <option value="<?php echo $ticket_department_option_key; ?>"  <?php echo $selected; ?>><?php echo $ticket_department_option; ?></option>
            <?php } ?>
    </select>
</div>

<div>
    <label>Status : </label>
    <select name="ticket_status">
            <?php foreach ($ticket_status_options as $ticket_option_key => $ticket_option) { ?>            
                    <?php 
                    $selected = '';
                    if ($ticket_status == $ticket_option_key) { 
                        $selected = ' selected="selected" ';
                    } ?>
                    <option value="<?php echo $ticket_option_key; ?>"  <?php echo $selected; ?>><?php echo $ticket_option; ?></option>
            <?php } ?>
    </select>
</div>
<div>
    <label>Message: </label>
    <textarea>
    </textarea>
</div>
<hr/>
<div>
    <label>Documents: </label>
    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th id="cb" class="manage-column column-cb" scope="col">#</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Filename</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Size</th>
                <th id="columnname" class="manage-column column-columnname" scope="col">Date uploaded</th> 
                <th id="columnname" class="manage-column column-columnname" scope="col">Action</th> 
            </tr>
        </thead>
      <tbody>
        
             <?php if (count($documents_f) > 0) { $n=0; ?>
                    <?php foreach ($documents_f as $document)  { $n++; ?>
                        <tr valign="top">
                             <td class="column-columnname"><?php echo $n ?></td>
                             <td class="column-columnname"><?php echo $document['filename'];?></td>
                             <td class="column-columnname"><?php echo $document['filename'];?></td>
                             <td class="column-columnname"><?php echo $document['filename'];?></td>
                             <td> 
                                <span><a target="blank" href="<?php echo $document['file_url'];?>">View</a></span>
                             </td>
                        </tr> 
                    <?php } ?>
             <?php } ?>
        
    </tbody>
</table>

   
</div>