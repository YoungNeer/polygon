<?php
/**
 * AJAX for getting number of friend requests
**/

include('../includes/constants.php');
include('../config/db_connect.php');;
include('../includes/classes/User.php');

$user_obj=new User($pdo,$_POST['username']);
echo $user_obj->getFRMsg();
?>