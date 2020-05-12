<?php
/**
 * AJAX for deleting a post
**/

include('../includes/constants.php');
include('../config/db_connect.php');;
include('../includes/classes/User.php');
include('../includes/classes/Notification.php');
include('../includes/classes/Comment.php');
include('../includes/classes/Toast.php');

echo $_POST['username'].$_POST['post_id'];

$toast=new Toast($pdo,new User($pdo,$_POST['username']));
$toast->deleteToast($_POST['post_id']);
?>