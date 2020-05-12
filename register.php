<?php
require 'includes/constants.php';
require 'config/db_connect.php';
require 'includes/register_ext.php';
if (isset($_SESSION['loggedIn']) or isset($user))
	header('Location: index.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Register to Polygon</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="assets/images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="assets/css/login.css">
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('assets/images/bg-01.jpg');">
			<div class="wrap-login100 p-l-110 p-r-110 p-t-62 p-b-33">
				<form method="POST" action="register.php" class="login100-form validate-form flex-sb flex-w">
						
					<span class="login100-form-title p-b-53">
						Register to Polygon
					</span>

					<div class="login-error-message">
						<?php
						# Error First Name
						if(in_array(1, $error_array))
							echo "Your First Name must be between 2 and 25 characters<br/>";
						
						# Error Last Name
						elseif(in_array(2, $error_array))
							echo "Your Last Name must be between 2 and 25 characters<br/>";

						# Error Username
						elseif(in_array(5, $error_array))
							echo "Username must be between 4 and 24 characters!<br/>"; 
						elseif(in_array(6, $error_array))
							echo "Sorry the username is already in use!!!<br/>
								<span style='color:#252525'>
								Why not \"$suggestedUsername1\" or \"$suggestedUsername2\"?
								</span><br/>";
						elseif(in_array(7, $error_array))
							echo "Your username can only contain letters and numbers!<br/>";

						# Error Email
						elseif(in_array(3, $error_array))
							echo "Email already in use<br/>"; 
						elseif(in_array(4, $error_array))
							echo "Invalid email format<br/>";

						# Password Error
						elseif (in_array(8, $error_array))
							echo "Your passwords do not match<br/>"; 
						elseif(in_array(9, $error_array))
							echo "Your password contains some invalid characters!";
						elseif(in_array(10, $error_array))
							echo "Your password must be between 6 and 30 characters<br/>";

						# Todo seperate look
						if (in_array(-1, $error_array)){
							echo "<span style='color:green'>Awesome! Now login with your new credentials</span><br/>
								<script type='text/javascript'>
									setTimeout(() => {
										window.location='login.php'
									}, 2000);
								</script>
							";
						}
						?>
					</div>
					
					<span class="wrap-input102 validate-input" style="margin-right:5px" data-validate = "Required">
						<input class="input100" placeholder="First Name" type="text"
							name="fname" value="<?php echo $_SESSION['fname'] ?>">
						<span class="focus-input100"></span>
					</span>
					
					<span class="wrap-input102 validate-input" data-validate = "Required">
						<input class="input100" placeholder="Last Name" type="text"
							name="lname" value="<?php echo $_SESSION['lname'] ?>">
						<span class="focus-input100"></span>
					</span>

					<div class="wrap-input101 validate-input" data-validate = "Username is required">
						<input class="input100" placeholder="Username" type="text"
							name="username" value="<?php echo $_SESSION['username'] ?>">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input101 validate-input" data-validate = "Email is required">
						<input class="input100" placeholder="Email" type="text"
							name="email" value="<?php echo $_SESSION['email'] ?>">
						<span class="focus-input100"></span>
					</div>

					<span class="wrap-input102 validate-input" style="margin-right:5px" data-validate = "Required">
						<input class="input100" placeholder="Password" type="password"
							name="password" value="<?php echo $_SESSION['password'] ?>">
						<span class="focus-input100"></span>
					</span>
					
					<span class="wrap-input102 validate-input" data-validate = "Required">
						<input class="input100" placeholder="Confirm Password" type="password"
							name="password2" value="<?php echo $_SESSION['password2'] ?>">
						<span class="focus-input100"></span>
					</span>


					<div class="container-login100-form-btn m-t-17">
						<button class="login100-form-btn">
							Register
						</button>
					</div>
					
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<script src="vendor/bootstrap/popper.js"></script>
	<script src="vendor/bootstrap/bootstrap.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<script src="assets/js/login.js"></script>
</body>
</html>
