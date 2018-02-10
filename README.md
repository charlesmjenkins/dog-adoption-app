# Dog Adoption Simulated Mobile App

- **[Try the Web App](http://charlesmjenkins.com/dog-adoption-app/startup.php)** 
- **Description:** A web app simulating a mobile app that shows users a list of dogs in nearby shelters in order of "euthanization schedule" to help incentivize users to adopt
- **Technologies:** MySQL, PHP, HTML5, CSS, JavaScript, jQuery, Google Maps Geocoding API
- **Team Project?:** Yes, with five other technical members
- **My Lead Contributions:** Built entire front-end, led project management and deliverable polish, taught web development principles to teammate during XP pair programming sessions
- **My Additional Contributions:** System spec and design, backend integration
- **Note:** For security purposes, database login credentials, API keys, etc. have been omitted from the code uploaded here.

## How to Execute:
- This program is written in PHP, JavaScript and HTML as an approximation of
  a phone application's interface.
  
- Recommended browser: Google Chrome
    - Other browser may generate unexpected UI issues.
    - Chrome is required to properly insert and add dog listings due to use of the datetime-local input element.

- To start, enter the following URL in your browser: http://charlesmjenkins.com/dog-adoption-app/startup.php

- There are two "sides" to the application--user and shelter.

- To test the user side:
    - From the startup page, click "I am a user browsing for dogs."
        - You may now select a distance radius relative to a location you provide to search for dogs.
        - Type in an address, generally the format \[address\] \[city\] \[state\] works best, and click "Pinpoint Location."
        - If the application finds your location adequately, click "Search."
        - Due to a lack of exhaustive shelter data, it is recommended that the grader click "Test Location" to populate
          an address for which shelters have already been inserted into the database. Then click "Search."

    - This brings you to the listings of all the dogs at shelters within range of the address you input.
    - Traditional scrolling has been disabled. Click to drag the list up and down like you would swipe a phone.
    - Click a dog's magnifying glass icon to view its bio.
        - Within the bio page, click the shelter's email address to compose an email to it.
        - Click the back arrow at the bottom of the phone to return to the listings of dogs.
    
    - Click "Log Out" to return to the startup page.
    
- To test the shelter side:
    - From the startup page, click "I am a shelter managing my dogs."
    - You may now enter the email of an existing shelter account with which to log in.
        - To test use the email: michigantest1@test.org
    
    - This brings you to the listings of all the dogs at the shelter.
    - Traditional scrolling has been disabled. Click to drag the list up and down like you would swipe a phone.
    - Click a dog's magnifying glass icon to view its bio.
    - To adjust the end time of the dog's listing, click the clock symbol at the bottom of the bio.
        - Input a date and time and click "Update Listing."
            - This feature requires Google Chrome to work.
        - You may also click the back arrow at the bottom of the phone to simply return to the listings of dogs.
    
    - Back at the list of dogs, click the "+" in the upper right corner to bring up a dialogue to add a listing.
        - Click "Choose File" and select a photo from your computer to upload.
        - Type in a name, bio, and end date. (The end date control requires Google Chrome to work.)
        - Click "Add Listing."
        
    - Click "Log Out" to return to the startup page.