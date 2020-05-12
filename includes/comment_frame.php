<?php
require 'constants.php';
require '../config/db_connect.php';
require 'classes/User.php';
require 'classes/Notification.php';
require 'classes/Comment.php';
require 'classes/Toast.php';

if (!isset($_SESSION['loggedIn'])) header('Location: ../login.php');

if (!isset($_GET['id'])) exit;

$post_id=$_GET['id'];

$_SESSION["comment$post_id"]=5;

$comment_obj=new Comment($pdo,$post_id,new User($pdo,$_SESSION['user']));
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<!--META-TAGS-->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" href="assets/images/icons/polygon.png"/>

		<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/bootstrap.css"/>
		<link rel="stylesheet" type="text/css" href="../assets/css/nav.css"/>
		<link rel="stylesheet" type="text/css" href="../assets/css/main.css"/>

		<!--JAVASCRIPT-->
		<script type="text/javascript" src="../vendor/jquery/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="../assets/js/main.js"></script>
		<script type="text/javascript">
			function toggle(){
			let element=document.getElementById('comment_section')
			element.style.display=(element.style.display=='block')?'none':'block';
		}
		</script>
		
	</head>
	<body style="background:white !important">
	<?php		
		if (isset($_POST["postButton$post_id"]))
			$comment_obj->postComment($_POST['comment_body']);
	?>
	<form class="comment_form" method="POST" action="comment_frame.php?id=<?php echo $post_id?>">
		<textarea class="post_text comment_text" name="comment_body" placeholder="Your comment here"></textarea>
		<input type='submit' class='blueButton' name='postButton<?php echo $post_id?>' value="Toast"/>
	</form>
	<div class='comments_section'><?php $comment_obj->displayComments();?></div>
	<script type='text/javascript'>
		$(()=>{
			updateTimestamp()
			refreshTimestamp(60000);
		})
	</script>
	</body>
</html>