/*
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: lookupQueries.php
//
// Description: SQL queries manipulating database.
// ---------------------------------------
*/

/*
    Add Shelter
*/

SET @ShelterName -- Shelter Name
;
SET @ShelterEmail -- Shelter emaail used for communication
;
SET @Latitude -- 
;
SET @Longitude --
;

INSERT
    INTO Shelters (ShelterName, ShelterEmail, Lat, Lng)
    VALUES (@ShelterName, @ShelterEmail, @Latitude, @Longitude);

/*
Lookup recently created petID
*/
IDENT_CURRENT('Pets')

/*
Set Parameters need to be passed in through the upload form to provide attributes to media in DB
Specifically the PetID which it is linked
        Media Directory Extension/Path
        The type of media (maybe can read the extension)
        The Rank 0 - 10 that the media should be displayed 
        Alt Text to describe the image, can be later helpful in tagging
*/

SET @Pet -- This will need to be which pet the media is linked to
;
SET @MediaReference = --This will need to be the directory reference 
;
SET @Rank = -- This will need to be what rank, can be count(MediaID) + 1 GROUP BY PetID 
;
SET @AltText = --Needs to be description or alt text 
;

INSERT 
    INTO Media (PetID,RankID,MediaRef,AltText) 
    VALUES (@Pet,@Rank,@MediaReference,@AltText);

/*
lookup shelter by email when adding pet
*/
SET @lookupEmail = --email being entered when adding a new pet
;

SELECT
    ShelterID
FROM
    Shelters 
WHERE
    ShelterEmail = @lookupEmail

/*
Upload pet, need to perform lookup on shelter to find shelterID
*/

SET @Shelter = ;

SET @PetName = ;

SET @PetBio = ;
$a
SET@EndDate = ;


INSERT
    INTO Pets (ShelterId, PetName, PetBio, EndDate)
    VALUES(@Shelter, @PetName, @PetBio, @EndDate);

/*
Ability to add a date and time for the euthanasia (2u)
Required time once INSERT new pet 
Can be changed through UPDATE
*/

SET @NewKillDate = --Date to update kill date to
;
SET @Pet = --The pet that needs to postpone death
;

UPDATE pets
SET EndDate = @NewKillDate
WHERE p.PetID = @Pet;


/*

Takes the pet selected and returns in a concatenated string to be parsed out 
all the photo pthats for that pet.

*/

SET @Pet = --The pet user selects to see all pictures
;

SELECT m.PetID, 
    GROUP_CONCAT(DISTINCT m.MediaRef ORDER BY m.RankID ASC)
FROM media m
WHERE @Pet = m.PetID
GROUP BY 1

/*
A filtering option based on distance radius (4u)
Lat/Lng math to determine distance from Pets stored in the DB when User/Shelter is created 
Only displays pets that are alive, ie not past expiration date)
*/

SET @Proximity = --How far they are willing to go
;
SET @UserLat = --users latitude 
;
SET @UserLong = --users longitude
;

SELECT p.PetID, 
    p.PetName,
    p.PetBio,
    CAST(p.EndDate AS DATE) AS 'EndDate',
    (ROUND(2*3959*asin(sqrt(pow(sin((radians(@UserLat)-radians(s.Lat))/2),2)+cos(radians(s.Lat))
    *cos(radians(@Userlat))*pow(sin((radians(@UserLong)-radians(s.Lng))/2),2))),0)) AS 'Distance',
    (DATEDIFF(CAST(p.EndDate AS DATE),CurDate())) AS 'FinalCountDown',
    (SELECT DISTINCT m.PetID, 
        GROUP_CONCAT(m.MediaRef)
        FROM Media m
        WHERE p.PetID = m.PetID
        ORDER BY m.RankID ASC
        GROUP BY 1) imgref
FROM Pets p JOIN Shelters s ON s.ShelterID = p.ShelterID
WHERE 'FinalCountDown' >= 0
    AND 'Distance' <= @Proximity;