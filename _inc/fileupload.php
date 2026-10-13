<?php

if ($_FILES['ticket_file_cro']['size'] != 0 && $_FILES['ticket_file_cro']['error'] == 0)
{

  $path = '../uploads/webcro/';

  if ( !file_exists($path)) {
    mkdir($path);
  }

  $newfilename = $_REQUEST['ticket_number_f'] . '_CRO_'. date('Ymdhis');
  $path_parts = pathinfo($_FILES['ticket_file_cro']["name"]);
  $extension = $path_parts['extension'];

  $path = $path .  $newfilename . "." . $extension ;

  if(move_uploaded_file($_FILES['ticket_file_cro']['tmp_name'], $path)) {
    echo $newfilename. "." . $extension;
  } 

}



if(!empty($_FILES['file']))
{
  $ticket_folder = $_REQUEST['ticket_folder'];
  $path = '../uploads/' . $ticket_folder .'/';

  if ( !file_exists($path)) {
    mkdir($path);
  }

  $path = $path . basename( $_FILES['file']['name']);

  if(move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
    echo "The file ".  basename( $_FILES['file']['name']). 
    " has been uploaded";
  } else{
      echo "There was an error uploading the file, please try again!";
  }
}

exit;

?>