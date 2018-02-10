<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: session2.php
//
// Description: Handles session creation for
// users.
// ---------------------------------------

	include "utilities.php";
	include_once "database/php/sqlfunctions.php";

	session_start();

	validateUser($_GET['radius'], $_GET['lat'], $_GET['lng']); // Sets the session data for this user
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=user.php">';
?>