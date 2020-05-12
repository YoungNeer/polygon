<?php
include ('includes/header.php');
?>
<title>Account doesn't exist - Polygon</title>
<style>

	#back-link{
		font-weight: 600;
		color: #00ad9f;
	}

	#back-link:hover{
		text-decoration:underline;
	}

	.header{
		margin-bottom:10px;
	}

	h1{
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
		font-size: 26px;
	}
	
	main {
		position: relative;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		height: 100vh;
		width: 100vw;
	}

.card {
	position: relative;
	display: flex;
	flex-direction: column;
	width: 75%;
	max-width: 364px;
	padding: 24px;
	background: white;
	color: rgb(14, 30, 37);
	border-radius: 8px;
	box-shadow: 0 2px 4px 0 rgba(14, 30, 37, .16);
}
</style>
	<main>
		<div class="card">
			<div class="header">
				<h1 style="text-align: center;">User Closed</h1>
			</div>
			<div class="body">
				<p style="line-height: 1.5em;">
					Looks like this user has closed his account or the account may have been suspended
				</p>
				<p style="margin-bottom: -5px;">
						<svg style="padding-top: 5px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
							<path fill="#00ad9f" d="M11.9998836,4.09370803 L8.55809517,7.43294953 C8.23531459,7.74611298 8.23531459,8.25388736 8.55809517,8.56693769 L12,11.9062921 L9.84187871,14 L4.24208544,8.56693751 C3.91930485,8.25388719 3.91930485,7.74611281 4.24208544,7.43294936 L9.84199531,2 L11.9998836,4.09370803 Z"></path>
						</svg>
					<a id="back-link" href=".">Back to Polygon</a>
				</p>
			</div>
		</div>
	</main>
</div>
</body>
</html>