<?php
// if (!isset($_GET['user'])) exit;
include ('includes/header.php');
include ('includes/classes/Message.php');

$message_obj=new Message($pdo,$user);

$user_to=(isset($_GET['user'])?$_GET['user']:$message_obj->getMostRecentUser());

if ($user_to=='new')
	$user_to=FALSE;
//messages.php?user=new -> username can't be less than 4 remember?

if ($user_to){
	$user_to_obj=new User($pdo,$user_to);

	if (isset($_POST['message_submit'])){
		if (isset($_POST['msg_body']) and $_POST['msg_body']!=$_SESSION['last_message']){
			$_SESSION['last_message']=$_POST['msg_body'];
			$message_obj->sendMessage($user_to,$_POST['msg_body']);
		}
	}
}

?>

<title><?php echo "Messages - ".$user->getFullName()?></title>

<link rel="stylesheet" type="text/css" href="assets/css/messages.css"/>
<style>
	.profile_user_data button{
    display:none;
}

@media (max-width: 1100px) {
    .userProfileCard{
        margin-left:0px;
    }
}

@media (max-width: 900px) {
    main{
        flex-direction: column;
    }
    .profile_user_data button{
        display:block;
    }
    .userProfileCard{
        margin: auto 10%;
        margin-top:120px; /*TODO*/
        width:auto;
        max-width:900px;
    }
    .msgfeed{
        min-width:400px;
        margin-top: 120px;
        margin: auto 10%;
        margin-top: 30px;
        width:auto;
    }
}

@media (max-width: 580px) {
    .msgfeed{
        margin-left:5%;
        margin-right:5%;
    }
}

@media (max-width: 500px) {
    .userProfileCard{
        width:100%;
        margin-left: 0px;
    }
    .msgfeed{
        margin-left:0px;
        width:100%
    }
}
</style>

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
				<button class='redButton' style="color:#908b8b">
					<i class="fa fa-share-square"></i> Sign Out
				</button>
				</div>
			</div>
		</div>
		<div class='column conversations'>
			<h4>Conversations</h4>
			<div class='loadedConvos'>
				<?php echo $message_obj->getConversations()?>
			</div>
			<a href="messages.php?user=new">
				<div class='newMessage blueButton'>
					<i class='fa fa-paper-plane'></i> New Message
				</div>
			</a>
		</div>
	</div>

	<div class='column msgfeed'>
		<?php echo $message_obj->showMessageFeed($user_to); ?>
	</div>
</main>

</body></html>