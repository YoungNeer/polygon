<?php

/**
 * AJAX for Searching a Friend
**/

include('../includes/constants.php');
include('../config/db_connect.php');;
include('../includes/classes/User.php');

$q=$_POST['query'];

if (strlen($q)<2) return;

$user=new User($pdo,$_POST['username']);

$names=explode(' ',$q);

$names[0]=$names[0].'%';
$names[1]=$names[1].'%';

if (strpos($q,'_')!==FALSE){
	$query=$pdo->prepare("SELECT * FROM `users` WHERE `username` LIKE :query AND `ac_closed`=0 LIMIT 8");
	$query->bindParam(":query","$q%");
}
elseif (count($names)==2){
	$query=$pdo->prepare("SELECT * FROM `users` WHERE `fname` LIKE :fname AND `lname` LIKE :lname AND `ac_closed`=0 LIMIT 8");
	$query->bindParam(":fname",$names[0]);
	$query->bindParam(":lname",$names[1]);
}
else{
	$query=$pdo->prepare("SELECT * FROM `users` WHERE `fname` LIKE :fname OR `lname` LIKE :fname AND `ac_closed`=0 LIMIT 8");
	$query->bindParam(":fname",$names[0]);
}

$query->execute();

if ($query->rowCount()==0) return;

while($row=$query->fetch(PDO::FETCH_ASSOC)){

	if ($row['username']!=$user->getUsername()){
		$mutualFriends="";
		// $mutualFriends=$user->getMutualFriends($row['username'])." friends in common";
	}else{
		$mutualFriends="";
	}

	if ($user->isFriend($row['username'])){
		echo "
			<a href='messages.php?user=".trim($row['username'])."'>
				<div class='liveSearchResult'>
						<div class='profilePic'>
							<img src='".PROFILE_PIC_LOCATION.$row['pic']."'>
						</div>

					<div class='username'>
						".$row['fname'].' '.$row['lname']."
					</div>
					<div class='bio'>
						Last Online: <span class='timestamp' data-time='".$row['last_online']."'></span>
					</div>
					
				</div>
			</a>
		";
	}
}

echo "<script type='text/javascript'>updateTimestamp()</script>";
?>