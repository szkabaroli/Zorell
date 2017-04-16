<?php
session_start();

include($_SERVER['DOCUMENT_ROOT'] . '/server/classes/db.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/classes/useragent.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/classes/postsagent.php');

//'mysql.hostinger.hu', 'u303229855_admin', 'liroliro99', 'u303229855_zorel'

$db = new DB('mysql.hostinger.hu', 'u303229855_admin', 'liroliro99', 'u303229855_zorel');
$db->Connect();

$image_extensions = array('jpeg', 'jpg', 'png');
$image_upload_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

$UserAgent = new UserAgent($db);
$PostsAgent = new PostsAgent($db);

?>