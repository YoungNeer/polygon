<?php

/**
 * Load Post from database - POSTS_LIMIT at a time
 */

include('../includes/constants.php');
include('../config/db_connect.php');;
include('../includes/classes/User.php');
include('../includes/classes/Comment.php');
include('../includes/classes/Notification.php');
include('../includes/classes/Like.php');
include('../includes/classes/Toast.php');

$toast=new Toast($pdo,new User($pdo,$_POST['username']));
$toast->loadFriendPosts($_POST['page'],$_POST['onlySelfPosts']);
?>