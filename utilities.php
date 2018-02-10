<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: utilities.php
//
// Description: Miscellaneous utility functions.
// ---------------------------------------

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
		session_regenerate_id (); //this is a security measure

		$_SESSION['valid'] = 1;
		$_SESSION['shelterID'] = $shelterID;
		$_SESSION['shelterName'] = $shelterName;
	}
	
	// ---------------------------------------
	// Name: validateUser
	//
	// Description: Sets user session data
	//
	// Receives: proximity, latitude, longitude
	//
	// Returns: Nothing
	//
	// Acknowledgement: http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/
	// ---------------------------------------
	function validateUser($proximity, $userLat, $userLon)
	{
		session_regenerate_id (); //this is a security measure

		$_SESSION['proximity'] = $proximity;
		$_SESSION['userLat'] = $userLat;
		$_SESSION['userLon'] = $userLon;
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
		
		header('Location: startup.php');
	}
?>