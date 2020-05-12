<?php  

$error_array=array();
if(isset($_POST['username']) and isset($_POST['password'])) {
	$username = $_POST['username'];

	$password = md5($_POST['password']); //Get password

	// echo "<br>SELECT * FROM users WHERE username='$username' AND password='$password'<br>";

	$check_database_query = mysqli_query($con, "");
	$query = $pdo->prepare("SELECT * FROM users WHERE username=:username AND password=:pass");
	$query->bindParam(":username", $username);
	$query->bindParam(":pass", $password);
	$query->execute();

	if($query->rowCount() == 1) {
		$query = $pdo->prepare("SELECT * FROM users WHERE username=:username AND ac_verified=1");
		$query->bindParam(":username", $username);
		$query->execute();

		if($query->rowCount() == 1){
			$_SESSION['user'] = $username;
			$_SESSION['loggedIn'] = TRUE;
			header("Location: index.php");
			array_push($error_array, -1);
		}
		else
			array_push($error_array, 2);
	}
	else
		array_push($error_array, 1);
}

?>