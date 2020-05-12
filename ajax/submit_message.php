<?php
/**
 * AJAX for submiting a post (used only in profile.php)
**/

include('../includes/constants.php');
include('../config/db_connect.php');;
include('../includes/classes/User.php');
include('../includes/classes/Message.php');

$msg_obj=new Message($pdo,new User($pdo,$_POST['user_from']));
$msg_obj->sendMessage($_POST['user_to'],$_POST['msg_body']);
?>