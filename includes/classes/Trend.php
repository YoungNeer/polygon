<?php

class Trend{
	private $user_obj;
	private $pdo;

	public function __construct($pdo){
		$this->pdo=$pdo;
	}

	public function calculateTrend($word){
		if (trim($word)=='' or strlen($word)<3) return;
		$word = preg_replace("/[^a-zA-Z 0-9]+/", "", $word); //remove puncuation marks
		$dHit=1; //how much to add to hits
		if (in_array(strtolower($word),STOP_WORDS)) //this word cannot be trending
			return;
		$word=strtoupper(substr($word,0,1)).substr($word,1); //to sentence case
		foreach (HOT_WORDS as $hot_word)
			if (strcasecmp($word,$hot_word)==0){
				$word=$hot_word; //use same case as in array
				$dHit=2; //this word has right to be trending
			}
		$already_in_trends=$this->pdo->prepare("SELECT COUNT(*) as rows 
					FROM `trends` WHERE `tname`=:title");
		$already_in_trends->bindParam(":title", $word);
		$already_in_trends->execute();

		if ($already_in_trends->fetchColumn()==0){
			$insert_query=$this->pdo->prepare("INSERT INTO `trends` VALUES (:word,$dHit)");
		}else{
			$insert_query=$this->pdo->prepare("UPDATE `trends` SET `hits`=`hits`+$dHit WHERE `tname`=:word");
		}
		$insert_query->bindParam(":word",$word);
		$insert_query->execute();
	}

	public function displayTrends(){
		echo "
			<div class='column trends'>
				<h4>Most Popular</h4><hr style='margin:initial'/><br/>
				<div class='trending_list'>
		";
		$rows=$this->pdo->query("SELECT * FROM trends ORDER BY `hits` DESC LIMIT ".TRENDS_LIMIT);

		foreach ($rows as $row) {
			
			$trend_name = $row['tname'];
			$trend_name=str_split($trend_name,14)[0].((strlen($trend_name)>14)?"...":"");

			echo "<div style'padding: 1px'>$trend_name</div><br/>";
		}
	echo "</div></div>";
	}
}

?>