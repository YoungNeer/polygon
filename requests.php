<?php
include ('includes/header.php');
?>
<title>Friend Requests - Polygon</title>


<style>
	main {
		position: relative;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		margin-top:120px;
		width: 100vw;
	}

	main .column{
		width:90%;
		max-width:600px;
	}

	.body{
		margin-left: 15%;
	}

	@media (max-width: 600px){
		.body{
			margin-left:10px
		}
	}

	.main-content{
		line-height: 1.5em;
		margin-top:30px;
	}
	#back-link{
		font-weight: 600;
		color: #00ad9f;
	}

	#back-link:hover{
		text-decoration:underline;
	}

	.friend_request{
		min-height:75px;
		margin-top: 20px;
	}

	.request_buttons{
		margin-top: 10px;
	}

	.request_buttons button{
		border: none;
		padding: 5px 15px;
		border-radius: 5px;
		color:white;
		cursor:pointer;
	}

	.request_buttons button:focus{
		outline:0;
		background:dimgrey;
	}

	@media (max-width:410px){
		main .column{
			width:100%;
		}
		.request_buttons button{
			padding: 5px 10px;
		}
	}

	.acceptBtn{
		background:#2ecc71;
	}

	.acceptBtn:hover{
		background:#61d994;
	}

	.rejectBtn{
		background:#e74c3c;
		margin-left:10px;
	}

	.rejectBtn:hover{
		background:#fc5c4b;
	}
	
</style>
	<main>
		<div class="column">
			<div class="header">
				<h1 style="text-align: center; font-size: 36px;">Friend Requests</h1>
			</div>
			<div class="body">
				<p class='main-content'>
					<?php
					// $user->removeFriendRequests('mickey');
					echo $user->displayFriendRequests();?>
				</p>
				<p style="margin-bottom: -5px;margin-top: 25px;">
						<svg style="padding-top: 5px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
							<path fill="#00ad9f" d="M11.9998836,4.09370803 L8.55809517,7.43294953 C8.23531459,7.74611298 8.23531459,8.25388736 8.55809517,8.56693769 L12,11.9062921 L9.84187871,14 L4.24208544,8.56693751 C3.91930485,8.25388719 3.91930485,7.74611281 4.24208544,7.43294936 L9.84199531,2 L11.9998836,4.09370803 Z"></path>
						</svg>
					<a id="back-link" href=".">Back to Home</a>
				</p>
			</div>
		</div>
	</main>
</body>
</html>