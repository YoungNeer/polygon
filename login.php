<?php
require 'includes/constants.php';
require 'config/db_connect.php';
require 'includes/login_ext.php';
if (isset($_SESSION['loggedIn']))
	header('Location: index.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login to Polygon</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="assets/images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/fonts/-Free-v1.0.0/icon-font.min.css">
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
				<form method="POST" action="login.php" class="login100-form validate-form flex-sb flex-w">
					<span class="login100-form-title p-b-53">
						Log in to Polygon
					</span>

					<div class="login-error-message">
						<?php
						if(in_array(1, $error_array))
							echo "Incorrect username or password!!!";
						else if(in_array(2, $error_array))
						#TODO
							echo "Hi there, firstName! It seems you have't verified your account!
								Please check your email for the verification link! Resend Link?";
						?>
					</div>
					
					<div class="wrap-input100 validate-input" data-validate = "Username is required" style="margin-bottom:8px;">
						<input class="input100" type="text" placeholder="Username" name="username"
							value="<?php echo $_SESSION['username'] ?>"/>
						<span class="focus-input100"></span>
					</div>
					
					
					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" placeholder="Password" name="password"/>
						<span class="focus-input100"></span>
					</div>

					<div class="container-login100-form-btn m-t-17">
						
						<button class="login100-form-btn">
							Log In
						</button>
					</div>

					<div class="w-full text-center p-t-55">
						<span class="txt2">
							Not a member?
						</span>

						<a href="register.php" class="txt2 bo1">
							Register Now
						</a>
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