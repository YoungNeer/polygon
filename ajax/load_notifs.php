<?php
/**
 * AJAX for submiting a post (used only in profile.php)
**/

include('../includes/constants.php');
include('../config/db_connect.php');;
include('../includes/classes/User.php');
include('../includes/classes/Notification.php');

$notif_obj=new Notification($pdo,new User($pdo,$_POST['username']));
echo $notif_obj->getConvoDropdown($_POST['page']);
?>