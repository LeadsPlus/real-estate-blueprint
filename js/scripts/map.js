function Map () {

}

Map.prototype.init = function ( params ) {
	this.map = params.map || {};
	this.listings = params.listings || {};
	this.polygons = params.polygons || {};
	this.markers = params.markers || {};


	
}

Map.prototype.get_bounds =  function () {
	var response = {}
	var bounds = map_js_var.map.getBounds();
	if ( typeof bounds == 'undefined' ) {
		return response;
	}
	response.vertices = [];
	response.vertices[0] = {};
	response.vertices[1] = {};
	response.vertices[2] = {};
	response.vertices[3] = {};
	response.vertices[0]['lat'] = bounds.getNorthEast().lat();
	response.vertices[0]['lng'] = bounds.getNorthEast().lng();
	response.vertices[1]['lat'] = bounds.getNorthEast().lat();
	response.vertices[1]['lng'] = bounds.getSouthWest().lng();
	response.vertices[2]['lat'] = bounds.getSouthWest().lat();
	response.vertices[2]['lng'] = bounds.getSouthWest().lng();
	response.vertices[3]['lat'] = bounds.getSouthWest().lat();
	response.vertices[3]['lng'] = bounds.getNorthEast().lng();

	return response;
}