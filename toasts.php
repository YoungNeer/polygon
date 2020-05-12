<?php
require 'includes/header.php';
$id=0;
if(isset($_GET['id']) and isset($_GET['type'])) {
	$id = $_GET['id'];
	$type = $_GET['type'];
}else{
	header("Location:index.php");
	exit; //in case redirection failed
}
?>
<title><?php echo $user->getFullName()." - Polygon"?></title>

<!-- <link rel="stylesheet" type="text/css" href="assets/css/main.css"/> -->
<link rel="stylesheet" type="text/css" href="assets/css/index.css"/>

<main>
<div class='userProfileCard'>
<div class="user_details column">
	<a href="profile.php?user=<?php echo $user->getUsername()?>">
		<img class="user_profile_pic_main" src="<?php echo $user->getProfilePic()?>">
	</a>
	<span class="name_field">
		<a href="profile.php?user=<?php echo $user->getUsername()?>"><?php echo $user->getFullName()?></a>
	</span>
	<div class='profile_user_data'>
		<span class='data_username'>(@<?php echo $user->getUsername()?>)</span>
		<div class="other_details">
			<i class="fa fa-users"></i>  <?php echo $user->getNumFriends()?> &nbsp;
			<i class="fa fa-edit"></i>  <?php echo $user->getNumPosts()?> &nbsp;
			<i class="fa fa-heart"></i> <?php echo $user->getNumLikes()?>

		</div>
		<div id='friendBtnContainer'>
		<button class='redButton' style="color:#908b8b"><i class="fa fa-share-square"></i> Sign Out</button>
		</div>
	</div>
</div></div>
	<div class='column newsfeed'>
		<?php
			$toast_obj = new Toast($pdo, $user);
			if ($toast_obj->toastExists($id)){
				$toast_obj->getSingleToast($id);
				$notif_obj = new Notification($pdo, $user);
				$notif_obj->openNotification($type,$id,$user->getUsername());
			}else{
				echo "<div style='text-align: center;'>Sorry but it seems the toast doesn't exist</div>";
			}
		?>
	</div>
	
	<hr/>
</main>
<script>
	$('button.redButton').on('click',function(){
		window.location='includes/functions/logout.php'
	})
</script>
</body></html>