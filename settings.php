<?php
include ('includes/header.php');
include ('includes/settings_ext.php');
?>
<title>User Settings - Polygon</title>
<link rel="stylesheet" type="text/css" href="assets/css/settings.css"/>

<main>
	<div class="column">
		<div class="header">
			<h1 style="text-align: center; font-size: 36px;">
				Account Settings
			</h1>
			<hr/>
		</div>
		<div class="settings-error-message">
			<?php
			# Error First Name
			if(in_array(1, $error_array))
				echo "Your First Name must be between 2 and 25 characters<br/>";
			
			# Error Last Name
			elseif(in_array(2, $error_array))
				echo "Your Last Name must be between 2 and 25 characters<br/>";

			# Password Error
			elseif (in_array(8, $error_array))
				echo "Your passwords do not match<br/>"; 
			elseif(in_array(9, $error_array))
				echo "Your password contains some invalid characters!";
			elseif(in_array(10, $error_array))
				echo "Your password must be between 6 and 30 characters<br/>";

			# Todo seperate look
			if (in_array(-1, $error_array)){
				echo "<span style='color:rgb(29, 161, 242) !important'>
						<img src='assets/images/svg/twitter.svg'
						style='height:20px;margin-right: 5px;margin-top: -2px;'/>	
						Please wait. Saving your settings
					</span>
					<script type='text/javascript'>
						setTimeout(() => {
							window.location='settings.php'
						}, 5000);
					</script>
				";
			}
			?>
		</div>
		<?php
			echo "
			<div class='detailsContainer'>
			<div class='details photo'>
				<a class='frame' href='upload.php'>
					<img src='".$user->getProfilePic()."'/>
				</a><br/>
				<div style='text-align:center'>
					<a style='color: #635757;text-decoration: underline;' href='upload.php'>
						Click
					</a> to upload
				</div>
			</div>
			<div class='details left'>
				First Name  <br/>
				Second Name  <br/>
				<br style='line-height: 4.2em;'/>
				Enter Password  <br/>
				Re-enter Password  <br/>
			</div>
			<div class='details right'>
				<form method='POST'>
					<input value='".$user->getFirstName()."' type='text' name='fname'/><br/>
					<input value='".$user->getLastName()."' type='text' name='lname'/><br/>
					<button>Update Details</button>
				</form>
				<form method='POST'>
					<br style='line-height: 3.7em;'/>
					<input placeholder='Password' type='password' name='password'/><br/>
					<input placeholder='Confirm Password' type='password' name='password2'/><br/>
					<button>Update Password</button>
				</form>
			</div>
			</div>
			
			";
		?>
		<p style="margin-top:5px;margin-bottom: 10px">
				<svg style="padding-top: 5px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
					<path fill="#00ad9f" d="M11.9998836,4.09370803 L8.55809517,7.43294953 C8.23531459,7.74611298 8.23531459,8.25388736 8.55809517,8.56693769 L12,11.9062921 L9.84187871,14 L4.24208544,8.56693751 C3.91930485,8.25388719 3.91930485,7.74611281 4.24208544,7.43294936 L9.84199531,2 L11.9998836,4.09370803 Z"></path>
				</svg>
			<a id="back-link" href=".">Back to Home</a>
		</p>
	</div>
</main>
