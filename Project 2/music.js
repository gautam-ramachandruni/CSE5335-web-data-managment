//Gautam Ramachandruni
// Put your Last.fm API key here
var api_key = "15572d6593ed84491df59da31b02bd94";

var xhr;
var artist;
var methodInfo;
var methodAlbums;
var methodSimilar;
var str;
var outputDiv;
var jsonInfo;
var jsonAlbums;
var jsonSimilar;
var url;

function initialize() {
    str = "";
    url = "https://www.last.fm/music/";
    outputDiv = document.getElementById("output");
}

function sendRequest() {
    xhr = new XMLHttpRequest();
    methodInfo = "artist.getInfo";
    artist = encodeURI(document.getElementById("form-input").value);
    
    xhr.open("GET", "proxy.php?method="+methodInfo+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
    xhr.setRequestHeader("Accept","application/json");
    xhr.onreadystatechange = function() {
        if(this.readyState == 4) {
            jsonInfo = JSON.parse(this.responseText);
            str = JSON.stringify(jsonInfo, undefined, 2);
        }
    };
    xhr.send(null);
    
    xhr = new XMLHttpRequest();
    methodAlbums = "artist.getTopAlbums";
    xhr.open("GET", "proxy.php?method="+methodAlbums+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
    xhr.setRequestHeader("Accept", "application/json");
    xhr.onreadystatechange = function() {
        if(this.readyState == 4) {
            jsonAlbums = JSON.parse(this.responseText);
            str += JSON.stringify(jsonAlbums, undefined, 2);
            
        }
    };
    xhr.send(null);
    
    xhr = new XMLHttpRequest();
    methodSimilar = "artist.getSimilar";
    xhr.open("GET", "proxy.php?method="+methodSimilar+"&artist="+artist+"&api_key="+api_key+"&format=json", true);
    xhr.setRequestHeader("Accept", "application/json");
    xhr.onreadystatechange = function() {
        if(this.readyState == 4) {
            jsonSimilar = JSON.parse(this.responseText);
            str += JSON.stringify(jsonSimilar, undefined, 2);
            //document.getElementById("output").innerHTML = "<pre>" + str + "</pre>";
        }
    };
    xhr.send(null);

    //constructURL();
    modifyHTML();  
}

function modifyHTML() {
    outputDiv.innerHTML = "<br>";
    outputDiv.innerHTML +=  "<h1>" + jsonInfo.artist.name + "</h1>";
    outputDiv.innerHTML += "<h3> Last fm link: <a href=" + jsonInfo.artist.url + ">" + jsonInfo.artist.url + "</a> </h3><br>";
    outputDiv.innerHTML += "<img src = " + jsonInfo.artist.image[2]['#text'] + "alt=" + jsonInfo.artist.name + "/> <br><br><br>";
    outputDiv.innerHTML += "<h2> Bio </h2> <label> " + jsonInfo.artist.bio.summary + "</label><br><br><br>";
    
    outputDiv.innerHTML += "<h2> <b> Top Albums </b> </h2>";
    
    if(jsonAlbums.topalbums.album.length>10) {
        outputDiv.innerHTML += "<ol>";
        for(var i=0; i<5; i++) {
            outputDiv.innerHTML += "<li>" + jsonAlbums.topalbums.album[i].name + "</li><br>";
            outputDiv.innerHTML += "<img src = " + jsonAlbums.topalbums.album[i].image[2]['#text'] + "alt=" + jsonAlbums.topalbums.album[i].name + "/>  <br><br>";
        }
        outputDiv.innerHTML += "</ol>";
    }
    else {
        for(var k=0; k<jsonAlbums.topalbums.album.length; k++) {
            outputDiv.innerHTML += jsonAlbums.topalbums.album[k].name + "<br>";
            outputDiv.innerHTML += "<img src = " + jsonAlbums.topalbums.album[k].image[2]['#text'] + "alt=" + jsonAlbums.topalbums.album[k].name + "/>  <br><br>";
        }
    }
    
    outputDiv.innerHTML += "<br> <br>";  
    outputDiv.innerHTML += "<h2> <b> Similar Artists </b> </h2> <ol>";
    
    if(jsonSimilar.similarartists.artist.length>10) {
        outputDiv.innerHTML += "<ol>";
        for(var j=0; j<5; j++) {
            outputDiv.innerHTML += "<li>" + jsonSimilar.similarartists.artist[j].name + "</li><br>";
        }
        outputDiv.innerHTML += "</ol>";
    }
    else {
        for(var l=0; l<jsonSimilar.similarartists.artist.length; l++) {
            outputDiv.innerHTML += "-" + jsonSimilar.similarartists.artist[l].name + "<br>";
        }
    }
    
    outputDiv.innerHTML += "</ol>";
}

/*function constructURL(){
    var arr = artist.split(" ");
    for(var x=0;  x<arr.length; x++){
        url += arr[x];
        if(x==arr.length-1){
        }
        else{
            url += "%2B";
        }
    }
}*/