<?php
class Notification{
	private $user_obj; //Who is messaging
	private $pdo;

	public function __construct($pdo,&$user_obj){
		$this->pdo=$pdo;
		$this->user_obj=$user_obj;
	}

	public function deleteNotification($type,$post_id,$user_to){
		$query = $this->pdo->prepare("DELETE FROM `notifications` WHERE
							(`user_to`=:user_to AND `notifType`=:notif_type AND `post_id`=:post_id)");
		$query->bindParam(":user_to", $user_to);
		$query->bindParam(":notif_type", $type);
		$query->bindParam(":post_id", $post_id);
		$query->execute();
	}

	public function insertNotification($type,$post_id,$user_to){
		$username=$this->user_obj->getFullName();
		$message="";
		$now=date("Y-m-d H:i:s");
		$link="post.php?id=$post_id";

		switch($type){
			case "comment":
				$message="$username commented on your post";
				break;
			case "like":
				$message="$username liked your post";
				break;
			case "profile":
				$message="$username posted on your profile";
				break;
		}

		$query=$this->pdo->prepare("INSERT INTO `notifications`
								(`user_to`,`user_from`,`message`,`post_id`,`notifType`,`date`)
								VALUES(:user_to, :user_from, :message_body, :post_id, :notif_type, :time_added)");

		$query->bindParam(":user_to",$user_to);
		$query->bindParam(":user_from",$this->user_obj->getUsername());
		$query->bindParam(":message_body",$message);
		$query->bindParam(":post_id",$post_id);
		$query->bindParam(":notif_type",$type);
		$query->bindParam(":time_added",$now);

		$query->execute();
	}

	//set notification to opened
	public function openNotification($type,$post_id,$user_to){
		$query = $this->pdo->prepare("UPDATE `notifications` SET `opened`=1 WHERE
							(`user_to`=:user_to AND `notifType`=:notif_type AND `post_id`=:post_id)");
		$query->bindParam(":user_to", $user_to);
		$query->bindParam(":notif_type", $type);
		$query->bindParam(":post_id", $post_id);
		$query->execute();
	}

	public function getConvoDropdown($page){

		$fromLimit=($page-1)*NOTIFICATIONS_LIMIT;
		$this_user=$this->user_obj->getUsername();
		$data="";
		$convos=array();

		$query = $this->pdo->prepare("SELECT COUNT(*) as total 
							FROM `notifications` WHERE `user_to`=:user_to");
		$query->bindParam(":user_to", $this_user);
		$query->execute();

		$rc=$query->fetchColumn();

		if($rc>$fromLimit+NOTIFICATIONS_LIMIT)
			//There are many more posts yet to retrieve
			$noMoreNotifs='false';
		elseif($rc>=$fromLimit)
			//This is the last retrieval. No more posts remaining after that
			$noMoreNotifs=-1; //-1 a flag, but will imply true later
		else
			//No new post could be retrieved. There is just no more post!
			$noMoreNotifs='true';

		if ($noMoreNotifs!=='true'){
			$view_notif_query=$this->pdo->prepare("UPDATE `notifications` SET `viewed`=1 WHERE `user_to`=:username");
			$view_notif_query->bindParam(":username",$this_user);
			$view_notif_query->execute();

			$query=$this->pdo->prepare("SELECT * FROM `notifications`
									WHERE `user_to`=:username ORDER BY `id` DESC
									LIMIT $fromLimit,".MESSAGES_LIMIT);

			$query->bindParam(":username",$this_user);
			$query->execute();

			$i=0;

			while($row=$query->fetch(PDO::FETCH_ASSOC)){
				$user_from=$row['user_from'];
				$post_id=$row['post_id'];
				$type=$row['notifType'];
				$msg_body=$row['message'];
				$msg_time=$row['date'];
				$isOpened=$row['opened'];

				$style=($isOpened?"":"background-color: #ddeeffb5");

				$other_user_obj=new User($this->pdo,$user_from);

				// $msg_body=str_split($msg_body,25)[0].((strlen($msg_body)>25)?"...":"");
				$data.="
						<div class='notifMessage' style='$style'onclick=\"javascript:window.location='toasts.php?id=$post_id&type=$type'\">
							<div class='profile_pic_small'>
								<img src='".$other_user_obj->getProfilePic()."'/>
							</div>
							<span class='timestamp' data-time='$msg_time' style='padding:0px'></span>
							<div class='notifMsgBody' style='color: #1485bd;'>
								$msg_body<br/>
							</div>
						</div>
						<hr/>
				";

				$i++;
			}

			if($i<NOTIFICATIONS_LIMIT) $noMoreNotifs='true';

		}

		if ($noMoreNotifs==-1) $noMoreNotifs='true';
		if ($noMoreNotifs=='false') $page++;
		
		$data.="<input type='hidden' id='nextNotif' value='$page'/>";
		$data.="<input type='hidden' id='noMoreNotifs' value='$noMoreNotifs'/>";
		$data.="<script type='text/javascript'>updateTimestamp()</script>";

		return $data;
	}
}
?>