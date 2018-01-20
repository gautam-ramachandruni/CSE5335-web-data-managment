//Gautam Ramachandruni
// Put your zillow.com API key here
var zwsid = "X1-ZWz1g1sifrtxcb_39zrb";

var request;
var lat, lng, coord;
var marker;
var map;
var address;
var addrParts;
var streetName, city, state, zipcode;
var geocoderObj;
var infowindow;
var zvalue;

function initialize() {
	request = new XMLHttpRequest();
	lat = 32.75;
	lng = -97.13;
	geocoderObj = new google.maps.Geocoder();
	infowindow = new google.maps.InfoWindow();
}

function initMap() {
    coord = {lat: 32.75, lng: -97.13};
    map = new google.maps.Map(document.getElementById('map'), {
          zoom: 17, center: coord
    });
    marker = new google.maps.Marker({
		position: coord,
        map: map
    });
	google.maps.event.addListener(map, 'click', function(event) {
	   placeMarker(event.latLng);
	   geocodeLatLng(geocoderObj, map, infowindow);
	});
}

function placeMarker(location) {
	map.setCenter(location);
	marker.setPosition(location);
	marker.setMap(map);
	lat = location.lat();
	lng = location.lng();
	console.log(location.lat());
	console.log(location.lng());
}

function find() {
	request.onreadystatechange = displayResult;
    address = document.getElementById("address").value;
	//console.log(address);
    addrParts = address.split(",");
	streetName = addrParts[0].trim();
	city = addrParts[1].trim();
	state = addrParts[2].trim();
	zipcode = addrParts[3].trim();
    request.open("GET","proxy.php?zws-id="+zwsid+"&address="+streetName+"&citystatezip="+city+"+"+state+"+"+zipcode);
    request.withCredentials = "true";
    request.send(null);
	//console.log(address);
	var geocoderObj = new google.maps.Geocoder();
	var geocoderRequest = {address: address};
	geocoderObj.geocode(geocoderRequest, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
		map.setCenter(results[0].geometry.location);
        marker.setPosition(results[0].geometry.location);
		marker.setMap(map);
		}
		else {
			alert(status);
		}
	});
}

function displayResult() {
    if (request.readyState == 4) {
        var xml = request.responseXML.documentElement;
		console.log(xml);
		if(xml.getElementsByTagName("message")[0].getElementsByTagName("code")[0].innerHTML==="0") {
			zvalue = xml.getElementsByTagName("zestimate")[0].getElementsByTagName("amount")[0].innerHTML;
			document.getElementById("output").innerHTML += "Address: " + address + "<br>Zestimate: $" + zvalue + "<br><br>";
			infowindow.setContent("Address: " + address + "<br> Zestimate: $" + zvalue);
			infowindow.open(map, marker);
		}
		else {
			alert(xml.getElementsByTagName("text")[0].innerHTML);
			infowindow.setContent("Address: " + address + "<br> Zestimate: not found");
			infowindow.open(map, marker);
			document.getElementById("output").innerHTML += "Address: " + address + "<br>Zestimate: not available.<br><br>";	
		}
    }
}

function geocodeLatLng(geocoder, map, infowindow) {
  var latlng = {lat: lat, lng: lng};
  geocoder.geocode({'location': latlng}, function(results, status) {
    if (status === 'OK') {
      if (results[0]) {
		request.onreadystatechange = displayResult;
        map.setZoom(17);
		address = results[0].formatted_address;
        addrParts = address.split(",");
		streetName = addrParts[0].trim();
		city = addrParts[1].trim();
		state = addrParts[2].trim();
		zipcode = addrParts[3].trim();
		//console.log(streetName);
		//console.log(city);
		//console.log(state);
		//console.log(zipcode);
		request.open("GET","proxy.php?zws-id="+zwsid+"&address="+streetName+"&citystatezip="+city+"+"+state+"+"+zipcode);
		request.withCredentials = "true";
		request.send(null);
      }
	  else {
        window.alert('No results found');
      }
    }
	else {
      window.alert('Geocoder failed due to: ' + status);
    }
  });
}

/*function displayMapsResult(){
	if (this.readyState == 4) {
        var jsonResult = JSON.parse(this.responseText);
		//console.log(jsonResult);
		lat = jsonResult.results[0].geometry.location.lat;
		lng = jsonResult.results[0].geometry.location.lng;
		//console.log(lat);
		//console.log(lng);
		coord = new google.maps.LatLng(lat, lng);
		map.setCenter(coord);
        marker.setPosition(coord);
		marker.setMap(map);
    }
}*/