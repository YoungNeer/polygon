<?php
require 'includes/header.php';
require 'includes/classes/Trend.php';
if (isset($_POST['toast'])){
	if ($_POST['post_body']!=$_SESSION['last_toast']){
		$_SESSION['last_toast']=$_POST['post_body'];
		$toast=new Toast($pdo,$user);
		$toast->addPost($_POST['post_body'],'');
		$_POST['toast']=NULL;
		$_POST['post_body']=NULL;
	}
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
		</div>
		<?php
			$trend=new Trend($pdo);
			$trend->displayTrends();
		?>
	</div>
	<div class='column newsfeed'>
	<form method="POST" action="index.php">
		<textarea name="post_body" class="post_text" placeholder="Got Something to Say?"></textarea>
		<input type="submit" name="toast" class="blueButton" value="Toast"/>
	</form>
	<hr/>
<?php echo $user->showNewsfeed(); ?>
</main>
<script>
	$('button.redButton').on('click',function(){
		window.location='includes/functions/logout.php'
	})
</script>
</body></html>