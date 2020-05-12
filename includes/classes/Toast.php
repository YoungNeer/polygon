<?php

class Toast{
	private $user_obj;
	private $pdo;

	public function __construct($pdo,&$user_obj){
		$this->pdo=$pdo;
		$this->user_obj=$user_obj;
	}

	public function getSingleToastHelper(&$row,&$added_by_user){
		$id=$row['id'];
		$post_text=$row['body'];
	
		$user_to=$row['user_to'];
		if ($user_to!=''){
			$user_to_obj=new User($this->pdo,$user_to);
			$user_to="</a> to <a href='profile.php?user=$user_to'>".$user_to_obj->getFullName();
		}


		// $start_date=new DateTime($row['date_added']);
		// $toastTime=$start_date->format('Y-m-d H:i:s');

		$toastTime=$row['date_added'];

		$like_obj=new Like($this->pdo,$id,$this->user_obj);

		$comment_obj=new Comment($this->pdo,$id,$added_by_user);
		$no_of_comments=$comment_obj->getNumberOfComments();

		$comment_icon='fa-comment';
		
		if ($no_of_comments!=0)
			$comment_icon=($no_of_comments>1?'fa-comments':'fa-comment-dots');

		if ($user){ //if the global variable is accessible
			$current_user=$user->getUsername();
		}else{
			$current_user=$_SESSION['user'];
		}

		return "
			<div class='toast'>
				<div class='toast-col'>
					<div class='left-col'>
						<div class='profile_pic'>
							<a href='".$added_by_user->getUsername()."'>
								<img src='".$added_by_user->getProfilePic()."'/>
							</a>
						</div>
					</div>
					<div class='right-col'>
						<div class='toasted_from' style='color:#acacac'>
							<a href='profile.php?user=".$added_by_user->getUsername()."'>".
								$added_by_user->getFullName()."$user_to</a>
							<span class='timestamp' data-time='$toastTime'></span>".
							(($current_user==$row['added_by'])?"
								<span onClick=\"javascript:deleteToast('".$current_user."',$id)\" class='dropArrow'></span>":"").
						"</div>
						<div class='toast_body'>$post_text<br/></div><br/>
					</div>
				</div>
				<div class='postsInfo'>
					<span class='commentsBtnContainer'>
						<i class='fa $comment_icon'></i>
						<span class='commentsBtn'
							onClick=\"javascript:toggleElement('toggleComment$id')\">Comments ($no_of_comments)</span>
					</span>
					<span id='likesContainer$id'>".
					$like_obj->getButtonState().
					"</span>
				</div>
			</div>
			<div class='post_comment' id='toggleComment$id' style='display:none'>
			<iframe src='includes/comment_frame.php?id=$id' class='comments'>
			</iframe>
			</div>
			<hr/>
		";
	}

