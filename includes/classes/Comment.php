<?php
class Comment{
	private $post_id; //The post on which to comment
	private $user_obj; //Who is commenting
	private $pdo;

	public function __construct(&$pdo,$post_id,&$user_obj){
		$this->pdo=$pdo;
		$this->post_id=$post_id;
		$this->user_obj=$user_obj;
	}

	public function postComment($comment_body){
		$now=date("Y-m-d H:i:s");

		$query = $this->pdo->prepare("SELECT `added_by` FROM `posts` WHERE `id`=:post_id");
		$query->bindParam(":post_id", $this->post_id);
		$query->execute();
		$user_to=$query->fetch(PDO::FETCH_ASSOC)['added_by'];

		$query=$this->pdo->prepare("INSERT INTO
								comments(`comment_body`,`posted_by`,`posted_to`,`date_added`,`post_id`)
								VALUES(:comment_body, :posted_by, :posted_to, :date_added, :post_id);
							");
		$query->bindParam(":comment_body",$comment_body);
		$query->bindParam(":posted_by",$this->user_obj->getUsername());
		$query->bindParam(":posted_to",$user_to);
		$query->bindParam(":date_added",$now);
		$query->bindParam(":post_id",$this->post_id);
		$query->execute();
		
		//Insert Notifications
		if ($user_to!=$this->user_obj->getUsername()){
			$notif=new Notification($this->pdo,$this->user_obj);
			$notif->insertNotification("comment",$this->post_id,$user_to);
		}

	}

	public function displayComments(){

		$query = $this->pdo->prepare("SELECT * FROM `comments` WHERE `post_id`=:post_id ORDER BY `id` ASC");
		$query->bindParam(":post_id", $this->post_id);
		$query->execute();

		$str="";

		if ($query->rowCount()>0)
			while ($row=$query->fetch(PDO::FETCH_ASSOC)){

				$comment_body=preg_replace("/\n/",'<br/>',$row['comment_body']);

				$posted_to=$row['posted_to'];
				$date_added=new DateTime($row['date_added']);
				$commentTime=$date_added->format('Y-m-d H:i:s');

				$added_by_user=new User($this->pdo,$row['posted_by']);

				$str.= "
					<div class='toast_comment'>
						<div class='profile_pic'>
							<a href='".$added_by_user->getUsername()."'>
								<img src='../".$added_by_user->getProfilePic()."'/>
							</a>
						</div>
						<div class='toasted_from'>
							<a href='".$added_by_user->getUsername()."'>".
								$added_by_user->getFullName()."$user_to</a>
							<span class='timestamp' data-time='$commentTime'></span>
						</div>
						<div class='toast_body' style='display:flex;'>$comment_body<br/></div>
					</div>";
			}
		else
			$str="<div class='emptyComment'>No comments to show</div>";
		echo $str;
	}

	//get number of comments
	public function getNumberOfComments(){
		$query = $this->pdo->prepare("SELECT * FROM `comments` WHERE `post_id`=:post_id");
		$query->bindParam(":post_id", $this->post_id);
		$query->execute();

		return $query->rowCount();
	}

	//delete all comments for the post
	public function deleteAllComments(){
		$query = $this->pdo->prepare("DELETE FROM `comments` WHERE `post_id`=:post_id");
		$query->bindParam(":post_id", $this->post_id);
		$query->execute();

		//Delete the notifications related to all comments to that post
		$query = $this->pdo->prepare("DELETE FROM `notifications` WHERE `post_id`=:post_id");
		$query->bindParam(":post_id", $this->post_id);
		$query->execute();
	}

	//Get the latest post id
	public function getLatestCommentID(){
		$query=$this->pdo->prepare("SELECT `id` FROM `comments` ORDER BY `id` DESC");
		$query->execute();
		return $query->fetchColumn();
	}
}
?>