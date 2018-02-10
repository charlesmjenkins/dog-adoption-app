<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: getDogList2.php
//
// Description: Dynamically generates list of
// dogs within given radius from user location.
// ---------------------------------------
?>

<div id='inner'>

<?php
session_start();
include_once "database/php/sqlfunctions.php";

	$dogList = new PetInterface;
	$dogList = $dogList->showPetsList($_SESSION['proximity'], $_SESSION['userLat'], $_SESSION['userLon']);

	$petNumber = 0;
	
	while(!$dogList->isEmpty()){
		echo "<div id=\"pet".$dogList->top()['petId']."\" class='listedPet' onmouseover=\"revealMagIcon('magicon".$dogList->top()['petId']."');\"";
		
		if($dogList->top()['imgRef'] != '')
			echo "style=\"background-image: url('img/thumbs/".$dogList->top()['imgRef']."');\"";
		
		echo "onmouseout=\"hideMagIcon('magicon".$dogList->top()['petId']."');\" >";
	
		echo "<img src='img/magiconwh.png' class='magicon' id='magicon".$dogList->top()['petId']."' 
			onmouseover=\"this.src='img/magiconwhsh.png'\" onmouseout=\"this.src='img/magiconwh.png'\" 
			onclick='showPetBio(".$_SESSION['proximity'].", ".$_SESSION['userLat'].", ".$_SESSION['userLon'].", ".$dogList->top()['petId'].", ".$dogList->top()['shelterId']."); revealBackArrow();' />";
			
		echo "<div class='nameAndTime'><div class='petName' id='petName$petNumber'>".$dogList->top()['petName']."</div>";
		echo " ";
		echo "<div id='clock$petNumber'></div></div>";
		echo "</div>";
		
		echo "<script type=\"text/javascript\"> 
			var timeLeft = new Date().getTime() + ".$dogList->top()['timeLeft'] * 1000;
		echo ";\n$('#clock$petNumber').countdown(timeLeft, {elapse: true})
			 .on('update.countdown', function(event) {
			   var \$this = $(this);
			   if (event.elapsed) {
			     \$this.html(event.strftime('Time Has Expired!'));
			   } else {
			     \$this.html(event.strftime('Time Left: <span>%w Weeks %d Days %H:%M:%S</span>'));
			   }
			 });
		</script>";
		
		$dogList->pop();
		
		$petNumber = $petNumber + 1;
	}
?>
</div> <!-- inner -->