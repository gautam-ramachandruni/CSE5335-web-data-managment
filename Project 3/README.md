# Web Mashup: Display House Prices on a Map
The goal of this project is to create a web mashup that combines two web services: Google Maps and the Zillow API, using JavaScript and AJAX. When a house on the map is clicked, this application will display the postal address and the estimated price of the house.

For this project, the following are used:

•	Real Estate API from the Zillow API (more specifically, GetSearchResults)

•	Geocoding and Reverse Geocoding from the Google Maps JavaScript API

•	Google Map Markers

An API key is needed to use Google Maps. Fetching a Zillow API requires the user to register and obtain a web service ID from Zillow Web Services ID (ZWSID). The HTML page has three sections:

1. A text input with two buttons: Find and Clear

2. A Google map of size 600*500 pixels, initially centered at (32.75, -97.13) with zoom level 17

3. A text display area

The program inserts an overlay marker on the Google map pinned on the latest house that displays the house's postal address and its Zestimate value (the house value) from zillow.com. The text display area is the history log that displays all the houses (addresses and prices) that have been found so far (latest house is last). Each time a house is discovered, the old marker is erased from the map (if any), and a new marker is displayed on the map on the house location (with address and price), and you append this information to the display area. So there are two ways to find a house:

1.	By providing a valid postal address, say "123 Lombard St, San Francisco, CA 94109", in the text input and pushing the Find button

2.	By clicking on a house on the map

Note that the call to the GetSearchResults API is done using Ajax: inside the callback function (the listener for the left click) of the map, an Ajax request that calls the GetSearchResults API is created. Note that everything is done asynchronously and the webpage is never redrawn completely.
