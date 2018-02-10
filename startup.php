<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: startup.php
//
// Description: Opening app page to allow 
// user to either search for dogs based on
// location, or log in as a shelter.
// ---------------------------------------
 	session_start();

 	include_once "database/php/sqlfunctions.php";
 	include "utilities.php";

	// If shelter is already login, redirect to shelter.php
	if(isLoggedIn())
	{
		header('Location: shelter.php');
		die();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Login</title>
		
		<link rel="stylesheet" type="text/css" href="css/shelterstyle.css">
		
		<script src="js/jquery-2.1.0.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/jquery.kinetic.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/jquery.countdown.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=">//Insert API key as "key" argument</script>

		<script type="text/javascript">
			// ---------------------------------------
			// Name: revealUserLogin
			//
			// Description: Sets userLogin div to visible
			//
			// Receives: Nothing
			//
			// Returns: Nothing
			// ---------------------------------------
			function revealUserLogin(){
				document.getElementById("userLogin").style.visibility = "visible";
			}
			
			// ---------------------------------------
			// Name: hideUserLogin
			//
			// Description: Sets userLogin div to hidden
			//
			// Receives: Nothing
			//
			// Returns: Nothing
			// ---------------------------------------
			function hideUserLogin(){
				document.getElementById("userLogin").style.visibility = "hidden";
			}
			
			// ---------------------------------------
			// Name: revealShelterLogin
			//
			// Description: Sets shelterLogin div to visible
			//
			// Receives: Nothing
			//
			// Returns: Nothing
			// ---------------------------------------
			function revealShelterLogin(){
				document.getElementById("shelterLogin").style.visibility = "visible";
			}
			
			// ---------------------------------------
			// Name: hideShelterLogin
			//
			// Description: Sets shelterLogin div to hidden
			//
			// Receives: Nothing
			//
			// Returns: Nothing
			// ---------------------------------------
			function hideShelterLogin(){
				document.getElementById("shelterLogin").style.visibility = "hidden";
			}
		</script>
	</head>

	<body>
		<div id="content">
			<div class="centered">
				<div id="titleBar">
					<h6 id="statusTitle">Dog Adoption App: Login</h6>
				</div>
				
				<input type="button" value="I am a user browsing for dogs." onclick="revealUserLogin();" id="userLoginBtn"/>
				
				<input type="button" value="I am a shelter managing my dogs." onclick="revealShelterLogin();" id="shelterLoginBtn"/>
				
				<img src="img/doglogo.png" id="doglogo" />
				
				<div id="userLogin">
					<form action="session2.php" class="menuBtn" method="get" id="userLoginForm" >
						<label for="radius">Search for dogs within </label>
						<select name="radius" id="radius" placeholder="Radius" maxlength="30">
						    <option value="5">5 Miles</option>
	        				<option value="10">10 Miles</option>
	        				<option value="15">15 Miles</option>
	        				<option value="30">30 Miles</option>
	        				<option value="60">60 Miles</option> 
	        			</select>
		        		<label for="address">of </label>
						<input type="text" name="locName" id="locName" placeholder="Address"  />
						<br />
						<input type="button" value="Pinpoint Location" onclick="converter()" />
						<input type="button" value="Test Location" onclick="testLocation()" />
						<br />
						<input class="statusTxt" type="text" value="" id='lat' name='lat'  />
						<input class="statusTxt" type="text" value="" id='lng' name='lng'  />
						<input class="statusTxt" type="text" value="" id='city' name='city'  />
						<input class="statusTxt" type="text" value="" id='state' name='state'  />
						<br />
						<input type="button" value="Cancel" onclick="hideUserLogin()" />
						<input id="searchBtn" type="submit" value="Search"  />
					</form>
				</div>
				
				<div id="shelterLogin">
					<form action="session.php" class="menuBtn" method="post" id="shelterLoginForm">
						<input type="text" name="shelterEmail" id="shelterEmail" placeholder="Email" maxlength="30"  />
						<input type="submit" value="Log In" />
						<input type="button" value="Cancel" onclick="hideShelterLogin()" />
					</form>
				</div>
	</body>
</html>		

<script type="text/javascript">
	// Set properties for click-drag functionality
	$('#shelterContents').kinetic({
		x: false,
		cursor: "pointer"
	});
	
    var coords = new google.maps.Geocoder();
      
    // ---------------------------------------
	// Name: converter
	//
	// Description: Use Google Maps API to locate
	//              an address and return its
	//              components data.
	//
	// Receives: Nothing
	//
	// Returns: Nothing
	//
	// Acknowledgement: https://developers.google.com/maps/documentation/geocoding/
	// ---------------------------------------
    function converter(){
        var location = document.getElementById('locName').value;
		var e = document.getElementById("radius");
		var radius = e.value;
		var locationType = document.getElementById('locName').value;
		var lat1, lat2, lng1, lng2;
		
        coords.geocode({'address': location},
        	function(results, status) {
	            if (status == google.maps.GeocoderStatus.OK){
					document.getElementById('lat').value = results[0].geometry.location.lat();
					lat1 = results[0].geometry.location.lat();
					document.getElementById('lng').value = results[0].geometry.location.lng();
					lng1 = results[0].geometry.location.lng();
					document.getElementById('city').value = results[0].address_components[2].long_name;
					document.getElementById('state').value = results[0].address_components[5].long_name;
	            }
			
			// Function to define toRad
			if (typeof(Number.prototype.toRad) === "undefined") {
				Number.prototype.toRad = function() {
				return this * Math.PI / 180;
				}
			}
			 
			// Find the distance between two points on the map
			var R = 6371000; // meters
			var pie1 = lat1.toRad();
			var pie2 = lat2.toRad();
			var tripie = (lat2-lat1).toRad();
			var trilam = (lng2-lng1).toRad();
			
			var a = Math.sin(tripie/2) * Math.sin(tripie/2) +
					Math.cos(pie1) * Math.cos(pie2) *
					Math.sin(trilam/2) * Math.sin(trilam/2);
			var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

			var d = R * c;
          });
      }
      
    // ---------------------------------------
	// Name: testLocation
	//
	// Description: Sets address to known test
	//              case and runs converter()
	//
	// Receives: Nothing
	//
	// Returns: Nothing
	// ---------------------------------------
	function testLocation(){
		document.getElementById('locName').value = "31780 Ridgeside Drive, Farmington Hills";
		converter();
	}
</script>