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

The program inserts an overlay marker on the Google map pinned on the latest house that displays the house's postal address and its Zestimate value (the house value) from zillow.com. The text display area is the history log that displays all the houses (addresses and prices) that you have found so far (latest hous is last). Each time you find a house, you erase the old marker from the map (if any), you display a new marker on the map on the house location (with address and price), and you append this information to the display area. There are two ways to find a house:
1.	by providing a valid postal address, say "904 Austin St, Arlington, TX 76012", in the text input and you push the Find button.
2.	by clicking on a house on the map.
To implement the first way, you need to get the address from the text input and send an asynchronous request to the Zillow API (GetSearchResults API) to retrieve the Zestimate (in $), which is the estimated home value of the house at that address. The address used by the GetSearchResults API must have two components: address and citystatezip. This means that you must use JavaScript code to break the address string from the input text area into these two strings. Then you clear the old marker and insert a new overlay marker on the map at the latitude and longitude of this postal address, using Geocoding. To implement the second way, when you click on the map, your program must find the address of the point you clicked (using Reverse Geocoding) and must send an asynchronous request to the Zillow API (GetSearchResults API) to retrieve the Zestimate (in $), which is the estimated home value of the house at that address. The you do the same as the first way.
Note that the call to the GetSearchResults API must be done using Ajax: inside the callback function (the listener for the left click) of the map, you should create an Ajax request that calls the GetSearchResults API. When the result arrives (this is the callback of the Ajax request), you extract the Zestimate and you display a new overlay marker on the map at the point you clicked. The overlay marker must display the house postal address and its Zestimate. The same information must be appended at the end of the text display area (third section). Note also that the map must display at most one marker and the text display area may contain multiple addresses/zestimates. If it is an invalid address or there is no Zestimate value, you don't change anything. Finally, the Clear button clears the text input only.
Hints:
•	How to URL-encode the address to send it to zillow: use the method encodeURI(address).
•	How to extract the Zestimate value from the zillow XML response: use the method getElementsByTagName('amount').
Note that everything should be done asynchronously and your web page should never be redrawn completely. You need only one XMLHttpRequest object for sending a request to Zillow, since Google Maps is already asynchronous.
