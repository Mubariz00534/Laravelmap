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
