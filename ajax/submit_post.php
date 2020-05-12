<?php
/**
 * AJAX for submiting a post (used only in profile.php)
**/

include('../includes/constants.php');
include('../config/db_connect.php');;
include('../includes/classes/User.php');
include('../includes/classes/Comment.php');
include('../includes/classes/Notification.php');
include('../includes/classes/Toast.php');
include('../includes/classes/Like.php');

$toast=new Toast($pdo,new User($pdo,$_POST['user_from']));
$toast->addPost($_POST['post_body'],$_POST['user_to']);
?>