<?php
session_start();

//Declaring variables to prevent errors
$suggestedUsername1='';$suggestedUsername2='';
$fname = "";$lname = "";
$email = "";
$password = "";$password2 = "";
$date = "";
$error_array = array(); //Holds error messages

if (isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['username']) and
	isset($_POST['email']) and isset($_POST['password']) and isset($_POST['password2'])
){

	//First name
	$fname = strip_tags($_POST['fname']); //Remove html tags
	$fname = str_replace(' ', '', $fname); //remove spaces
	$fname = ucfirst(strtolower($fname)); //Uppercase first letter
	$_SESSION['fname'] = $fname; //Stores first name into session variable

	//Last name
	$lname = strip_tags($_POST['lname']); //Remove html tags
	$lname = str_replace(' ', '', $lname); //remove spaces
	$lname = ucfirst(strtolower($lname)); //Uppercase first letter
	$_SESSION['lname'] = $lname; //Stores last name into session variable

	//email
	$email = strip_tags($_POST['email']); //Remove html tags
	$email = str_replace(' ', '', $email); //remove spaces
	$email = strtolower($email); //email will be in all lower case
	$_SESSION['email'] = $email; //Stores email into session variable

	$username = strip_tags($_POST['username']); //Remove html tags
	$username = str_replace(' ', '', $username); //remove spaces
	$username = strtolower($username); //email will be in all lower case
	$_SESSION['username'] = $username; //Stores email into session variable

	//Password
	$password = strip_tags($_POST['password']); //Remove html tags
	$password2 = strip_tags($_POST['password2']); //Remove html tags

	if(strlen($fname) > 25 || strlen($fname) < 2)
		array_push($error_array, 1);

	if (strlen($lname) > 25 || strlen($lname) < 2)
		array_push($error_array,  2);

	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		//Check if email already exists 
		$query = $pdo->prepare("SELECT `email` FROM users WHERE email=:email");
		$query->bindParam(":email", $email);
		$query->execute();
		if($query->rowCount() > 0)
			array_push($error_array, 3);
	}
	else
		array_push($error_array, 4);
	
	if(strlen($username > 24 || strlen($username) < 4))
		array_push($error_array, 5);

	$user_query = $pdo->prepare("SELECT username FROM users WHERE username=:username");
	$user_query->bindParam(":username", $username);
	$user_query->execute();
	if ($user_query->rowCount() > 0){
		array_push($error_array, 6);
		$i='';
		while(TRUE){
			//Suggest vacant usernames for the user based on his real name!
			if ($suggestedUsername1==''){
				$suggest1=strtolower($fname.'_'.$lname.$i);
				$query = $pdo->prepare("SELECT username FROM users WHERE username=:username");
				$query->bindParam(":username", $suggest1);
				$query->execute();
				if ($query->rowCount() == 0)
					$suggestedUsername1=$suggest1;
			}
			if ($suggestedUsername2==''){
				$suggest2=strtolower($lname.'_'.$fname.$i);
				$query = $pdo->prepare("SELECT username FROM users WHERE username=:username");
				$query->bindParam(":username", $suggest2);
				$query->execute();
				if ($query->rowCount() == 0)
					$suggestedUsername2=$suggest2;
			}
			//Breakout if we found exactly two vacant usernames!
			if ($suggestedUsername1!='' and $suggestedUsername2!='') break;
			if ($i=='') $i=-1; ++$i;
		}
	}
	
	if (preg_match('/[^A-Za-z0-9]/', $username))
		array_push($error_array, 7);

	if ($password != $password2)
		array_push($error_array,  8);
	else
		if (preg_match('/[^A-Za-z0-9]/', $password))
			array_push($error_array, 9);

	if(strlen($password > 30 || strlen($password) < 6))
		array_push($error_array, 10);


	if (empty($error_array)){
		//Everything is as expected!
		$password=md5($password);
		$pid=rand(1,10);
		$profilePic="default/$pid.png";
		$today = date("Y-m-d"); //Current date
		$now = date("Y-m-d  H:i:s"); //Current date
		
		//Account is verfied by default! You might want to change this and use OTP method
		$query = $pdo->prepare("
			INSERT INTO `users` VALUES
			(NULL,:firstName,:lastName,:username,:email,:password,:last_online,:date_joined,:pic,'0','0','1','0',',')"
		);
		$query->bindParam(":firstName", $fname);
		$query->bindParam(":lastName", $lname);
		$query->bindParam(":username", $username);
		$query->bindParam(":email", $email);
		$query->bindParam(":password", $password);
		$query->bindParam(":last_online", $now);
		$query->bindParam(":date_joined", $today);
		$query->bindParam(":pic", $profilePic);
		$query->execute();
		
		array_push($error_array, -1); //-1 means everything went alright!
		// $_SESSION=[]; //Clear all session variables!
	}
}
?>