<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: user.php
//
// Description: Search results of dog listings
// based on location search query.
// ---------------------------------------
 	session_start();

 	include_once "database/php/sqlfunctions.php";
 	include "utilities.php";

	$proximity = $_SESSION['proximity'];
    $userLat = $_SESSION['userLat'];
    $userLon = $_SESSION['userLon'];
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>Dog Search</title>
		
		<link rel="stylesheet" type="text/css" href="css/shelterstyle.css">
		
		<script src="js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/jquery.kinetic.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/jquery.countdown.min.js"></script>

		<script type="text/javascript">
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
			function showPetBio(proximity, userLat, userLon, petId, shelterId) {
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
				xmlhttp.open("GET", "getbio2.php?proximity="+proximity+"&userLat="+userLat+"&userLon="+userLon+"&petId="+petId+"&shelterId="+shelterId, true);
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
				xmlhttp.open("GET", "getDogList2.php", true);
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
			    window.location = "user.php";
			}
		</script>

	</head>

	<body>
		<div id="content">
			<div class="centered">
				<div id="titleBar">
					<h6 id="statusTitle"><?php echo $shelterName; ?></h6>
					<form action="logout.php"><input id="logoutButton" type="submit" value="Log Out" ></form>
				</div>
				
					<div id="shelterContents" onclick="hideAddListing();">
						<?php include("getDogList2.php"); ?>
					</div> <!-- shelterContents -->
	
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