<?php
	//prepare the string for database
	function prepareString($str){
		//basically replace new line with line break and remove tags
		return preg_replace("/\n/",' <br/>',trim(strip_tags($str)));
	}

	//Preresquisite: $url should be a word
	function isLink($url){
		if (strpos($url,'..')) return false;  //hello..com is NOT a valid URL
		$pos=strrpos($url,'.');
		//if https://in.this.form then it's obviously a URL 
		if (filter_var($url, FILTER_VALIDATE_URL)) return true; 
		//otherwise if in host.tld where the TLD should be atleast 2 letters long
		if (!strrpos($url,'/') and preg_match("/[^a-zA-Z]+/",substr($url,$pos+1))) return false;
		if ($pos and $pos<=strlen($url)-2) return true;
	}

	//Converts simple text to hyperlink (Preresquisite: $url should be a link)
	function toHyperlink($url){
		$text=$url;
		if (substr($url,0,4)!="http")
			$url="http://$url";
		return "<a href='$url' target='new'>$text</a>";
	}

	function isYoutubeLink($url){
		return (isLink($url) and strpos($url,"youtube.com/watch?v=")!==false);
	}

	//Preresquisite: $url should be a youtube video and must be trimmed down
	function embedYoutubeVideo($url){
		if (substr($url,0,4)=='www.' or substr($url,0,8)=='youtube.')
			$url="https://$url";
		$url=str_ireplace('watch?v=','embed/',preg_split('!&!',$url)[0]);
		return "<iframe class='youtube-card' width='440' height='320' src='$url'></iframe>";
	}

?>