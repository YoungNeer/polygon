<?php
class Message{
	private $user_obj; //Who is messaging
	private $pdo;

	public function __construct(&$pdo,&$user_obj){
		$this->pdo=$pdo;
		$this->user_obj=$user_obj;
	}

	//get the recent most user with whom this user interacted
	public function getMostRecentUser(){
		$username=$this->user_obj->getUsername();
		$query=$this->pdo->prepare("SELECT `user_to`,`user_from` FROM `messages`
								WHERE (`user_to`=:username OR `user_from`=:username)
								ORDER BY `id` DESC LIMIT 1");

		$query->bindParam(":username",$username);
		$query->execute();

		if ($query->rowCount()==0)
			return FALSE;
		
		$row=$query->fetch(PDO::FETCH_ASSOC);
		return (($row['user_to']!=$username)?$row['user_to']:$row['user_from']);
	}

	public function sendMessage($user_to,$msg_body){
		if ($msg_body=="") return;
		$query=$this->pdo->prepare("
			INSERT INTO messages(`user_to`,`user_from`,`body`,`date`)
			VALUES(:user_to,:user_from,:body,:date)
		");
		$today= date("Y-m-d H:i:s");
		$query->bindParam(":user_to",$user_to);
		$query->bindParam(":user_from",$this->user_obj->getUsername());
		$query->bindParam(":body",$msg_body);
		$query->bindParam(":date",$today);
		$query->execute();
	}

	public function showMessageFeed($user_to,$inside_profile){
		$body="";
		if ($user_to and $user_to!=$this->user_obj->getUsername()){
			$user_to_obj=new User($this->pdo,$user_to);
			if (!$inside_profile)
				$body.= "<h4>You and ".$user_to_obj->getFullName()."</h4><hr/><br/>";
			$body.="
			<div id='loadedMessages'>"
				.$this->getMessages($user_to).
			"</div>";
		}
		
		$body.= "<div class='send_message'>";
				if (!$user_to or $user_to==$this->user_obj->getUsername()){
					if (!$inside_profile)
						$body.= "<h4>New Message</h4><hr/><br/>";
					$body.="
						<span>Select the friend you'd like to message</span><br/><br/>
						To <input class='liveSearch' type='text' name='q' placeholder='Enter name here' autocomplete='off' onkeyup=\"liveSearch('".$this->user_obj->getUsername()."',this.value,'.results')\"><br/>
						<div class='results'></div>
					";
				}
				else{
					$body.= "
						<form style='display:flex' ".($inside_profile?"class='msgTabForm'":"action='messages.php?user=$user_to'")." method='POST'>
							<textarea name='msg_body' id='message_text_area'
								placeholder='Write your message ...'></textarea>
							<input type='hidden' name='user_from' value='".$this->user_obj->getUsername()."'>
							<input type='hidden' name='user_to' value='$user_to'>
							<input type='submit' ".($inside_profile?"id='msgTabBtn'":"")." name='message_submit' class='blueButton' value='Send'/>
						</form>
					";
				}
				$body.= "</div>";
		
		if ($user_to) {
			$body.= "<script type='text/javascript'>
				$(()=>{
					let msgDiv=document.getElementById('loadedMessages');
					if (msgDiv) msgDiv.scrollTop=msgDiv.scrollHeight
					refreshTimestamp(60000); //refresh after one minute
				})
			</script>";
		}
		return $body;
	}

	public function getMessages($user_from){
		$query=$this->pdo->prepare("UPDATE `messages` SET `opened`=1
						WHERE (`user_to`=:user_to AND `user_from`=:user_from);");
		$query->bindParam(":user_to",$this->user_obj->getUsername());
		$query->bindParam(":user_from",$user_from);
		$query->execute();

		$query=$this->pdo->prepare("SELECT * FROM `messages`
			WHERE (`user_to`=:user_to AND `user_from`=:user_from) OR
			(`user_to`=:user_from AND `user_from`=:user_to);");
		$query->bindParam(":user_to",$this->user_obj->getUsername());
		$query->bindParam(":user_from",$user_from);
		$query->execute();

		$data="";


		while ($row=$query->fetch(PDO::FETCH_ASSOC)){
			$user_to=$row['user_to'];
			$user_from=$row['user_from'];
			$msg_body=$row['body'];
			$msg_body=preg_replace("/\n/",'<br/>',$msg_body);
			// $date=$row['date'];

			$data.="
				<div class='message'>
					<span class='".(($user_to==$this->user_obj->getUsername())?'green':'blue')."'>
						 $msg_body
					</span>
				</div>
			";
		}

		return $data;
	}

	//{body,user_to,date}; hashtable not used for performance reasons
	public function getLatestMessage($first_user,$second_user){
		$details_array=array();

		$query=$this->pdo->prepare("SELECT * FROM `messages`
			WHERE (`user_to`=:user_to AND `user_from`=:user_from) OR
			(`user_to`=:user_from AND `user_from`=:user_to) ORDER BY `id` DESC LIMIT 1");
		$query->bindParam(":user_to",$first_user);
		$query->bindParam(":user_from",$second_user);
		$query->execute();

		$row=$query->fetch(PDO::FETCH_ASSOC);
		array_push($details_array,$row['body']);
		array_push($details_array,(($row['user_to']==$first_user)?$row['user_from']:"You")." said: ");
		array_push($details_array,$row['date']);
		return $details_array;
	}

	public function getConversations(){
		$this_user=$this->user_obj->getUsername();
		$data="";
		$convos=array();

		$query=$this->pdo->prepare("SELECT `user_to`,`user_from` FROM `messages`
								WHERE (`user_to`=:username OR `user_from`=:username)");

		$query->bindParam(":username",$this_user);
		$query->execute();

		while ($row=$query->fetch(PDO::FETCH_ASSOC)){
			$other_user=(($row['user_to']!=$this_user)?$row['user_to']:$row['user_from']);

			if (!in_array($other_user,$convos))
				array_push($convos,$other_user);
		}

		foreach($convos as $other_user){
			$other_user_obj=new User($this->pdo,$other_user);
			$msg_details=$this->getLatestMessage($this_user,$other_user);
			$msg_body=$msg_details[0];
			$who_said=$msg_details[1];
			$msg_time=$msg_details[2];

			$msg_body=str_split($msg_body,12)[0].((strlen($msg_body)>12)?"...":"");
			$data.="
					<hr/>
					<div class='conversationMessage' onclick=\"javascript:window.location='messages.php?user=$other_user'\">
						<div class='profile_pic'>
							<img src='".$other_user_obj->getProfilePic()."'/>
						</div>
						<div class='toasted_from' style='color:#acacac'>
							<a href='profile.php?user=$other_user'>".
								$other_user_obj->getFullName()."</a>
							<span class='timestamp' data-time='$msg_time'></span>
						</div>
						<div class='lastMessageBody'>$who_said.$msg_body<br/></div>
						<!--a class='readMore' href='messages.php?user=$other_user'>
							Read More
						</a-->
					</div>
			";
		}

		return $data;
	}

	public function getConvoDropdown($page){

		$fromLimit=($page-1)*MESSAGES_LIMIT;
		$this_user=$this->user_obj->getUsername();
		$data="";
		$convos=array();

		$query = $this->pdo->prepare("SELECT COUNT(*) as total 
							FROM `messages` WHERE `user_to`=:user_to");
		$query->bindParam(":user_to", $this_user);
		$query->execute();

		$rc=$query->fetchColumn();

		if($rc>$fromLimit+MESSAGES_LIMIT)
			//There are many more posts yet to retrieve
			$noMoreMsg='false';
		elseif($rc>=$fromLimit)
			//This is the last retrieval. No more posts remaining after that
			$noMoreMsg=-1; //-1 a flag, but will imply true later
		else
			//No new post could be retrieved. There is just no more post!
			$noMoreMsg='true';

		if ($noMoreMsg!=='true'){
			$view_msg_query=$this->pdo->prepare("UPDATE `messages` SET `viewed`=1 WHERE `user_to`=:username");
			$view_msg_query->bindParam(":username",$this_user);
			$view_msg_query->execute();

			$query=$this->pdo->prepare("SELECT `user_from`,`id` FROM `messages`
									WHERE `user_to`=:username ORDER BY `id` DESC
									LIMIT $fromLimit,".MESSAGES_LIMIT);

			$query->bindParam(":username",$this_user);
			$query->execute();

			while ($row=$query->fetch(PDO::FETCH_ASSOC)){
				$user_from=$row['user_from'];

				$get_id_query=$this->pdo->prepare("SELECT `id` FROM `messages`
									WHERE (`user_to`=:to AND `user_from`=:from) ORDER BY `id` DESC");

				$get_id_query->bindParam(":to",$this_user);
				$get_id_query->bindParam(":from",$user_from);
				$get_id_query->execute();
				$id=$get_id_query->fetch(PDO::FETCH_ASSOC)['id'];

				if ($id==$row['id'] and !in_array($user_from,$convos))
					array_push($convos,$user_from);
			}

			$i=0;

			foreach($convos as $user_from){

				$view_msg_query=$this->pdo->prepare("SELECT `opened` FROM `messages`
											WHERE (`user_to`=:to AND `user_from`=:from) ORDER BY `id` DESC");
				$view_msg_query->bindParam(":to",$this_user);
				$view_msg_query->bindParam(":from",$user_from);
				$view_msg_query->execute();
				$isOpened=$view_msg_query->fetch(PDO::FETCH_ASSOC)['opened'];

				$style=($isOpened?"":"background-color: #ddeeffb5");

				$other_user_obj=new User($this->pdo,$user_from);
				$msg_details=$this->getLatestMessage($this_user,$user_from);
				$msg_body=$msg_details[0];
				$who_said=$msg_details[1];
				$msg_time=$msg_details[2];

				if ($who_said=='You said: ') continue;

				$msg_body=str_split($msg_body,25)[0].((strlen($msg_body)>25)?"...":"");
				$data.="
						<div class='notifMessage' style='$style' onclick=\"javascript:window.location='messages.php?user=$user_from'\">
							<div class='profile_pic_small'>
								<img src='".$other_user_obj->getProfilePic()."'/>
							</div>
							<div class='toasted_from' style='color:#acacac'>
								<a href='profile.php?user=$user_from'>".
									$other_user_obj->getFullName()."</a>
								<span class='timestamp' data-time='$msg_time'></span>
							</div>
							<div class='lastMessageBody'>$msg_body<br/></div>
						</div>
						<hr/>
				";

				$i++;
			}

			if($i<MESSAGES_LIMIT) $noMoreMsg='true';

		}

		if ($noMoreMsg==-1) $noMoreMsg='true';
		if ($noMoreMsg=='false') $page++;
		
		$data.="<input type='hidden' id='nextNotif' value='$page'/>";
		$data.="<input type='hidden' id='noMoreNotifs' value='$noMoreMsg'/>";
		$data.="<script type='text/javascript'>updateTimestamp()</script>";

		return $data;
	}
}
?>