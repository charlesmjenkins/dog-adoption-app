<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: shelter.php
//
// Description: Admin features for shelters
// to upload and manage listings and
// view/edit listing time left.
// ---------------------------------------
 	session_start();

 	include_once "database/php/sqlfunctions.php";
 	include "utilities.php";

	if(!isLoggedIn())
	{
		header('Location: startup.php');
		die();
	}

	$shelterID = $_SESSION['shelterID'];
 	$shelterName = $_SESSION['shelterName'];
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>Shelter Admin</title>
		<link rel="stylesheet" type="text/css" href="css/shelterstyle.css">
		
		<script src="js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script><!--https://jquery.com/-->
        <script src="js/jquery.kinetic.min.js" type="text/javascript" charset="utf-8"></script><!--https://github.com/davetayls/jquery.kinetic-->
        <script src="js/jquery.countdown.min.js"></script><!--http://hilios.github.io/jQuery.countdown/-->

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
			
			// ---------------------------------------
			// Name: revealEditListing
			//
			// Description: Sets editListing div to visible
			//
			// Receives: Nothing
			//
			// Returns: Nothing
			// ---------------------------------------
			function revealEditListing(){
				document.getElementById("editListing").style.visibility = "visible";
			}
			
			// ---------------------------------------
			// Name: hideEditListing
			//
			// Description: Sets editListing div to hidden
			//
			// Receives: Nothing
			//
			// Returns: Nothing
			// ---------------------------------------
			function hideEditListing(){
				document.getElementById("editListing").style.visibility = "hidden";
			}
			
			// ---------------------------------------
			// Name: revealMagIcon
			//
			// Description: Sets magnifiying glass image to visible
			//
			// Receives: magIconId
			//
			// Returns: Nothing
			// ---------------------------------------
			function revealMagIcon(magIconId){
				document.getElementById(magIconId).style.visibility = "visible";
			}
			
			// ---------------------------------------
			// Name: hideMagIcon
			//
			// Description: Sets magnifiying glass image to hidden
			//
			// Receives: magIconId
			//
			// Returns: Nothing
			// ---------------------------------------
			function hideMagIcon(magIconId){
				document.getElementById(magIconId).style.visibility = "hidden";
			}
			
			// ---------------------------------------
			// Name: revealBackArrow
			//
			// Description: Sets back arrow image to visible
			//
			// Receives: Nothing
			//
			// Returns: Nothing
			// ---------------------------------------
			function revealBackArrow(){
				document.getElementById("backarrow").style.visibility = "visible";
			}
			
			// ---------------------------------------
			// Name: hideBackArrow
			//
			// Description: Sets back arrow image to hidden
			//
			// Receives: Nothing
			//
			// Returns: Nothing
			// ---------------------------------------
			function hideBackArrow(){
				document.getElementById("backarrow").style.visibility = "hidden";
			}
			
			// ---------------------------------------
			// Name: assignPetIdToEditFormAction
			//
			// Description: Gives each listing a unique div id
			//
			// Receives: petId
			//
			// Returns: Nothing
			// ---------------------------------------
			function assignPetIdToEditFormAction(petId){
				document.getElementById("editForm").action = "editpet.php?petId="+petId;
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
				xmlhttp.open("GET", "getbio.php?petId="+petId+"&shelterId="+shelterId, true);
				xmlhttp.send();
			}
			
			// ---------------------------------------
			// Name: getDogList
			//
			// Description: AJAX function for generating pet listings
			//
			// Receives: Nothing
			//
			// Returns: Nothing
			//
			// Acknowledgement: http://www.w3schools.com/ajax/ajax_xmlhttprequest_create.asp
			// ---------------------------------------
			function getDogList() {
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
				xmlhttp.open("GET", "getDogList.php", true);
				xmlhttp.send();
			}
			
			// ---------------------------------------
			// Name: returnToDogList
			//
			// Description: Refreshes shelter.php to 
			//              regenerate timers
			//
			// Receives: Nothing
			//
			// Returns: Nothing
			// ---------------------------------------
			function returnToDogList(){
			    window.location = "shelter.php";
			}
		</script>
	</head>

	<body>
		<div id="content">
			<div class="centered">
				
				<div id="titleBar">
					<h6 id="statusTitle"><?php echo $shelterName; ?></h6>
					<input id="addListingButton" type="button" value="+" onclick="revealAddListing();">
					<form action="logout.php"><input id="logoutButton" type="submit" value="Log Out" ></form>
				</div>
				
					<div id="shelterContents" onclick="hideAddListing();">
						<?php include("getDogList.php"); ?>
					</div> <!-- shelterContents -->
					
				<div id="addListing">
				<form action="addpet.php" class="menuBtn" method="post" enctype="multipart/form-data" id="addForm">
					<input type="file" name="newPetImage" id="newPetImage">
					<br />
					<input type="text" name="newPetName" id="newPetName" placeholder="Name" maxlength="30"  />
				    <br />
				    <textarea rows="4" cols="50" name="newPetBio" id="newPetBio" placeholder="Bio" maxlength="255"></textarea>
				    <br />
				    <label for="newPetEndDate">End Date: </label>
				    <input type="datetime-local" name="newPetEndDate" id="newPetEndDate" placeholder="End Time"  />
				    <br />
					<input type="button" value="Cancel" onclick="hideAddListing();"/>
					<input type="submit" value="Add Listing" />
				</form>
				</div>
				
				<div id="editListing">
				<form action="editpet.php?petId=" class="menuBtn" method="post" enctype="multipart/form-data" id="editForm">
				    <label for="newPetEndDate">Update End Date: </label>
				    <input type="datetime-local" name="newPetEndDate" id="newPetEndDate" placeholder="End Time"  />
				    <br />
					<input type="button" value="Cancel" onclick="hideEditListing();"/>
					<input type="submit" value="Update Listing" />
				</form>
				</div> 
				
			</div> <!-- centered -->
			
			<img id="backarrow" src='img/backarrwh.png' onclick='returnToDogList(); hideBackArrow();' onmouseover="this.src='img/backarrwhsh.png'" onmouseout="this.src='img/backarrwh.png'" />
		
		</div> <!-- content -->
	</body>
</html>		

<script type="text/javascript">
	// Set properties for click-drag functionality
	$('#shelterContents').kinetic({
		x: false,
		cursor: "pointer"
	});
</script>