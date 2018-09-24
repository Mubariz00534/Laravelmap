<html>
<head>
   	<title>Mapstraction Simple Example</title>
   	<script src="http://openlayers.org/api/OpenLayers.js"></script>
   	<script src="http://mapstraction.com/mxn/build/latest/mxn.js?(openlayers)" type="text/javascript"></script>
	<style type="text/css">
	#map {
		height: 900px;
        width: 900px;
	}
	</style>
</head>
<body>
	<div id="map"></div>
	<script type="text/javascript">
		var map = new mxn.Mapstraction('map', 'openlayers'); 
		var latlon = new mxn.LatLonPoint(51.50733, -0.12769);

		map.setCenterAndZoom(latlon, 10);
	</script>
</body>
</html>		