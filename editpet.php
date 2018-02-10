<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: editpet.php
//
// Description: Backend code for uploading
// new end time into database.
// ---------------------------------------

	session_start();
	include_once "database/php/sqlfunctions.php";
	
	//Update pet's time to live
	$dogList = new PetInterface;
	$dogList = $dogList->updateKillDate($_GET["petId"], str_replace("T", " ", $_POST["newPetEndDate"]).":00");
	
	//Reload pet list
	header('Location: shelter.php');
?>