	public function loadFriendPosts($page,$onlySelfPosts){
		$fromLimit=($page-1)*POSTS_LIMIT;
		$noMorePosts=FALSE;

		$body=""; //string to return;
		$query=$this->pdo->query("SELECT * FROM `posts` WHERE `deleted`=0 ORDER BY `id` DESC");
		$query->execute();
			
		if($query->rowCount()>$fromLimit+POSTS_LIMIT)
			//There are many more posts yet to retrieve
			$noMorePosts='false';
		elseif($query->rowCount()>=$fromLimit)
			//This is the last retrieval. No more posts remaining after that
			$noMorePosts=-1; //-1 a flag, but will imply true later
		else
			//No new post could be retrieved. There is just no more post!
			$noMorePosts='true';

		if ($noMorePosts!=='true'){
		
			$query=$this->pdo->query("SELECT * FROM `posts`
								WHERE `deleted`=0 ORDER BY `id` DESC
								LIMIT $fromLimit,".POSTS_LIMIT);
			$query->execute();

			while($row=$query->fetch(PDO::FETCH_ASSOC)){

				$added_by_user=new User($this->pdo,$row['added_by']);

				//Show only active users' post
				if (!$added_by_user->isActive()) continue;

				if (isset($onlySelfPosts) and $onlySelfPosts==='true'){
					if (!$this->user_obj->isSelf($row['added_by'])) continue;
				}
				else{
					if (!($this->user_obj->canViewPosts($row['added_by']))) continue;
				}

				$body.=$this->getSingleToastHelper($row,$added_by_user);
				
			}
		}
		echo $body;
		if ($noMorePosts==-1) $noMorePosts='true';
		$page++;
		
		echo "<input type='hidden' id='nextPage' value='$page'/>";
		echo "<input type='hidden' id='noMorePosts' value='$noMorePosts'/>";
		echo "<script type='text/javascript'>updateTimestamp()</script>";
	}

	public function deleteToast($post_id){
		$query=$this->pdo->prepare("DELETE FROM `posts` WHERE `id`=:post_id");
		$query->bindParam(":post_id", $post_id);
		$query->execute();

		// also delete the comments related to that posts? i guess yes!
		$comment_obj=new Comment($this->pdo,$post_id,$this->user_obj);
		$comment_obj->deleteAllComments();

		// also delete the notifications!
		$added_to=$this->getUserPostedTo($post_id); //the user to whom the post was toasted
		if ($added_to!=$this->user_obj->getUsername()){
			$notif_obj=new Notification($this->pdo,$this->user_obj);
			$notif_obj->deleteNotification('profile',$post_id,$added_to);
		}
	}
	
	public function addPost($body,$user_to){
		$body=prepareString($body);

		// If body is empty
		if (preg_replace("/\s+/",'',$body)=='')
			return;
		
		// $words=preg_match("/\s+/",$body);
		$words=explode(' ',$body);

		$media_found=FALSE;
		$card="";

		$trend_obj=new Trend($this->pdo);

		foreach($words as $i => $word){
			// get youtube videos, etc
			if (isYoutubeLink($word) and !$media_found){
				$card=embedYoutubeVideo($word);
				$media_found=TRUE;
				if ($i==0){
					$words[0]='';
					continue;
				}
			}
			if (isLink($word))
				$words[$i]=toHyperlink($word);
			else
				$trend_obj->calculateTrend($words[$i]);
		}

		// if ($words[0]=='' and substr($words[1],0,4)) $words[1]=substr($words[1],4);

		$body=implode(' ',$words).$card; //card should always be at the end

		$date_added=date('Y-m-d H:i:s');
		$added_by=$this->user_obj->getUserName();

		// If user is toasting to himself
		if ($added_by==$user_to)
			$user_to="";

		$query=$this->pdo->prepare("INSERT INTO
								posts(`body`,`added_by`,`user_to`,`date_added`)
								VALUES(:body, :added_by, :user_to, :date_added);
							");
		$query->bindParam(":body",$body);
		$query->bindParam(":added_by",$added_by);
		$query->bindParam(":user_to",$user_to);
		$query->bindParam(":date_added",$date_added);
		$query->execute();

		$this->user_obj->updatePosts(1); //increment posts by 1

		//Insert Notifications
		if ($user_to!=""){
			$notif=new Notification($this->pdo,$this->user_obj);
			$notif->insertNotification("profile",$this->getLatestToastID(),$user_to);
		}
	}

	public function updatePostLikes($post_id,$offset){
		$query=$this->pdo->prepare("SELECT `likes` FROM `posts` WHERE
							(`deleted`=0 AND `id`=:post_id) ORDER BY `id` DESC");
		$query->bindParam(':post_id',$post_id);
		$query->execute();
		$likes=$query->fetch(PDO::FETCH_ASSOC)['likes']+$offset;
		// echo "here$likes";

		$query=$this->pdo->prepare("UPDATE `posts` SET likes=:likes WHERE
							(`deleted`=0 AND `id`=:post_id)");

		$query->bindParam(":post_id", $post_id);
		$query->bindParam(':likes',$likes);
		$query->execute();
	}

	//See who posted a post
	public function getUserFromPost($post_id){
		$query=$this->pdo->prepare("SELECT `added_by` FROM `posts` WHERE `id`=:post_id");
		$query->bindParam(":post_id",$post_id);
		$query->execute();
		return $query->fetchColumn();
	}

	//See to whom the post was posted
	public function getUserPostedTo($post_id){
		$query=$this->pdo->prepare("SELECT `user_to` FROM `posts` WHERE `id`=:post_id");
		$query->bindParam(":post_id",$post_id);
		$query->execute();
		return $query->fetchColumn();
	}

	//Get the latest post id
	public function getLatestToastID(){
		$query=$this->pdo->prepare("SELECT `id` FROM `posts` ORDER BY `id` DESC");
		$query->execute();
		return $query->fetchColumn();
	}

	//see if the toast exists by the given id
	public function toastExists($post_id){
		$query=$this->pdo->prepare("SELECT COUNT(*) FROM `posts` WHERE `id`=:post_id");
		$query->bindParam(":post_id",$post_id);
		$query->execute();
		return $query->fetchColumn();
	}

	public function getSingleToast($id){
		$query=$this->pdo->prepare("SELECT * FROM `posts`
								WHERE `deleted`=0 AND `id`=:post_id");
		$query->bindParam(":post_id",$id,PDO::PARAM_INT);
		$query->execute();
		$row=$query->fetch(PDO::FETCH_ASSOC);
		$added_by_user=new User($this->pdo,$row['added_by']);

		echo $this->getSingleToastHelper($row,$added_by_user,TRUE);
	}

}
?>