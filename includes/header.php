<?php
session_start();
require 'includes/constants.php';
require 'config/db_connect.php';
require 'includes/util.php';
require 'includes/classes/Notification.php';
require 'includes/classes/User.php';
require 'includes/classes/Comment.php';
require 'includes/classes/Like.php';
require 'includes/classes/Toast.php';

if (!isset($_SESSION['loggedIn']))
	header('Location: login.php');
$user=new User($pdo,$_SESSION['user']);

function getBadge($type,$num){
	$style='margin-left: ';
	if ($num>20) $num='20+';
	elseif ($num<=0) return;
	if (strlen($num)>2){
		if ($type=='friends') $style.="-31px";
		elseif ($type=='messages') $style.="-25px";
		else $style.="-22px";
	}elseif (strlen($num)>1){
		if ($type=='friends') $style.="-23px";
		elseif ($type=='messages') $style.="-19px";
		else $style.="-16px";
	}else{
		if ($type=='friends') $style.="-25px";
		elseif ($type=='messages') $style.="-17px";
		else $style.="-17px";
	}
	return "<span class='notificationBadge' style='$style;'
		id='unread_$type'>$num</span>";
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<!--META-TAGS-->
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="icon" type="image/png" href="assets/images/icons/polygon.png"/>

		<!--STYLESHEETS-->
		<!-- <link rel="stylesheet" type="text/css" href="vendor/bootstrap/bootstrap.css"/> -->
		<link rel="stylesheet" type="text/css" href="vendor/bootstrap/bootstrap.css"/>
		<!-- <link rel="stylesheet" href="assets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css"> -->
		<link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/all.min.css">
		<link rel="stylesheet" type="text/css" href="assets/css/nav.css"/>
		<link rel="stylesheet" type="text/css" href="assets/css/main.css"/>
		<link rel="stylesheet" type="text/css" href="assets/css/messages.css"/>

		<!--JAVASCRIPT-->
		<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
		<script src="vendor/bootstrap/popper.js"></script>
		<script src="vendor/bootstrap/bootstrap.js"></script>
		<script src="vendor/bootbox/bootbox.min.js"></script>
		<script src="vendor/inview/jquery.inview.min.js"></script>

		<script type="text/javascript" src="assets/js/main.js"></script>
	</head>
	<body>
		<div class='top-bar'>
			<div class='logo'>
				<a href=".">
					<div class='logoContainer'></div>
				</a>
			</div>
			<nav>
				<a class="username" href="profile.php?user=<?php echo $user->getUsername() ?>">
					<?php echo $user->getFirstName() ?>
				</a>
				<a href="index.php">
					<i class="fa fa-home fa-2x"></i>
				</a>
				<a id='envelopeBtn' href="javascript:void(0);">
					<i class="fa fa-envelope fa-2x"></i>
				</a>
				<?php echo getBadge("messages",$user->getNumUnreadMessages()); ?>
				<a id='bellBtn' href="javascript:void(0);">
					<i class="fa fa-bell fa-2x"></i>
				</a>
				<?php echo getBadge("notifications",$user->getNumUnreadNotifications()); ?>
				<a href="requests.php">
					<i class="fa fa-users fa-2x"></i>
				</a>
				<?php
				if (substr($_SERVER['PHP_SELF'],-12)!="requests.php")
					echo getBadge("friends",$user->getNumUnseenFriendRequests());
				?>
				<span id='show_more_options'>
					<a href='javascript:void(0);'><!--href="includes/functions/logout.php"-->
						&nbsp;<i class="fa fa-angle-down fa-2x"></i>&nbsp;
					</a>
				</span>
			</nav>
			<div id='more_options_dropdown'>
				<!-- <ul> -->
					<li id='showSearchBar'>
						&nbsp;<i class="fa fa-search"></i>&nbsp;
						<a href="#">Search</a>
					</li>
					<li>
						&nbsp;<i class="fa fa-cog"></i>&nbsp;
						<a href="settings.php">Edit Profile</a>
					</li>
					<li>
						&nbsp;<i class="fa fa-share-square"></i>&nbsp;
						<a href="includes/functions/logout.php">  Log Out</a>
					</li>
				<!-- </ul> -->
			</div>

			<div class='searchBarContainer'>
				<div class='searchBar'>
					<form action="search.php" name='search_form'>

						<!-- <label for='searchText'>Search: </label>
						<input name='searchText' placeholder='Username' type='text'/><button>&nbsp;<i class="fa fa-search"></i>&nbsp;</button> -->
						<button>
							<i class="fa fa-search"></i>
						</button>
						<input type="text" name="searchText" placeholder="Search"/>
					</form>
				</div>
				<span id='hideSearchBar'>
					&nbsp;<i class="fa fa-times-circle"></i>&nbsp;
				</span>
			</div>
			<div id='liveSearchResults'></div>
			<div id='dropdown_data_window'>
				<div id='notifs_area'>
					<input type='hidden' id='nextNotif' value='1'/>
					<input type='hidden' id='noMoreNotifs' value='false'/>
				</div>
				<div class='notifEndMessage'></div>
				<div id='notifLoading'>
					<img src='assets/images/svg/oval_loader.svg'/>
				</div>
			</div>
			<input type='hidden' id='dropdown_data_type' value=''/>
		</div>
		<script type='text/javascript'>

			function toggleVisibility(query){
				if ($(query).css('visibility')=='hidden')
					$(query).css({"visibility":'visible'})
				else
					$(query).css({"visibility":'hidden'})
			}

			let liveSearchField=$('input[name="searchText"]')
			liveSearchField.on('keyup',()=>{
				liveSearch('<?php echo $user->getUsername()?>',liveSearchField.val(),'#liveSearchResults')
			})

			$('#show_more_options').on('click',function(){
				toggleVisibility('#more_options_dropdown')
			})

			$('#showSearchBar, #hideSearchBar').on('click',function(){
				toggleVisibility('.searchBarContainer')
				liveSearchField.val('')
				$('#more_options_dropdown').css({"visibility":'hidden'})

				let m=$('main').css('margin-top');
				m=Number(m.substr(0,m.length-2)) //get the number (not px)
				$('main').css(
					{"margin-top":(($('.searchBarContainer').css('visibility')=='hidden')?m-35:m+35)}
				)
				//clear the content
				$('#liveSearchResults').html('')
				toggleVisibility('#liveSearchResults')
			})
			

			$('#envelopeBtn').on('click',function(){
				<?php echo "getDropDownData('messages','".$user->getUsername()."')"?>
			})
			$('#bellBtn').on('click',function(){
				<?php echo "getDropDownData('notifications','".$user->getUsername()."')"?>
			})
		</script>

		<!-- <div class='wrapper'> -->
<!--Rest of the code is in the PHP file that requires this file-->