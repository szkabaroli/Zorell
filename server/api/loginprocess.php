<?php

include('config.php');

if($_POST['action'] === 'register') {
    $UserAgent->Register($_POST['rfname'], $_POST['rlname'], $_POST['rpass'], $_POST['remail']);
    echo 'success';
}

if($_POST['action'] === 'login') {
    if($UserAgent->Login($_POST['lemail'], $_POST['lpass'])) {
        echo 'success';
    }
}

if($_GET['action'] == 'getuser') {
    $user = $UserAgent->GetUser($_SESSION['user_id']);
    echo json_encode($user, JSON_UNESCAPED_UNICODE);
}
?>
