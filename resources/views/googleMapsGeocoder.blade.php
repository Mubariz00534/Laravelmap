<!DOCTYPE html>
<html>
  <head>
    <title>Googlemaps Search</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
      #floating-panel2 {
        position: absolute;
        top: 5px;
        left: 50%;
        margin-left: -180px;
        width: 350px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }
    </style>
  </head>
  <body>
    <div id="floating-panel">
      <input id="address" type="textbox" value="City Point, Baku">
      <input id="submitByName" type="button" value="Search">
    </div>
    <div id="floating-panel2">
      <input id="latlng" type="text" value="40.4093, 49.8671">
      <input id="submitByLatLng" type="button" value="Reverse Geocode">
    </div>
    <div id="map"></div>
    <script>
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: {lat: 40.4093, lng: 49.8671}
        });
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow();

        document.getElementById('submitByName').addEventListener('click', function() {
          geocodeAddress(geocoder, map);
        });

        document.getElementById('submitByLatLng').addEventListener('click', function() {
          geocodeLatAndLng(geocoder, map, infowindow);
        });
      }

      function geocodeAddress(geocoder, resultsMap, infowindow) {
        var address = document.getElementById('address').value;
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location
            });
            infowindow.setContent(result[0].formatted_address);
            infowindow.open(map, marker);
          } else {
            alert('Geocode error for this reason: ' + status);
          }
        });
      }

      function geocodeLatAndLng(geocoder, map, infowindow) {
        var input = document.getElementById('latlng').value;
        var latlngStr = input.split(',', 2);
        var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
            if (results[0]) {
              map.setZoom(11);
              var marker = new google.maps.Marker({
                position: latlng,
                map: map
              });
              infowindow.setContent(results[0].formatted_address);
              infowindow.open(map, marker);
            } else {
              window.alert('No results found');
            }
          } else {
            window.alert('Geocoder failed due to: ' + status);
          }
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAT1wPyNhWJy6a96A3pAi3aE6rizclLcm0&callback=initMap">
    </script>
  </body>
</html>