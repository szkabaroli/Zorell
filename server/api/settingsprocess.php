<?php

include('config.php');

if($_POST['action'] == 'updateprofile'){

    if(strlen($_FILES['profile_image']['name']) != 0 ) {

        $img = $_FILES['profile_image']['name'];
        $tmp = $_FILES['profile_image']['tmp_name'];

        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

        $final_image = md5(microtime()) . '.' . $ext;

        if(in_array($ext, $image_extensions)) {
            
            $final_image = strtolower($final_image);
            $path = $image_upload_path . $final_image; 
            $relative_path = '/uploads/' . $final_image;
            if(move_uploaded_file($tmp,$path)) {
                $db->Query('UPDATE users SET user_avatar = ? WHERE user_id = ?', array( $relative_path ,$_SESSION['user_id']));
                echo 'success';
            }

        } else {
            
            echo 'error';

        }
    }

    if(strlen($_POST['user_fname']) != 0){
        $db->Query('UPDATE users SET user_fname = ? WHERE user_id = ?', array($_POST['user_fname'] ,$_SESSION['user_id']));
    }
    if(strlen($_POST['user_lname']) != 0){
        $db->Query('UPDATE users SET user_lname = ? WHERE user_id = ?', array($_POST['user_lname'] ,$_SESSION['user_id']));
    }
    if(strlen($_POST['user_subname']) != 0){
        $db->Query('UPDATE users SET user_subname = ? WHERE user_id = ?', array($_POST['user_subname'] ,$_SESSION['user_id']));
    }   
    if(strlen($_POST['user_email']) != 0){
        $db->Query('UPDATE users SET user_email = ? WHERE user_id = ?', array($_POST['user_email'] ,$_SESSION['user_id']));
    }   
}

if($_POST['action'] == 'updatepass'){
    
    if($UserAgent->ChangePass($_POST['user_passc'], $_POST['user_passn'], $_POST['user_passna'], $_SESSION['user_id'])){
        echo 'success';
    } else {
        
        echo 'error';
    }

}

?>