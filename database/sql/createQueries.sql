/*
// CS361 PROJECT B - DOG ADOPTION APP
// ---------------------------------------
// Title: createQueries.php
//
// Description: SQL queries for database creation.
// ---------------------------------------
*/

CREATE DATABASE AdoptApp;

USE AdoptApp;

/*
*   RankId      -   is used for ordering
*   MediaRef    -   media location
*   AltText     -   description
*/

CREATE TABLE Media (
    MediaID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    PetID INT UNSIGNED NOT NULL,
    RankID INT UNSIGNED NOT NULL,
    MediaRef VARCHAR(30) NOT NULL,
    AltText VARCHAR(30) NOT NULL,
    mediaupload_date TIMESTAMP DEFAULT NOW()
) ENGINE=InnoDB; 

CREATE TABLE Shelters (
    ShelterID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ShelterName VARCHAR(30),
    ShelterBio VARCHAR(255),
    ShelterEmail VARCHAR(30) NOT NULL,
    Lat DECIMAL(16,8) NOT NULL,
    Lng DECIMAL(16,8) NOT NULL, 
    reg_date TIMESTAMP DEFAULT NOW()
) ENGINE=InnoDB;

CREATE TABLE Pets (
    PetID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ShelterID INT UNSIGNED NOT NULL,
    PetName VARCHAR(30) NOT NULL,
    PetBio VARCHAR(255) NOT NULL,
    ListDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    EndDate DATETIME DEFAULT NULL
) ENGINE=InnoDB;



ALTER TABLE Pets ADD CONSTRAINT ShelterID FOREIGN KEY (ShelterID) REFERENCES Shelters(ShelterID);

ALTER TABLE Media ADD CONSTRAINT PetID FOREIGN KEY (PetID) REFERENCES Pets(PetID);
