<?php 

/**
 * Not coded by me! Don't blame me for bugs or thank me for anything!
 * Credit goes to the person from whom i stole this code
**/

include("includes/header.php");

$profile_id = $user->getUsername();
$imgSrc = "";
$result_path = "";
$msg = "";

/***********************************************************
	0 - Remove The Temp image if it exists
***********************************************************/
	if (!isset($_POST['x']) && !isset($_FILES['image']['name']) ){
		//Delete users temp image
			$temppath = "assets/images/profile_pics/uploads/$profile_id.jpeg";
			if (file_exists ($temppath)){ @unlink($temppath); }
	} 


if(isset($_FILES['image']['name'])){	
/***********************************************************
	1 - Upload Original Image To Server
***********************************************************/	
	//Get Name | Size | Temp Location		    
		$ImageName = $_FILES['image']['name'];
		$ImageSize = $_FILES['image']['size'];
		$ImageTempName = $_FILES['image']['tmp_name'];
	//Get File Ext   
		$ImageType = @explode('/', $_FILES['image']['type']);
		$type = $ImageType[1]; //file type	
	//Set Upload directory    
		$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/facebook/assets/images/profile_pics/uploads';
	//Set File name	
		$file_temp_name = $profile_id.'_original.'.md5(time()).'.'.$type; //the temp file name
		$fullpath = $uploaddir."/".$file_temp_name; // the temp file path
		$file_name = "$profile_id.jpeg"; //$profile_id.'_temp.'.$type; // for the final resized image
		$fullpath_2 = $uploaddir."/".$file_name; //for the final resized image
	//Move the file to correct location
		$move = move_uploaded_file($ImageTempName ,$fullpath) ; 
		chmod($fullpath, 0777);  
		//Check for valid uplaod
		if (!$move) { 
			$_POST=NULL;
			die ("
				<link rel=\"stylesheet\" href=\"assets/css/upload.css\" type=\"text/css\" />
				<main><div class='column'>The file couldn't be uploaded. Please try again!</div></main>
			");
		} else { 
			$imgSrc= "assets/images/profile_pics/uploads/$file_name"; // the image to display in crop area
			$msg= "Image Uploaded";  	//message to page
			$src = $file_name;	 		//the file name to post from cropping form to the resize		
		} 

/***********************************************************
	2  - Resize The Image To Fit In Cropping Area
***********************************************************/		
		//get the uploaded image size	
			clearstatcache();				
			$original_size = getimagesize($fullpath);
			$original_width = $original_size[0];
			$original_height = $original_size[1];	
		// Specify The new size
			$main_width = 500; // set the width of the image
			$main_height = $original_height / ($original_width / $main_width);	// this sets the height in ratio									
		//create new image using correct php func			
			if($_FILES["image"]["type"] == "image/gif"){
				$src2 = imagecreatefromgif($fullpath);
			}elseif($_FILES["image"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/pjpeg"){
				$src2 = imagecreatefromjpeg($fullpath);
			}elseif($_FILES["image"]["type"] == "image/png"){ 
				$src2 = imagecreatefrompng($fullpath);
			}else{ 
				$msg .= "There was an error uploading the file. Please upload a .jpg, .gif or .png file. <br />";
			}
		//create the new resized image
			$main = imagecreatetruecolor($main_width,$main_height);
			imagecopyresampled($main,$src2,0, 0, 0, 0,$main_width,$main_height,$original_width,$original_height);
		//upload new version
			$main_temp = $fullpath_2;
			imagejpeg($main, $main_temp, 90);
			chmod($main_temp,0777);
		//free up memory
			imagedestroy($src2);
			imagedestroy($main);
			//imagedestroy($fullpath);
			@ unlink($fullpath); // delete the original upload					
									
}//ADD Image 	

/***********************************************************
	3- Cropping & Converting The Image To Jpg
***********************************************************/
if (isset($_POST['x'])){
	//the file type posted
		$type = $_POST['type'];	
	//the image src
		$src = 'assets/images/profile_pics/uploads/'.$_POST['src'];	
		$finalname = $profile_id;
	
	if($type == 'jpg' || $type == 'jpeg' || $type == 'JPG' || $type == 'JPEG'){	
		//the target dimensions 150x150
			$targ_w = $targ_h = 150;
		//quality of the output
			$jpeg_quality = 90;
		//create a cropped copy of the image
			$img_r = imagecreatefromjpeg($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );

			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		//save the new cropped version
			imagejpeg($dst_r, "assets/images/profile_pics/uploads/".$finalname.".jpg", 90); 	
			 		
	}else if($type == 'png' || $type == 'PNG'){
		
		//the target dimensions 150x150
			$targ_w = $targ_h = 150;
		//quality of the output
			$jpeg_quality = 90;
		//create a cropped copy of the image
			$img_r = imagecreatefrompng($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );		
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		//save the new cropped version
			imagejpeg($dst_r, "assets/images/profile_pics/uploads/".$finalname.".jpg", 90); 	
						
	}else if($type == 'gif' || $type == 'GIF'){
		
		//the target dimensions 150x150
			$targ_w = $targ_h = 150;
		//quality of the output
			$jpeg_quality = 90;
		//create a cropped copy of the image
			$img_r = imagecreatefromgif($src);
			$dst_r = imagecreatetruecolor( $targ_w, $targ_h );		
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		//save the new cropped version
			imagejpeg($dst_r, "assets/images/profile_pics/uploads".$finalname.".jpg", 90); 	
		
	}
		//free up memory
		imagedestroy($img_r); // free up memory
		imagedestroy($dst_r); //free up memory
		@unlink($src); // delete the original upload					
		
		//Insert image into database
		$user->updateProfilePic("uploads/".$finalname.".jpg");

		header("Location: profile.php?user=".$user->getUsername());
														
}// post x
?>

<script type="text/javascript" src="vendor/jcrop/jquery.Jcrop.js"></script>
<script type="text/javascript" src="vendor/jcrop/jcrop_bits.js"></script>

<link rel="stylesheet" href="vendor/jcrop/jquery.Jcrop.css" type="text/css" />
<!-- <link rel="stylesheet" href="assets/css/login.css" type="text/css" /> -->
<link rel="stylesheet" href="assets/css/upload.css" type="text/css" />

<main>

<div id="Overlay" style=" width:100%; height:100%; border:0px #990000 solid; position:absolute; top:0px; left:0px; display:none;"></div>
<div class="column">


	<div id="formExample">
		
	    <p><b> <?=$msg?> </b></p>
	    
	    <form action="upload.php" method="post"  enctype="multipart/form-data">
	        Upload something<br /><br />
	        <input type="file" id="image" name="image" /><br /><br />
	        <input type="submit" class='rainbowBtn' value="Submit" />
	    </form><br /><br />
	    
	</div> <!-- Form-->  


    <?php
    if($imgSrc){ //if an image has been uploaded display cropping area?>
	    <script>
	    	$('#Overlay').show();
			$('#formExample').hide();
	    </script>
	    <div id="CroppingContainer">  
		
	        <div id="CroppingArea">	
	            <img src="<?=$imgSrc?>" border="0" id="jcrop_target" style="border:0px #990000 solid; position:relative; margin:0px 0px 0px 0px; padding:0px; " />
	        </div>  

			<div class='EngageArea'>
				<div id="InfoArea">	
				<p style="margin:0px; padding:0px; color:#444; font-size:18px; text-align:justify;">          
						<b style="text-align: center;width: 100%;display: inline-block;margin-bottom: 0px;">
							Crop Profile Image
						</b><br /><br />
						<span style="font-size:14px;">
							Crop / resize your uploaded profile image. 
							Once you are happy with your profile image then please click save.
						</span>
				</p>
				</div>  

				<br />

				<div class='buttonsGroup'>
					<div id="CropImageForm">  
						<form action="upload.php" method="post" onsubmit="return checkCoords();">
							<input type="hidden" id="x" name="x" />
							<input type="hidden" id="y" name="y" />
							<input type="hidden" id="w" name="w" />
							<input type="hidden" id="h" name="h" />
							<input type="hidden" value="jpeg" name="type" />
							<input type="hidden" value="<?php echo $src?>" name="src" />
							<input type="submit" class='rainbowBtn' value="Save" style="width:180px; height:35px;"   />
						</form>
					</div>

					<div id="CropImageForm2">  
						<form action="upload.php" method="post" onsubmit="return cancelCrop();">
							<input type="submit" class='rainbowBtn' value="Cancel Crop" style="width:180px; height:35px; margin-top:5px"   />
						</form>
					</div>
				</div>
			</div>
	            
	    </div><!-- CroppingContainer -->
	<?php 
	} ?>
</div>
 
 
 <?php if($result_path) {
	 ?>
     
     <img src="<?=$result_path?>" style="position:relative; margin:10px auto; width:150px; height:150px;" />
	 
 <?php } ?>
 <br /><br />
 </main>