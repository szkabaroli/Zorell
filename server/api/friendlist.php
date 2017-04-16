<?php

include('config.php');

if($_POST['action'] == 'search'){
    $matched = $UserAgent->Search($_POST['text'], $_SESSION['user_id']);

    $matched = array_map(function($arr){
        if(in_array($arr['user_id'], $_SESSION['friends'])){
            return $arr + ['user_isfriend' => 1];
        } else {
            return $arr + ['user_isfriend' => 0];
        }
        
    }, $matched);

    echo json_encode($matched, JSON_UNESCAPED_UNICODE);
}

if($_POST['action'] == 'addfriend'){
    $_SESSION['friends'] = $UserAgent->AddFriend($_POST['friendid'], $_SESSION['user_id']);
    echo 'success';
}

if($_POST['action'] == 'removefriend'){
    $_SESSION['friends'] = $UserAgent->RemoveFriend($_POST['friendid'], $_SESSION['user_id']);
    echo 'success';
}

if($_POST['action'] == 'getfriends'){
    $result = $UserAgent->GetFriends($_SESSION['friends'], $_SESSION['user_id']);

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>