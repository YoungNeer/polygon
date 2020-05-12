<?php
include ('includes/header.php');
if (!isset($_GET['user'])) header("Location: profile.php?user=".$user->getUsername());
include ('includes/classes/Message.php');
include ('includes/classes/Follow.php');

$profile_user=new User($pdo,$_GET['user']);
$follow_obj=new Follow($pdo,$profile_user,$user);
?>

<title><?php echo "Viewing Profile - ".$profile_user->getFullName()?></title>
<link rel="stylesheet" type="text/css" href="assets/css/messages.css"/>
<link rel="stylesheet" type="text/css" href="assets/css/profile.css"/>


<?php
	//show messages tab
	if (isset($_GET['msg']) and $_GET['msg']==1){
		echo "<script type='text/javascript'>
			$(()=>{
				$('#profileTabs a[href=\"#messages\"]').tab('show');
			})
		</script>";
	}
?>

<main>
<div class='userProfileCard'>
<div class="user_details column">
	<a href="#">
		<img class="user_profile_pic_main" src="<?php echo $profile_user->getProfilePic()?>">
	</a>
	<span class="name_field">
		<a href=""><?php echo $profile_user->getFullName()?></a>
	</span>
	<div class='profile_user_data'>
		<span class='data_username'>(@<?php echo $profile_user->getUsername()?>)</span>
		<div class="other_details">
			<i class="fa fa-users"></i>  <?php echo $profile_user->getNumFriends()?> &nbsp;
			<i class="fa fa-edit"></i>  <?php echo $profile_user->getNumPosts()?> &nbsp;
			<i class="fa fa-heart"></i> <?php echo $profile_user->getNumLikes()?>

		</div>
		<div id='friendBtnContainer'>
			<?php
				echo $follow_obj->getButtonState();
			?>
		</div>
	</div>
</div></div>

<?php echo $follow_obj->getNewsfeed(); ?>

<script type="text/javascript">
	$('#msg_btn').on('click',function(){
		$.post(
			"ajax/submit_message.php",
			$('form.message_form').serialize()
		).done(function(result){
			location.reload()
		})
	})
	$('#msgTabBtn').on('click',function(){
		$.post(
			"ajax/submit_message.php",
			$('form.msgTabForm').serialize()
		).done(function(result){
			window.location=window.location.href+"&msg=1"; //show messages tab
		})
	})
	$('#toast_btn').on('click',function(){
		$.post(
			"ajax/submit_post.php",
			$('form.toast_form').serialize()
		).done(function(result){
			location.reload()
		})
	})
	$('#home form button').on('click',function(){
		console.log('working')
		$.post(
			"ajax/submit_post.php",
			$('#home form').serialize()
		).done(()=>{
			location.reload()
		})
	})
</script>

</main>

</body></html>