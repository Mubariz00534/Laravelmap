<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
    <style>
        </style> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/openlayers/2.13.1/theme/default/style.css" type="text/css"> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/openlayers/2.13.1/OpenLayers.js"></script>
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/superagent/1.2.0/superagent.min.js"></script-->
<script src="http://smalljs.org/ajax/superagent/superagent.js"></script>
<style>

    #mapContainer {
    width: 100%;
    height: 400px;
    }
    #map {
    width: 100%;
    height: 100%;
    border: 1px solid #999;
    }

    /* SEARCH BOX */
    .box {
    position: absolute;
    top: 15px;
    right: 50px;
    z-index: 1000;
    }

    .ol-touch .box {
    top: 1.5em;
    right: 70px; }

    .search-container {
    overflow: hidden;
    width: 250px;
    vertical-align: middle;
    white-space: nowrap; }

    .ol-touch .search-container {
    width: 200px; }

    .search-container input#search {
    width: 250px;
    height: 30px;
    background: rgba(255, 255, 255, 0.75);
    border: none;
    font-size: 12pt;
    float: left;
    color: #1e6aa8;
    padding-left: 15px;
    padding-right: 60px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px; }

    .ol-touch .search-container input#search {
    width: 200px; }

    .search-container input#search::-webkit-input-placeholder {
    color: #1e6aa8; }

    .search-container input#search:-moz-placeholder {
    /* Firefox 18- */
    color: #1e6aa8; }

    .search-container input#search::-moz-placeholder {
    /* Firefox 19+ */
    color: #1e6aa8; }

    .search-container input#search:-ms-input-placeholder {
    color: #1e6aa8; }

    .search-container button.icon {
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    -moz-border-radius-topright: 5px;
    -moz-border-radius-bottomright: 5px;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    border: none;
    background: #1e6aa8;
    height: 30px;
    width: 50px;
    color: #4f5b66;
    opacity: 0;
    font-size: 12pt;
    -webkit-transition: all .1s ease;
    -moz-transition: all .1s ease;
    -ms-transition: all .1s ease;
    -o-transition: all .1s ease;
    transition: all .1s ease; }

    .search-container:hover button.icon,
    .search-container:active button.icon,
    .search-container:focus button.icon {
    outline: none;
    opacity: 1;
    margin-left: -50px;
    color: #fff; }

    .search-container:hover button.icon:hover {
    background: #3498db;
    color: #fff;
    cursor: pointer; }

    </style>
</head>
<body>
    <div id="mapContainer">
    <div id="map">
    </div>
    <div class="box">
        <div class="search-container">
            <form id="searchForm">
                <input type="search" id="search" placeholder="Search..." />
            </form>
            <button class="icon search"><i class="fa fa-search"></i></button>
        </div>
    </div>
    </div>
</body>
</html>

<script>
    var map, layer;

map = new OpenLayers.Map('map');
layer = new OpenLayers.Layer.WMS("OpenLayers WMS",
                                 "http://vmap0.tiles.osgeo.org/wms/vmap0", {
  layers: 'basic'
});
map.addLayer(layer);
map.zoomToMaxExtent();

document.querySelector('#searchForm').addEventListener('submit', function(e) {
  geocodeSearch(e.target.children.namedItem('search').value);
  e.preventDefault();
  return false; // prevent page reload
});
document.querySelector('button.search').addEventListener('click', function(e) {
  geocodeSearch(document.querySelector('#search').value);
});

/*  geocodeSearch() requires:
		* jQuery -> rewrite AJaX request using your own method
    * map
*/
function geocodeSearch(searchStr) {
  //var bbox = ol.proj.transform($.OLMAP.view.calculateExtent($.OLMAP.map.getSize()),
  //                             'EPSG:3857', 'EPSG:4326').toString(),
    var bbox = map.getExtent().toBBOX();
    var center = map.getCenter().transform(
                    map.getProjectionObject(), //4326
                    new OpenLayers.Projection("EPSG:3857")
                );
    var distance = map.getExtent().transform(
                   map.getProjectionObject(), new OpenLayers.Projection("EPSG:3857")
                ).getWidth();
    var url = 'http://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates' +
      '?SingleLine=' + searchStr +
      '&f=json' +
      '&location=' + center +
      '&distance=' + distance + // '3218.69' = 2 meters
      //'&outSR=102100'+ // web mercator
      //'&searchExtent='+bbox+
      //'&outFields=Loc_name'+
      '&maxLocations=1';
  var parser = new OpenLayers.Format.GeoJSON();
  var req = window.superagent;
  req.get(url, function(response){
    var responseJson = JSON.parse(response.text);
    var searchExt = responseJson.candidates[0].extent;
    var extent = new OpenLayers.Bounds(searchExt.xmin, searchExt.ymin, searchExt.xmax, searchExt.ymax);
    map.zoomToExtent(extent);
  });
}

document.map = map;


</script>