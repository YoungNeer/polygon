<?php
session_start();

$fname = "";$lname = "";
$password = "";$password2 = "";
$error_array = array(); //Holds error messages

if (isset($_POST['fname']) and isset($_POST['lname'])){

	$id=$_POST['postId'];

	//First name
	$fname = strip_tags($_POST['fname']); //Remove html tags
	$fname = str_replace(' ', '', $fname); //remove spaces
	$fname = ucfirst(strtolower($fname)); //Uppercase first letter

	//Last name
	$lname = strip_tags($_POST['lname']); //Remove html tags
	$lname = str_replace(' ', '', $lname); //remove spaces
	$lname = ucfirst(strtolower($lname)); //Uppercase first letter

	if(strlen($fname) > 25 || strlen($fname) < 2)
		array_push($error_array, 1);

	if (strlen($lname) > 25 || strlen($lname) < 2)
		array_push($error_array,  2);
	
	if (empty($error_array)){
		//Everything is as expected!
		$user->changeName($fname,$lname);
		array_push($error_array, -1); //-1 means everything went alright!
		return; //to prevent any mischief
	}
}

if (isset($_POST['password']) and isset($_POST['password2'])){
	//Password
	$password = strip_tags($_POST['password']); //Remove html tags
	$password2 = strip_tags($_POST['password2']); //Remove html tags
	if ($password != $password2)
		array_push($error_array,  8);
	else
		if (preg_match('/[^A-Za-z0-9]/', $password))
			array_push($error_array, 9);

	if(strlen($password > 30 || strlen($password) < 6))
		array_push($error_array, 10);
		
	if (empty($error_array)){
		$user->changePassword(md5($password));
	}
}

?>