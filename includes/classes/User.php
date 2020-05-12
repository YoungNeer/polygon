<?php
class User{
	private $user_array;
	private $pdo;

	public function __construct(&$pdo,$username){
		$this->pdo=$pdo;
		$query = $pdo->prepare("SELECT * FROM users WHERE username=:username");
		$query->bindParam(":username", $username);
		$query->execute();
		$this->user_array=$query->fetch(PDO::FETCH_ASSOC);
	}

	public function getUsername(){
		return $this->user_array['username'];
	}

	public function getFirstname(){
		return $this->user_array['fname'];
	}

	public function getLastName(){
		return $this->user_array['lname'];
	}

	public function getFullName(){
		return $this->user_array['fname'].' '.$this->user_array['lname'];
	}

	public function getEmail(){
		return $this->user_array['email'];
	}

	public function getID(){
		return $this->user_array['id'];
	}

	public function getProfilePic(){
		return PROFILE_PIC_LOCATION.$this->user_array['pic'];
	}

	public function getNumPosts(){
		return $this->user_array['num_posts'];
	}

	public function getNumLikes(){
		return $this->user_array['num_likes'];
	}

	public function getNumFriends(){
		return substr_count($this->user_array['friends'],',')-1;
	}

	public function getNumUnreadMessages() {
		$query = $this->pdo->prepare("SELECT * FROM `messages` WHERE `viewed`=0 AND `user_to`=:username");
		$query->bindParam(":username",$this->getUsername());
		$query->execute();
		return $query->rowCount();
	}

	public function getNumUnreadNotifications() {
		$query = $this->pdo->prepare("SELECT * FROM `notifications` WHERE `viewed`=0 AND `user_to`=:username");
		$query->bindParam(":username",$this->getUsername());
		$query->execute();
		return $query->rowCount();
	}

