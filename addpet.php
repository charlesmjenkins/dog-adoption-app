<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: addpet.php
//
// Description: Backend code for uploading
// images and inserting listings into database.
// ---------------------------------------

	session_start();
	include_once "database/php/sqlfunctions.php";
	
	// ---------------------------------------
	// Name: convertImage
	//
	// Description: Converts a given image to jpeg
	//
	// Receives: original image path, output image path
	//           desired quality of output file
	//
	// Returns: jpeg version of original image
	//
	// Acknowledgement: http://stackoverflow.com/questions/14549446/how-can-i-convert-all-images-to-jpg
	// ---------------------------------------
	function convertImage($originalImage, $outputImage, $quality)
	{
	    $exploded = explode('.',$originalImage);
	    $ext = $exploded[count($exploded) - 1];
	
	    if (preg_match('/jpg|jpeg/i',$ext))
	        $imageTmp=imagecreatefromjpeg($originalImage);
	    else if (preg_match('/png/i',$ext))
	        $imageTmp=imagecreatefrompng($originalImage);
	    else if (preg_match('/gif/i',$ext))
	        $imageTmp=imagecreatefromgif($originalImage);
	    else if (preg_match('/bmp/i',$ext))
	        $imageTmp=imagecreatefrombmp($originalImage);
	    else
	        return 0;
	
	    imagejpeg($imageTmp, $outputImage, $quality);
	    imagedestroy($imageTmp);
	
	    return 1;
	}
	
	// Image upload error checking code courtesy of: http://stackoverflow.com/questions/27367083/uploaded-image-doesnt-get-stored-in-the-uploads-folder-using-php-code
	// Check chosen file for errors
	$target_dir = "img/tmp/";
	$target_file = $target_dir . basename($_FILES["newPetImage"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	    $check = getimagesize($_FILES["newPetImage"]["tmp_name"]);
	    if($check !== false) {
	        echo "File is an image - " . $check["mime"] . ".";
	        $uploadOk = 1;
	    } else {
	        echo "File is not an image.";
	        $uploadOk = 0;
	    }
	}
	// Check if file already exists
	if (file_exists($target_file)) {
	    echo "Sorry, file already exists.";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["newPetImage"]["size"] > 50000000) {
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["newPetImage"]["tmp_name"], $target_file)) {
	        //echo "The file ". basename( $_FILES["newPetImage"]["name"]). " has been uploaded.";
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
	
	// Convert image to jpeg for consistency throughout application
	$successfullyConverted = convertImage("img/tmp/".$_FILES["newPetImage"]["name"], "img/cnv/".$_FILES["newPetImage"]["name"], 100);
	
	// Crop and resize the image to generate a thumbnail
	// Code courtesy of: http://solvedstack.com/questions/crop-image-in-php
	$image = imagecreatefromjpeg("img/cnv/".$_FILES["newPetImage"]["name"]);
	$filename = "img/thumbs/".$_FILES["newPetImage"]["name"];
	
	$thumb_width = 378;
	$thumb_height = 150;
	
	$width = imagesx($image);
	$height = imagesy($image);
	
	$original_aspect = $width / $height;
	$thumb_aspect = $thumb_width / $thumb_height;
	
	if ($original_aspect >= $thumb_aspect)
	{
	  // If image is wider than thumbnail (in aspect ratio sense)
	  $new_height = $thumb_height;
	  $new_width = $width / ($height / $thumb_height);
	}
	else
	{
	  // If the thumbnail is wider than the image
	  $new_width = $thumb_width;
	  $new_height = $height / ($width / $thumb_width);
	}
	
	$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
	
	// Resize and crop
	imagecopyresampled($thumb,
	                  $image,
	                  0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
	                  0 - ($new_height - $thumb_height) / 2, // Center the image vertically
	                  0, 0,
	                  $new_width, $new_height,
	                  $width, $height);
	imagejpeg($thumb, $filename, 100);
	
	// Save a resized (but not cropped) copy of the image for bio
	// Code courtesy of: http://syframework.alwaysdata.net/imagecopyresampled
	$width = 378; // Desired width
	
	list($width_orig, $height_orig) = getimagesize("img/cnv/".$_FILES["newPetImage"]["name"]);
	
	$ratio_orig = $width_orig/$height_orig;
	$height = $width/$ratio_orig;
	
	// Resample
	$image_p = imagecreatetruecolor($width, $height);
	$image = imagecreatefromjpeg("img/cnv/".$_FILES["newPetImage"]["name"]);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	
	// Output
	imagejpeg($image_p, "img/rsz/".$_FILES["newPetImage"]["name"], 100);
	
	// Delete temporary image files
	imagedestroy($image);
	unlink("img/tmp/".$_FILES["newPetImage"]["name"]);
	unlink("img/cnv/".$_FILES["newPetImage"]["name"]);
	
	//Add to the database
	$dogList = new PetInterface;
	$latestId = $dogList->add($_SESSION["shelterID"], $_POST["newPetName"], $_POST["newPetBio"], str_replace("T", " ", $_POST["newPetEndDate"]).":00");
	$dogList = $dogList->addMedia($latestId, $_SESSION["shelterID"], $_FILES["newPetImage"]["name"], $_POST["newPetName"]);
	
	//Reload pet list
	header('Location: shelter.php');
?>