<?php
/**
 * AJAX for Liking/Unliking a post
 */

include('../includes/constants.php');
include('../config/db_connect.php');;
include('../includes/classes/User.php');
include('../includes/classes/Notification.php');
include('../includes/classes/Comment.php');
include('../includes/classes/Toast.php');
include('../includes/classes/Like.php');

$like_obj=new Like($pdo,$_POST['post_id'],new User($pdo,$_POST['username']));
$like_obj->bumpToast();
echo $like_obj->getButtonState();
?>