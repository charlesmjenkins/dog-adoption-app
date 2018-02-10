<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: getbio.php
//
// Description: Dynamically generates dog's
// bio within shelter interface based on selected
// listing.
// ---------------------------------------
?>

<div id='inner'>

<?php
session_start();
include_once "database/php/sqlfunctions.php";

	$dogList = new PetInterface;
	$dogList = $dogList->showSpecificPetAtShelter($_SESSION["shelterID"], $_GET["petId"]);

		echo "<div id=\"pet".$dogList->top()['petId']."\" class='bioPet'";
		
		list($width, $height) = getimagesize("img/rsz/".$dogList->top()['imgRef']);
		
		if($dogList->top()['imgRef'] != '')
			echo "style=\"background-image: url('img/rsz/".$dogList->top()['imgRef']."'); height: ".$height."px; \"";
		
		echo "onmouseout=\"hideMagIcon('magicon".$dogList->top()['petId']."');\" >";
			
		echo "<div class='bioInfo'><div class='petName' id='petName$petNumber'>".$dogList->top()['petName']."</div>";
		echo "<div class='petBio' id='petBio'>".$dogList->top()['petBio']."</div><br />";
		echo "<div class='endDate' id='endDate'>End Date: ".$dogList->top()['endDate']."</div>";

		echo "<img src='img/addtimewh.png'
			onmouseover=\"this.src='img/addtimewhsh.png'\" onmouseout=\"this.src='img/addtimewh.png'\" onclick=\"revealEditListing(); assignPetIdToEditFormAction(".$_GET["petId"].");\"
			onclick='showPetBio(".$dogList->top()['petId'].")' />";
?>
</div> <!-- inner -->