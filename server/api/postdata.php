<?php

include('config.php');

if($_POST['action'] == 'sendpost'){
    if(strlen($_POST['post'])<=150){

        $post_id = $PostsAgent->SendPost($_SESSION['user_id'], $_POST['post']);
        
    }else {
        echo json_encode('error');
    }
}

if($_POST['action'] == 'getnewposts') {

    $page_num = $_POST['page'] - 1;
    $ammount = $_POST['ammount'];

    $posts = $PostsAgent->GetPosts(($page_num-1)*$ammount, $ammount);
    echo '{ "action": "success", "posts":' . json_encode($posts) . ' }';
}

?>