	public function getNumUnseenFriendRequests(){
		$query = $this->pdo->prepare("SELECT COUNT(*) as total 
							FROM `friend_requests` WHERE `user_to`=:user_to");

		$query->bindParam(":user_to", $this->user_array['username']);
		$query->execute();

		return $query->fetchColumn();
	}

	public function getFriendList(){
		return $this->user_array['friends'];
	}

	public function getDateJoined(){
		return date("jS M Y", strtotime($this->user_array['joined_on']));
	}

	public function getDateLastOnline(){
		return $this->user_array['last_online'];
		// return date("jS M Y", strtotime($this->user_array['last_online']));
	}

	public function isSelf($username){
		return $this->user_array['username']==$username;
	}

	public function isFriend($username){
		return strstr($this->user_array['friends'],",$username,");
	}

	public function canViewPosts($username){
		return ($this->isSelf($username) or $this->isFriend($username));
	}

	public function isActive(){
		return !$this->user_array['ac_closed'];
	}

	public function isVerified(){
		return $this->user_array['ac_verified'];
	}
	
	//display newsfeed of self and friends (proper container should be added before #posts_area)
	//also the file calling this must be in main directory for the buffering image to be accessible
	public function showNewsFeed($onlySelfPosts){
		if ($onlySelfPosts!=='true') $onlySelfPosts='';
		// return $onlySelfPosts;
		return
				"
				<div id='posts_area'>
					<input type='hidden' id='nextPage' value='1'/>
					<input type='hidden' id='noMorePosts' value='false'/>
				</div>
					<div class='postEndMessage'>No more toasts!</div>
					<div id='loading'>
						<img src='assets/images/svg/oval_loader.svg'/>
					</div>
			</div>
				<script type='text/javascript'>
					let \$username='".$this->user_array['username']."';
					$(()=>{
						$('#loading').on('inview',function(event,isInview){
							if (!isInview) return;
							loadNewToasts(\$username,$onlySelfPosts);
							//To prevent any glitch - like the buffering still there
							setTimeout(() => {
								if (checkInView('#loading'))
									loadNewToasts(\$username,$onlySelfPosts);
							}, 2000);
						})
						refreshTimestamp(60000); //refresh after one minute
					})
				</script>";

	}

	//show bio and other information
	public function showProfile(){
		$body= "
			<div class='detailsContainer'>
			<div class='details left'>
				Full Name  <br/>
				Username  <br/>
				Friends <br/>
				Number of Posts<br/>
				Number of Likes<br/>
				Date Joined<br/>
				Last Online<br/>
			</div>
			<div class='details right'>
				".$this->getFullName()."<br/>
				".$this->getUsername()."<br/>
				".$this->getNumFriends()."<br/>
				".$this->getNumPosts()."<br/>
				".$this->getNumLikes()."<br/>
				".$this->getDateJoined()."<br/>
				<span class='timestamp' style='padding-left:0px !important;color:grey' data-time='".$this->getDateLastOnline()."'></span><br/>
			</div>
			<div class='details photo'>
				<img src='".$this->getProfilePic()."'/>
			</div>
			</div>
		";
		return $body;
	}

	//friend request label
	public function getFRMsg(){
		$rc=$this->getNumUnseenFriendRequests();
		if ($rc==0)
			return "There are no friend requests in your inbox at this time.";
		else
			return "A total of $rc user". ($rc==1?'':'s')." have sent you friend request!";
	}

	//display friend requests
	public function displayFriendRequests(){
		$query = $this->pdo->prepare("SELECT `user_from` FROM `friend_requests` WHERE `user_to`=:user_to");
		$query->bindParam(":user_to", $this->user_array['username']);
		$query->execute();
		$body="<span id='fr_message'>".$this->getFRMsg()."</span>";
		
		while ($row=$query->fetch(PDO::FETCH_ASSOC)){
			$userFrom=$row['user_from'];
			$userTo=$this->getUsername();
			$row_user_obj=new User($this->pdo,$userFrom);
			$body.="
			<div class='friend_request' id='requestFrom$userFrom'>
				<div class='profile_pic'>
					<a href='".$row_user_obj->getUsername()."'>
						<img src='".$row_user_obj->getProfilePic()."'>
					</a>
				</div>
				<div class='toasted_from' style='color:#6b6b6b'>
					<a style='color:#6b6b6b' href='".$row_user_obj->getUsername()."'>".
						$row_user_obj->getFullName().
					"</a> sent you a friend request!
				</div>
				<div class='request_buttons'>
					<button class='acceptBtn' onClick='javascript:acceptFR(\"$userFrom\",\"$userTo\")'>
						<i class='fa fa-check-circle'></i> Accept
					</button>
					<button class='rejectBtn' onClick='javascript:rejectFR(\"$userFrom\",\"$userTo\")'>
						<i class='fa fa-times-circle'></i> Reject
					</button>
				</div>
			</div>";
		}
		return $body;
	}

	//increment or decrement likes! Usually $value will be 1 or -1 as per need
	public function updatePosts($value){
		$this->user_array['num_posts']+=$value;
		$query = $this->pdo->prepare("UPDATE `users` SET num_posts=:num_posts WHERE username=:username");
		$query->bindParam(":num_posts", $this->user_array['num_posts']);
		$query->bindParam(":username", $this->user_array['username']);
		$query->execute();
	}

	//increment or decrement likes! Usually $value will be 1 or -1 as per need
	public function updateLikes($value){
		$this->user_array['num_likes']+=$value;
		$query = $this->pdo->prepare("UPDATE `users` SET num_likes=:num_likes WHERE username=:username");
		$query->bindParam(":num_likes", $this->user_array['num_likes']);
		$query->bindParam(":username", $this->user_array['username']);
		$query->execute();
	}

	public function changeName($fname,$lname){
		$this->user_array['fname']=$fname;
		$this->user_array['lname']=$lname;
		$query = $this->pdo->prepare("
			UPDATE `users` SET `fname`=:firstName,`lname`=:lastName WHERE `username`=:username"
		);
		$query->bindParam(":firstName", $this->user_array['fname']);
		$query->bindParam(":lastName", $this->user_array['lname']);
		$query->bindParam(":username", $this->user_array['username']);
		$query->execute();
	}

	public function changePassword($passwordHash){
		$this->user_array['password']=$passwordHash;
		$query = $this->pdo->prepare("UPDATE `users` SET `password`=:pass WHERE `username`=:username");
		$query->bindParam(":pass", $this->user_array['password']);
		$query->bindParam(":username", $this->user_array['username']);
		$query->execute();
	}

	//Helper function!
	private function updateFriendList(&$friend_list){
		$this->user_array['friends']=$friend_list;
		$query = $this->pdo->prepare("UPDATE `users` SET friends=:friend_list WHERE username=:username");
		$query->bindParam(":friend_list", $this->user_array['friends']);
		$query->bindParam(":username", $this->user_array['username']);
		$query->execute();
	}

	public function addFriend($friend_id){ //TODO: private function
		$this->user_array['friends'].="$friend_id,";
		$this->updateFriendList($this->user_array['friends']);
	}

	public function removeFriend($username){
		$this->updateFriendList(str_replace("$username,", '', $this->user_array['friends']));
	}

	//send friend request to another user
	public function sendFriendRequest($username){
		$query=$this->pdo->prepare("INSERT INTO
								friend_requests(`user_to`,`user_from`)
								VALUES(:user_to, :user_from);
							");
		$query->bindParam(":user_from", $this->user_array['username']);
		$query->bindParam(":user_to", $username);
		$query->execute();
	}

	private function removeFriendRequest($username){ //private function
		$query=$this->pdo->prepare("DELETE FROM `friend_requests`
								WHERE (`user_to`=:user_to AND `user_from`=:user_from)");
		$query->bindParam(":user_from", $username);
		$query->bindParam(":user_to", $this->user_array['username']);
		$query->execute();
	}

	public function acceptFriendRequest($username){
		$this->addFriend($username);
		$this->removeFriendRequest($username);
	}
	
	public function rejectFriendRequest($username){
		$this->removeFriendRequest($username);
	}

	//if this user has got friend request from another user
	public function gotFriendRequest($username){
		$query = $this->pdo->prepare("SELECT * FROM `friend_requests`
									WHERE (`user_to`=:user_to AND `user_from`=:user_from)");
		$query->bindParam(":user_to", $this->user_array['username']);
		$query->bindParam(":user_from", $username);
		$query->execute();
		return $query->rowCount()!=0;
	}

	public function updateProfilePic($path){
		$query = $this->pdo->prepare("UPDATE `users` SET pic=:path WHERE `username`=:username");
		$query->bindParam(":path",$path);
		$query->bindParam(":username",$this->getUsername());
		$query->execute();
	}

}
?>