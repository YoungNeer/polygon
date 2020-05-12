<?php
/**
 * AJAX for accepting a friend request
 */

include('../includes/constants.php');
include('../config/db_connect.php');;
include('../includes/classes/User.php');

$user_obj=new User($pdo,$_POST['user']);
$user_obj->acceptFriendRequest($_POST['friend']);

//You are my friend. So I am also your friend!
$friend_obj=new User($pdo,$_POST['friend']);
$friend_obj->addFriend($_POST['user']);
?>