var locations = [];

$.post("geoloc/renderIP.php", 
    {}, 
    function(data){
        locations = JSON.parse(data);
        // console.log(locations);
});
    


function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 6,
          center: {lat: 46.815558, lng: 2.535592}
        });

        // Create an array of alphabetical characters used to label the markers.
        // var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
            return new google.maps.Marker({
                position: location
                });
            });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
     }
     
$("#timeSearch").click(function(){
    
   if(document.getElementById('debut').value != ''){
       
   }
    
});