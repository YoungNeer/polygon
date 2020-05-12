<?php
class Follow{
	private $friend; //Whom to add friend
	private $user; //Who is sending the friend request/removing friend,etc.
	private $pdo;

	public function __construct(&$pdo,&$friend,&$user){
		$this->pdo=$pdo;
		$this->friend=$friend;
		$this->user=$user;
	}

	//Friend or unfriend a user
	public function bumpUser(){
		$friend_id=$this->friend->getUsername();
		if ($this->user->isFriend($friend_id))
			$this->user->removeFriend($friend_id);
		else
			$this->user->sendFriendRequest($friend_id); //send friend request
	}

	//Get state of the bump button
	public function getButtonState(){
		$fn="onClick=\"javascript:bumpUser('".$this->friend->getUsername()."','".$this->user->getUsername()."')\"";
		// return $fn;

		if ($this->user->isSelf($this->friend->getUsername()))
			return "<button class='dimButton'><i class='fa fa-user'></i> This is You</button>";
		elseif ($this->user->isFriend($this->friend->getUsername()))
			return "<button class='redButton' $fn><i class='fa fa-user'></i> Remove Friend</button>";
		elseif ($this->friend->gotFriendRequest($this->user->getUsername()))
			return "<button class='dimButton responding'><i class='fa fa-clock'></i> Request Sent</button>";
		else
			return "<button class='blueButton' $fn><i class='fa fa-user'></i> Add Friend</button>";
	}

	//it is used only in profile.php!
	public function getNewsfeed(){
		$friend_id=$this->friend->getUsername();
		if (!$this->user->canViewPosts($friend_id))
			return "<div class='newsfeed_unavailable'>
				<i class='fa fa-eye-slash'></i> You cannot view toasts of this user
			</div>";
		else{
			$message_obj=new Message($this->pdo,$this->user);
			$body.="<div class='column newsfeed'>
				<ul class='nav nav-tabs' id='profileTabs' role='tablist' style='margin-bottom: 15px;'>
					<li role='presentation' class='active'>
						<a href='#home' aria-controls='home' role='tab' data-toggle='tab'>Home</a>
					</li>
					<li role='presentation'>
						<a href='#profile' aria-controls='profile' role='tab' data-toggle='tab'>Profile</a>
					</li>
					<li role='presentation'>
						<a href='#messages' aria-controls='message' role='tab' data-toggle='tab'>Messages</a>
					</li>
				</ul>
				<div class='tab-content'>
					<div role='tab-panel' class='tab-pane fade in active' id='home'>
						<form action='' method='POST'>
							<textarea name='post_body' class='post_text' placeholder='Got Something to Say?'></textarea>
							<input type='hidden' name='user_from' value='".$this->user->getUsername()."'>
							<input type='hidden' name='user_to' value='".($this->user->isSelf($friend_id)?'':$friend_id)."'>
							<button name='toast_btn' class='blueButton'>Toast</button>
						</form>
						".$this->friend->showNewsFeed('true')."
					
					<div role='tab-panel' class='tab-pane fade in' id='profile'>
						".$this->friend->showProfile()."
						<div class='btnGroup'>";
						if ($this->user->isSelf($friend_id))
							$body.="<button class='dimButton msgButton'>
								<i class='fa fa-envelope'></i> Unavailable</button>";
						else
							$body.="<button class='greenButton msgButton' data-toggle='modal' data-target='#message_modal' >
								<i class='fa fa-envelope'></i> Send Message</button>";
						$body.="<button class='blueButton' data-toggle='modal' data-target='#post_modal'>
									<i class='fa fa-edit'></i> Toast something
								</button>
						</div>
					</div>

					<div role='tab-panel' class='tab-pane fade in' id='messages'>".
						$message_obj->showMessageFeed($friend_id,TRUE)."
					</div>
				</div>
				
				
				<!-- Modal for Toast  -->
				<div class='modal fade' id='post_modal' tabindex='-1' role='dialog' aria-labelledby='postModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
								<h4 class='modal-title' id='postModalLabel'>Toast something</h4>
							</div>
							<div class='modal-body'>
								<form class='toast_form' action='profile.php' method='POST'>
									<div class='form-group'>
										<textarea class='form-control' name='post_body'></textarea>
										<input type='hidden' name='user_from' value='".$this->user->getUsername()."'>
										<input type='hidden' name='user_to' value=
											'".($this->user->isSelf($friend_id)?'':$friend_id)."'>
									</div>
								</form>
							</div>
							<div class='modal-footer'>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
								<button type='button' class='btn btn-primary' name='toast_btn' id='toast_btn'>Toast</button>
							</div>
						</div>
					</div>
				</div>
				<!-- / Modal for Toast -->

				<!-- Modal for Send Message  -->
				<div class='modal fade' id='message_modal' tabindex='-1' role='dialog' aria-labelledby='postModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
								<h4 class='modal-title' id='postModalLabel'>Send message to ".$this->friend->getFullName()."</h4>
							</div>
							<div class='modal-body'>
								<form class='message_form' method='POST'>
									<div class='form-group'>
										<textarea class='form-control' name='msg_body'></textarea>
										<input type='hidden' name='user_from' value='".$this->user->getUsername()."'>
										<input type='hidden' name='user_to' value='$friend_id'>
									</div>
								</form>
							</div>
							<div class='modal-footer'>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
								<button type='button' class='btn btn-primary' name='msg_btn' id='msg_btn'>Send</button>
							</div>
						</div>
					</div>
				</div>
				<!-- / Modal for Toast -->
			</div>
				";
			return $body;
			// return $body.$this->friend->showNewsFeed('true');
			//show only self posts
		}
	}
}
?>