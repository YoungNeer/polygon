<?php
class Like{
	private $post_id; //The post on which to like
	private $user_obj; //Who is liking
	private $pdo;

	public function __construct(&$pdo,$post_id,&$user_obj){
		$this->pdo=$pdo;
		$this->post_id=$post_id;
		$this->user_obj=$user_obj;
	}

	//Like or Unlike a post
	public function bumpToast(){
		if ($this->isAlreadyLiked())
			$this->unlikeToast();
		else
			$this->likeToast();
	}

	//Get state of the bump button
	public function getButtonState(){
		$str="<span class='bumpBtnContainer'>";
		$id="onClick=\"javascript:bumpPost($this->post_id,'".$this->user_obj->getUsername()."')\"";
		$nl=$this->getNumberOfLikes();
		if ($this->isAlreadyLiked())
			$str.="<span class='unlikeBtn' $id> Unlike</span><i class='thumbs_clicked ";
		else
			$str.="<span class='likeBtn' $id> Like</span><i class='";
		$str.="fa fa-thumbs-up'></i> &nbsp;$nl</span>";
		return $str;
	}

	public function likeToast(){
		$username=$this->user_obj->getUsername();
		$query=$this->pdo->prepare("INSERT INTO
								likes(`username`,`post_id`)
								VALUES(:username, :post_id);
							");
		$query->bindParam(":username", $username);
		$query->bindParam(":post_id", $this->post_id);
		$query->execute();
		$this->user_obj->updateLikes(1); //increment likes by 1
		$toast=new Toast($this->pdo,$this->user_obj);
		$toast->updatePostLikes($this->post_id,1); //increment likes by 1

		//Insert Notification
		$added_by=$toast->getUserFromPost($this->post_id);
		if ($username!=$added_by){
			$notif=new Notification($this->pdo,$this->user_obj);
			$notif->insertNotification("like",$this->post_id,$added_by);
		}
	}

	public function unlikeToast(){
		$query = $this->pdo->prepare("DELETE FROM `likes` WHERE `username`=:un AND `post_id`=:id");
		$query->bindParam(":un", $this->user_obj->getUsername());
		$query->bindParam(":id", $this->post_id);
		$query->execute();
		$this->user_obj->updateLikes(-1); //decrement likes by 1
		$toast=new Toast($this->pdo,$this->user_obj);
		$toast->updatePostLikes($this->post_id,-1); //decrement likes by 1

		//Delete Notification
		$added_by=$toast->getUserFromPost($this->post_id);
		if ($username!=$added_by){
			$notif=new Notification($this->pdo,$this->user_obj);
			$notif->deleteNotification("like",$this->post_id,$added_by);
		}
	}

	//check if given post is already liked
	public function isAlreadyLiked(){
		$query = $this->pdo->prepare("SELECT * FROM `likes` WHERE `username`=:un AND `post_id`=:id");
		$query->bindParam(":un", $this->user_obj->getUsername());
		$query->bindParam(":id", $this->post_id);
		$query->execute();
		return $query->rowCount();
	}

	//get number of likes
	public function getNumberOfLikes(){
		$query = $this->pdo->prepare("SELECT `likes` FROM `posts` WHERE `id`=:post_id");
		$query->bindParam(":post_id", $this->post_id);
		$query->execute();

		return $query->fetch(PDO::FETCH_ASSOC)['likes'];
	}
}
?>