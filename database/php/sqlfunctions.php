<?php
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: sqlfunctions.php
//
// Description: SQL interfaces and query code.
// ---------------------------------------

/*************************************************
* Provides connection function to the database
* - Private from dev and not used directly
* 
*************************************************/
class DatabaseConnection {
    protected $mysqli;
    
    //Insert your database credentials in the mysqli object below
    protected function connect() {
        $this->mysqli = new mysqli('localhost', '', '','');
         if (mysqli_connect_errno($mysqli)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }
}


/*************************************************
* Shelter Object
* - Functions:
*   -add(shelterName, shelterEmail, latitude, longitude)
*       -adds shelter to shelter table
*   -lookupEmail($shelterEmail)
*       -returns shelterID or NULL if doesnt exist
*   -lookupEmailForName($shelterEmail)
*       -returns shelterName or NULL if doesnt exist
* 
*************************************************/
class ShelterInterface extends DatabaseConnection {
    
    public function add($shelterName, $shelterEmail, $latitude, $longitude) {
        
        $this->connect();
        
        $shelterName    = $this->mysqli->real_escape_string($shelterName);
        $shelterEmail   = $this->mysqli->real_escape_string($shelterEmail);
        $latitude       = $this->mysqli->real_escape_string($latitude);
        $longitude      = $this->mysqli->real_escape_string($longitude);
        
        if (!($stmt = $this->mysqli->prepare("INSERT INTO Shelters(ShelterName, ShelterEmail, Lat, Lng) VALUES(?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("ssdd", $shelterName, $shelterEmail, $latitude, $longitude)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->close();
        $this->mysqli->close();
    }
    
    public function lookupEmail($shelterEmail) {
        
        $this->connect();
        
        $shelterEmail = $this->mysqli->real_escape_string($shelterEmail);
        
        if (!($stmt = $this->mysqli->prepare("SELECT ShelterID FROM Shelters WHERE ShelterEmail = ?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("s", $shelterEmail)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        $shelterID  = NULL;
        if (!$stmt->bind_result($shelterID)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        //linked list for result
        while ($stmt->fetch()) {
            $id = $shelterID;
        }
        $stmt->close();
        $this->mysqli->close();
        return $id;
    }
    
    //Added this copy of the above function to get name (rather than ID) of shelter
    public function lookupEmailForName($shelterEmail) {
        
        $this->connect();
        
        $shelterEmail = $this->mysqli->real_escape_string($shelterEmail);
        
        if (!($stmt = $this->mysqli->prepare("SELECT ShelterName FROM Shelters WHERE ShelterEmail = ?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("s", $shelterEmail)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        $shelterName  = NULL;
        if (!$stmt->bind_result($shelterName)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        //linked list for result
        while ($stmt->fetch()) {
            $name = $shelterName;
        }
        $stmt->close();
        $this->mysqli->close();
        return $name;
    }
    
    public function lookupIDForNameAndEmail($shelterId) {
        
        $this->connect();
        
        $shelterId = $this->mysqli->real_escape_string($shelterId);
        
        if (!($stmt = $this->mysqli->prepare("SELECT ShelterName, ShelterEmail FROM Shelters WHERE ShelterID = ?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("i", $shelterId)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        $shelterName  = NULL;
        $shelterEmail  = NULL;
        if (!$stmt->bind_result($shelterName, $shelterEmail)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        //create linkedlist
        $list = new SplDoublyLinkedList();
        while ($stmt->fetch()) {
            $tempArray = [
                "shelterName"     => $shelterName,
                "shelterEmail"   => $shelterEmail,
                ];
            $list->push($tempArray);
        }
        $stmt->close();
        $this->mysqli->close();
        return $list;
    }  
        
}

/*************************************************
* Pet Object
* - Functions:
*   -add(shelterID, petName, petBio, killDate)
*       -adds pet to pet table with corresponding shelter FK
*       -4 variables are required
*       -shelterID will need to be looked up
*   -update(petID, newKillDate)
*       -updates the pet killDate in the database
*       -2 variables required
*   -addMedia(petID, rankID, mediaRef, altText)
*       -adds mediaRef to pet.
*       -4 variables required
*   -viewAdditionalInfo(petID)
*       -returns list containing all image references related to 
*       -if nothing found, returns a list with 1 node containing a NULL value
*           -**future fix, return just NULL
*       -1 required variable
*    -showPetList(proximity, userLat, userLon)
*       -A linked list is returned. Each row is an associative array inside of a list node.
*       -if nothing found, returns a list with 1 node containing an array with NULL values
*           -**future fix, return just NULL
*           -Returned Data:
*               -Pet ID
*               -Pet Name
*               -Pet Bio
*               -Date of Death
*               -Distance
*               -#1 ranked image reference
*       -3 variables required
*           -userLat is user Latitude
*           -userLon is user Longitude
*   -function showSpecificPetAtShelter($shelterId, $petId)
*       -A linked list with 1 node containing an array is returned.
*       -if nothing found, returns a list with 1 node containing an array with NULL values
*           -**future fix, return just NULL
*           -Returned Data:
*               -Pet ID
*               -Pet Name
*               -Pet Bio
*               -Date of Death
*               -Distance
*   -public function showPetsAtShelter($shelterId)
*       -A linked list with with pet nodes containing an array of pet details is returned.
*       -if nothing found, returns a list with 1 node containing an array with NULL values
*           -**future fix, return just NULL
*           -Returned Data:
*               -Pet ID
*               -Pet Name
*               -Pet Bio
*               -Date of Death
*               -Distance
*************************************************/
class PetInterface extends DatabaseConnection {
    
    public function add($shelterId, $petName, $petBio, $killDate) {
        
        $this->connect();
        
        $shelterId  = $this->mysqli->real_escape_string($shelterId);
        $petName    = $this->mysqli->real_escape_string($petName);
        $petBio     = $this->mysqli->real_escape_string($petBio);
        $killDate   = $this->mysqli->real_escape_string($killDate);
        
        if (!($stmt = $this->mysqli->prepare("INSERT INTO Pets(ShelterID, PetName, PetBio, EndDate) VALUES(?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("isss", $shelterId, $petName, $petBio, $killDate)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        $lastId = $stmt->insert_id;
        
        $stmt->close();
        $this->mysqli->close();
        
        return $lastId;
    }
    
    public function updateKillDate($petID, $newKillDate) {
        
        $this->connect();
        
        $petID          = $this->mysqli->real_escape_string($petID);
        $newKillDate    = $this->mysqli->real_escape_string($newKillDate);
        
        if (!($stmt = $this->mysqli->prepare("UPDATE Pets SET EndDate = ? WHERE PetID = ?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("si", $newKillDate, $petID)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->close();
        $this->mysqli->close();
    }
    
    public function addMedia($petID, $rankID, $mediaRef, $altText) {
        
        $this->connect();
        
        $petID          = $this->mysqli->real_escape_string($petID);
        $rankID         = $this->mysqli->real_escape_string($rankID);
        $mediaRef       = $this->mysqli->real_escape_string($mediaRef);
        $altText        = $this->mysqli->real_escape_string($altText);   
        
        if (!($stmt = $this->mysqli->prepare("INSERT INTO Media(PetID, RankID, MediaRef, AltText) VALUES (?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("iiss", $petID, $rankID, $mediaRef, $altText)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->close();
        $this->mysqli->close();
    }
    
    public function viewAdditionalPetInfo($petID) {
        
        $this->connect();
        
        $petID = $this->mysqli->real_escape_string($petID);
                
        if (!($stmt = $this->mysqli->prepare("
            SELECT 
                m.PetID, 
                m.MediaRef
            FROM Media m
            WHERE m.PetID = ?
            ORDER BY m.RankID ASC"
            ))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("i", $petID)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        $imgRef = NULL;
        $petId  = NULL;
        if (!$stmt->bind_result($petId, $imgRef)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        //linked list for result
        $list = new SplDoublyLinkedList();
        while ($stmt->fetch()) {
            $list->push($imgRef);
        }
        $stmt->close();
        $this->mysqli->close();
        return $list;
    }
    
     
        public function showPetsList($proximity, $userLat, $userLon) {
        
        $this->connect();
        
        $proximity      = $this->mysqli->real_escape_string($proximity);
        $userLat        = $this->mysqli->real_escape_string($userLat);
        $userLon        = $this->mysqli->real_escape_string($userLon);
    
        if (!($stmt = $this->mysqli->prepare("
        SELECT 
            p.PetID, 
            p.PetName,
            p.PetBio,
            p.EndDate,
            p.ShelterID,
            (TIMESTAMPDIFF(SECOND, NOW(), p.EndDate)) AS FinalCountDown,
			m.MediaRef,
			(ROUND (2*3959*asin(sqrt(pow(sin((radians(?)-radians(s.Lat))/2),2)+cos(radians(s.Lat))
                *cos(radians(?))*pow(sin((radians(?)-radians(s.Lng))/2),2))),0)) AS Distance
            FROM Pets p LEFT JOIN Media m ON m.PetID = p.PetID LEFT JOIN Shelters s ON s.ShelterID = p.ShelterID
            WHERE (TIMESTAMPDIFF(SECOND, NOW(), p.EndDate)) >= 0
            AND (ROUND (2*3959*asin(sqrt(pow(sin((radians(?)-radians(s.Lat))/2),2)+cos(radians(s.Lat))
                *cos(radians(?))*pow(sin((radians(?)-radians(s.Lng))/2),2))),0)) <= ?
            ORDER BY (TIMESTAMPDIFF(SECOND, NOW(), p.EndDate))  DESC"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("ddddddi", $userLat, $userLat, $userLon, $userLat, $userLat, $userLon, $proximity)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        //return result
        $petId      = NULL;
        $petName    = NULL;
        $petBio     = NULL;
        $endDate    = NULL;
        $shelterId  = NULL;
        $timeLeft   = NULL;
        $imgRef     = NULL;
        $distance   = NULL;
        
        if (!$stmt->bind_result($petId, $petName, $petBio, $endDate, $shelterId, $timeLeft, $imgRef, $distance)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        //create linkedlist
        $list = new SplDoublyLinkedList();
        while ($stmt->fetch()) {
            $tempArray = [
                "petId"     => $petId,
                "petName"   => $petName,
                "petBio"    => $petBio,
                "endDate"   => $endDate,
                "shelterId"   => $shelterId,
                "timeLeft"  => $timeLeft,
                "imgRef"    => $imgRef,
                "distance"  => $distance,
                ];
            $list->push($tempArray);
        }
        $stmt->close();
        $this->mysqli->close();
        return $list;
    }
    
    /*SET @lat = 123;
            SET @long = 123;
            SET @dist = 25;

            SELECT 
            p.PetID, 
            p.PetName,
            p.PetBio,
            p.EndDate,
            p.ShelterID,
            (TIMESTAMPDIFF(SECOND, NOW(), p.EndDate)) AS FinalCountDown,
			m.MediaRef,
			(ROUND (2*3959*asin(sqrt(pow(sin((radians(@lat)-radians(s.Lat))/2),2)+cos(radians(s.Lat))
                *cos(radians(@lat))*pow(sin((radians(@long)-radians(s.Lng))/2),2))),0)) AS Distance
            FROM Pets p LEFT JOIN Media m ON m.PetID = p.PetID LEFT JOIN Shelters s ON s.ShelterID = p.ShelterID
            WHERE (TIMESTAMPDIFF(SECOND, NOW(), p.EndDate)) >= 0
            AND (ROUND (2*3959*asin(sqrt(pow(sin((radians(@lat)-radians(s.Lat))/2),2)+cos(radians(s.Lat))
                *cos(radians(@lat))*pow(sin((radians(@long)-radians(s.Lng))/2),2))),0)) <= @dist
            ORDER BY (TIMESTAMPDIFF(SECOND, NOW(), p.EndDate))  DESC
        *
        */
    
    //Added to provide list of all dogs at a particular shelter (for shelter use)
    public function showPetsAtShelter($shelterId) {
        
        $this->connect();
        
        $shelterId = $this->mysqli->real_escape_string($shelterId);
        
        if (!($stmt = $this->mysqli->prepare("
        SELECT 
            p.PetID, 
            p.PetName,
            p.PetBio,
            p.EndDate,
            (TIMESTAMPDIFF(SECOND, NOW(), p.EndDate)) AS 'FinalCountDown',
			m.MediaRef
            FROM Pets p LEFT JOIN Media m ON m.PetID = p.PetID
            WHERE p.ShelterID = ?
            ORDER BY (TIMESTAMPDIFF(SECOND, NOW(), p.EndDate)) DESC"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("i", $shelterId)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        //return result
        $petId      = NULL;
        $petName    = NULL;
        $petBio     = NULL;
        $endDate    = NULL;
        $timeLeft   = NULL;
        $imgRef     = NULL;
        if (!$stmt->bind_result($petId, $petName, $petBio, $endDate, $timeLeft, $imgRef)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        //create linkedlist
        $list = new SplDoublyLinkedList();
        while ($stmt->fetch()) {
            $tempArray = [
                "petId"     => $petId,
                "petName"   => $petName,
                "petBio"    => $petBio,
                "endDate"   => $endDate,
                "timeLeft"  => $timeLeft,
                "imgRef"    => $imgRef,
                ];
            $list->push($tempArray);
        }
        $stmt->close();
        $this->mysqli->close();
        return $list;
    }
    
    //Added to get bio of dog
    public function showSpecificPetAtShelter($shelterId, $petId) {
        $this->connect();
        
        $shelterId = $this->mysqli->real_escape_string($shelterId);
        $petId = $this->mysqli->real_escape_string($petId);
        
        if (!($stmt = $this->mysqli->prepare("
        SELECT 
            p.PetID, 
            p.PetName,
            p.PetBio,
            p.EndDate,
            (TIMESTAMPDIFF(SECOND, NOW(), p.EndDate)) AS 'FinalCountDown',
			m.MediaRef
            FROM Pets p LEFT JOIN Media m ON m.PetID = p.PetID
            WHERE p.ShelterID = ? AND p.PetID = ?
            ORDER BY (TIMESTAMPDIFF(SECOND, NOW(), p.EndDate)) DESC"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$stmt->bind_param("ii", $shelterId, $petId)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        //return result
        $petId      = NULL;
        $petName    = NULL;
        $petBio     = NULL;
        $endDate    = NULL;
        $timeLeft   = NULL;
        $imgRef     = NULL;
        if (!$stmt->bind_result($petId, $petName, $petBio, $endDate, $timeLeft, $imgRef)) {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        //create linkedlist
        $list = new SplDoublyLinkedList();
        while ($stmt->fetch()) {
            $tempArray = [
                "petId"     => $petId,
                "petName"   => $petName,
                "petBio"    => $petBio,
                "endDate"   => $endDate,
                "timeLeft"  => $timeLeft,
                "imgRef"    => $imgRef,
                ];
            $list->push($tempArray);
        }
        $stmt->close();
        $this->mysqli->close();
        
        return $list;
    }
}
?>
