<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: logout.php
//
// Description: Calls logout function to
// clear session data.
// ---------------------------------------

	// Acknowledgement: http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/

	include "utilities.php";
	
	session_start(); 
	
	// If the user has not logged in
	if(!isLoggedIn())
	{
		header('Location: startup.php');
		die();
	}
	
	logout();
?>