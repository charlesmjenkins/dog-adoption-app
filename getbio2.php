<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: getbio2.php
//
// Description: Dynamically generates dog's
// bio within user interface based on selected
// listing.
// ---------------------------------------
?>

<div id='inner'>

<?php
session_start();
include_once "database/php/sqlfunctions.php";

	$dogList = new PetInterface;
	$dogList = $dogList->showSpecificPetAtShelter($_GET["shelterId"], $_GET["petId"]);
	
	$shelter = new ShelterInterface;
	$shelter = $shelter->lookupIDForNameAndEmail($_GET["shelterId"]);

		echo "<div id=\"pet".$dogList->top()['petId']."\" class='bioPet'";
		
		list($width, $height) = getimagesize("img/rsz/".$dogList->top()['imgRef']);
		
		if($dogList->top()['imgRef'] != '')
			echo "style=\"background-image: url('img/rsz/".$dogList->top()['imgRef']."'); height: ".$height."px; \"";
			
		echo "onmouseout=\"hideMagIcon('magicon".$dogList->top()['petId']."');\" >";
			
		echo "<div class='bioInfo'><div class='petName' id='petName$petNumber'>".$dogList->top()['petName']."</div>";
		echo "<div class='petBio' id='petBio'>".$dogList->top()['petBio']."</div><br />";
		echo "<div class='endDate' id='endDate'>End Date: ".$dogList->top()['endDate']."</div><br />";
		echo "<div class='shName' id='shName'>".$shelter->top()['shelterName']."</div>";
		echo "<div class='shEmail' id='shEmail'><a href=\"mailto:\"".$shelter->top()['shelterEmail']."\">".$shelter->top()['shelterEmail']."</a></div>";
?>
</div> <!-- inner -->