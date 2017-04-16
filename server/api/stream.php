<?php

include('config.php');

$new_ids = array();
$last_ids = array();
$updates = array();
$deletes = array();

$page = $_POST['page'];
$ammount = $_POST['ammount'];
$friend_ids = $_SESSION['friends'];
$user_id = $_SESSION['user_id'];

/* 
    Ha nincsennek barátai a felhasználónak akkor csak a saját posztjait töltjük be,
    különben az összes barátjáét + a sajátjait.
*/

if(count($friend_ids) == 0) {

    $new = $PostsAgent->LoadPosts(array($_SESSION['user_id']), $page*$ammount);


} else {

    array_unshift($friend_ids, $user_id);
    $new = $PostsAgent->LoadPosts( $friend_ids, $page*$ammount);
}

if(!isset($_SESSION['last'])){

    echo '{"action":"init", "updates":' . json_encode($new, JSON_UNESCAPED_UNICODE) . ', "deletes": [] }';

} else {

    if(isset($_SESSION['lastpage']) && $_SESSION['lastpage'] !== $page){

        echo '{"action":"pageload", "updates":' . json_encode($new, JSON_UNESCAPED_UNICODE) . ', "deletes": [] }';

    }
    else if($new !== $_SESSION['last']){

        $last = $_SESSION['last'];

        $new_ids = array_column($new, 'post_id');  
        $last_ids = array_column($last, 'post_id');

        $updates_id = array_diff($new_ids, $last_ids); //updates
        $deletes_id = array_diff($last_ids, $new_ids); //deletes

        foreach($updates_id as $key => $value){
            $id = array_search($value, array_column($new, 'post_id'));
            array_push($updates, $new[$id]);
        };

        foreach($deletes_id as $key => $value){
            $id = array_search($value, array_column($last, 'post_id'));
            array_push($deletes, $last[$id]);
        };

        foreach($new as $key => $value){
            if(isset($last[$key])){
                if( $value !== $last[$key]){
                    $updates = $new;
                }
            }
        }

        echo '{ "action":"update", "updates":' . json_encode($updates, JSON_UNESCAPED_UNICODE) . ', "deletes": ' . json_encode($deletes, JSON_UNESCAPED_UNICODE) . '}';
        
    } else {
        echo '{"action":"sleep"}';
    }
}


$_SESSION['last'] = $new;
$_SESSION['lastpage'] = $page;