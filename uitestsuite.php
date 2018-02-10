<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: uitestsuite.php
//
// Description: Unit tests for various UI
// functionality.
// ---------------------------------------
session_start();

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
    else
        return 0;

    imagejpeg($imageTmp, $outputImage, $quality);
    imagedestroy($imageTmp);

    return 1;
}

// Test that png image converts successfully
function phpUnitTest1(){
    $OK = false;
    
    convertImage("img/test/t1.png", "img/test/t1.jpg", 100);
    
    if(file_exists("img/test/t1.jpg"))
        $OK = true;
        
    unlink("img/test/t1.jpg");
        
    return $OK;
}

// Test that gif image converts successfully
function phpUnitTest2(){
    $OK = false;
    
    convertImage("img/test/t2.GIF", "img/test/t2.jpg", 100);
    
    if(file_exists("img/test/t2.jpg"))
        $OK = true;
        
    unlink("img/test/t2.jpg");
        
    return $OK;
}

// Test that jpg image "converts" successfully
function phpUnitTest3(){
    $OK = false;
    
    convertImage("img/test/t3.jpg", "img/test/t3new.jpg", 100);
    
    if(file_exists("img/test/t3new.jpg"))
        $OK = true;
        
    unlink("img/test/t3new.jpg");
        
    return $OK;
}

// ---------------------------------------
// Name: convertToSQLDatetime
//
// Description: Tests datetime string adjustment
//
// Receives: Nothing
//
// Returns: Nothing
// ---------------------------------------
function convertToSQLDatetime($dateinput){
    return str_replace("T", " ", $dateinput).":00";
}

// Test that format of returned datetime is SQL-ready
function phpUnitTest4(){
    $OK = false;
    
    convertToSQLDatetime("2023-03-03T03:33");
    
    if(convertToSQLDatetime("2023-03-03T03:33") == "2023-03-03 03:33:00")
        $OK = true;
        
    return $OK;
}

// ---------------------------------------
// Name: validateShelter
//
// Description: Sets shelter session data
//
// Receives: shelterID, shelterName
//
// Returns: Nothing
//
// Acknowledgement: http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/
// ---------------------------------------
function validateShelter($shelterID, $shelterName)
{
    $_SESSION['valid'] = 1;
	$_SESSION['shelterID'] = $shelterID;
	$_SESSION['shelterName'] = $shelterName;
}

// Test that session is generated properly with correct values
function phpUnitTest5(){
    $OK = false;
    
    validateShelter(100, "Test");
    
    if(isset($_SESSION['valid']) && isset($_SESSION['shelterID']) && isset($_SESSION['shelterName']) 
        && $_SESSION['valid'] == 1 && $_SESSION['shelterID'] == 100 && $_SESSION['shelterName'] == "Test"){
        $OK = true;
    }
        
    return $OK;
}

// ---------------------------------------
// Name: isLoggedIn
//
// Description: Validates if user/shelter is logged in
//
// Receives: Nothing
//
// Returns: Nothing
//
// Acknowledgement: http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/
// ---------------------------------------
function isLoggedIn()
{
	if(isset($_SESSION['valid']) && $_SESSION['valid'])
		return true;
	else
		return false;
}

// Test that login check reads session values properly
function phpUnitTest6(){
    $OK = false;
    
    if(isLoggedIn()){
        $OK = true;
    }
        
    return $OK;
}

// ---------------------------------------
// Name: logout
//
// Description: Destroys session data and redirects
//				to startup page
//
// Receives: Nothing
//
// Returns: Nothing
//
// Acknowledgement: http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/
// ---------------------------------------
function logout()
{
	$_SESSION = array(); //destroy all of the session variables
	session_destroy();
}

// Test that session is destroyed properly
function phpUnitTest7(){
    $OK = false;
    
    logout();
    
    if(!isset($_SESSION['valid'])){
        $OK = true;
    }
        
    return $OK;
}

// ---------------------------------------
// Name: cropAndResizeImage
//
// Description: Crops and resizes image for
// thumbnail and bio picture.
//
// Receives: original image path, output image path
//           desired quality of output file
//
// Returns: jpeg version of original image
//
// Acknowledgement: http://stackoverflow.com/questions/27367083/uploaded-image-doesnt-get-stored-in-the-uploads-folder-using-php-code
// ---------------------------------------
function cropAndResizeImage($myfilename){
	// Crop and resize the image to generate a thumbnail
	// Code courtesy of: http://solvedstack.com/questions/crop-image-in-php
	$image = imagecreatefromjpeg("img/cnv/".$myfilename);
	$filename = "img/thumbs/".$myfilename;
	
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
	
	list($width_orig, $height_orig) = getimagesize("img/cnv/".$myfilename);
	
	$ratio_orig = $width_orig/$height_orig;
	$height = $width/$ratio_orig;
	
	// Resample
	$image_p = imagecreatetruecolor($width, $height);
	$image = imagecreatefromjpeg("img/cnv/".$myfilename);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	
	// Output
	imagejpeg($image_p, "img/rsz/".$myfilename, 100);
	
	// Delete temporary image files
	imagedestroy($image);
}

