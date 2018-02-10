<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: session.php
//
// Description: Handles session creation for
// shelters.
// ---------------------------------------

	include "utilities.php";
	include_once "database/php/sqlfunctions.php";

	session_start();
	
	$shelter = new ShelterInterface;
	$shelterEmail = $_POST['shelterEmail'];
	$shelterName = $shelter->lookupEmailForName($shelterEmail);
	$shelterID = $shelter->lookupEmail($shelterEmail);

	if($shelterID == NULL) // No such user exists
	{
		echo "<script type='text/javascript'>alert('No account exists with that email.')</script>";
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=startup.php">';
	}
	else
	{
		validateShelter($shelterID, $shelterName); // Sets the session data for this user
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=shelter.php">'; 
	}
	
	if(isset($_SESSION['shelterID']))
	{
		header('Location: shelter.php');
		die();
	}
?>