<?php
/**
 * AJAX for Adding/removing friend (following/unfollowing)
 */

include('../includes/constants.php');
include('../config/db_connect.php');;
include('../includes/classes/User.php');
include('../includes/classes/Follow.php');

$friend=new User($pdo,$_POST['friend']); //whom to follow/unfollow
$user=new User($pdo,$_POST['user']); //who is following
$follow_obj=new Follow($pdo,$friend,$user);
$follow_obj->bumpUser();
echo $follow_obj->getButtonState();
?>