// Test that image successfully is resized, cropped and saved
function phpUnitTest8(){
    $OK = false;
    
    cropAndResizeImage("t3.jpg");
    
    if(file_exists("img/rsz/t3.jpg") && file_exists("img/thumbs/t3.jpg")){
        $OK = true;
    }
    
    unlink("img/rsz/t3.jpg");
    unlink("img/thumbs/t3.jpg");
        
    return $OK;
}

// Test that image is resized/cropped to correct dimensions
function phpUnitTest9(){
    $OK = false;
    
    cropAndResizeImage("t3.jpg");
    
    list($w1, $h1) = getimagesize("img/rsz/t3.jpg");
    list($w2, $h2) = getimagesize("img/thumbs/t3.jpg");
    
    if($w1 == 378 && $w2 == 378 && $h2 == 150){
        $OK = true;
    }
    
    unlink("img/rsz/t3.jpg");
    unlink("img/thumbs/t3.jpg");
        
    return $OK;
}

?>

<script type="text/javascript">
// ---------------------------------------
// Name: revealAddListing
//
// Description: Sets addListing div to visible
//
// Receives: Nothing
//
// Returns: Nothing
// ---------------------------------------
function revealAddListing(){
	document.getElementById("addListing").style.visibility = "visible";
}

// Test that addListing div is made visible properly
function jsUnitTest1(){
    revealAddListing();
    
    return document.getElementById("addListing").style.visibility == "visible";
}

// ---------------------------------------
// Name: hideAddListing
//
// Description: Sets addListing div to hidden
//
// Receives: Nothing
//
// Returns: Nothing
// ---------------------------------------
function hideAddListing(){
	document.getElementById("addListing").style.visibility = "hidden";
}

// Test that addListing div is hidden properly
function jsUnitTest2(){
    hideAddListing();
    
    return document.getElementById("addListing").style.visibility == "hidden";
}

// ---------------------------------------
// Name: showPetBio
//
// Description: AJAX function for generating pet bio
//
// Receives: petId, shelterId
//
// Returns: Nothing
//
// Acknowledgement: http://www.w3schools.com/ajax/ajax_xmlhttprequest_create.asp
// ---------------------------------------
function showPetBio(petId, shelterId) {
	if (petId=="") {
		document.getElementById("shelterContents").innerHTML="";
		return;
	} 
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else { // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("shelterContents").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", "testbio.php?petId="+petId+"&shelterId="+shelterId, true);
	xmlhttp.send();
}

// Test that AJAX populates div innerHTML properly
function jsUnitTest3(){
    showPetBio(1, 9);
    
    var x = document.getElementById("shelterContents").innerHTML;
    
    return x == 19;
}

// Run all JavaScript tests
function runTests(){
    if(jsUnitTest1())
        document.getElementById('j1').value = "jsUnitTest1 = OK";
    else
        document.getElementById('j1').value = "jsUnitTest1 = FAILED";
    
    if(jsUnitTest2())
        document.getElementById('j2').value = "jsUnitTest2 = OK";
    else
        document.getElementById('j2').value = "jsUnitTest2 = FAILED";
    
    jsUnitTest3();
}
    
</script>

<html>
    <head></head>
    <body>
        <?php
            // Run all PHP tests
            if(phpUnitTest1())
                echo "phpUnitTest1 = OK<br />";
            else
                echo "phpUnitTest1 = FAILED<br />";
            
            if(phpUnitTest2())
                echo "phpUnitTest2 = OK<br />";
            else
                echo "phpUnitTest2 = FAILED<br />";
            
            if(phpUnitTest3())
                echo "phpUnitTest3 = OK<br />";
            else
                echo "phpUnitTest3 = FAILED<br />";
            
            if(phpUnitTest4())
                echo "phpUnitTest4 = OK<br />";
            else
                echo "phpUnitTest4 = FAILED<br />";
            
            if(phpUnitTest5())
                echo "phpUnitTest5 = OK<br />";
            else
                echo "phpUnitTest5 = FAILED<br />";
            
            if(phpUnitTest6())
                echo "phpUnitTest6 = OK<br />";
            else
                echo "phpUnitTest6 = FAILED<br />";
            
            if(phpUnitTest7())
                echo "phpUnitTest7 = OK<br />";
            else
                echo "phpUnitTest7 = FAILED<br />";
            
            if(phpUnitTest8())
                echo "phpUnitTest8 = OK<br />";
            else
                echo "phpUnitTest8 = FAILED<br />";
            
            if(phpUnitTest9())
                echo "phpUnitTest9 = OK<br />";
            else
                echo "phpUnitTest9 = FAILED<br />";
        ?>
        
        <button type="button" onclick="runTests();" >Run JavaScript Tests</button>
        <br />
        <input type="text" value="" id="j1" />
        <br />
        <input type="text" value="" id="j2" />
        <br />
        If '19' appears below after pressing the above button, jsUnitTest3 works.
        
        <div id="addListing" visibility="hidden" ></div>
        <form id="editForm" action=""></form>
        <div id="shelterContents" ></div>
        
    </body>
</html>


