//bounded map search
//info windows
//max results behavior
//no results behavior
//polygon map
//lifestyle map
//lifestyle polygon map

function Map () {}

Map.prototype.init = function ( params ) {
	this.map = false;
	this.type = params.type || alert('You must define a map type for the method to work properly');
	this.infowindows = [];
	this.markers = params.markers ||[];
	this.bounds = false;

	this.dom_id = params.dom_id || 'map_canvas'
	this.listings = params.listings || {};
	this.polygons = params.polygons || {};
	this.lat = params.lat || '42.37';
	this.lng = params.lng || '-71.03';
	this.zoom = params.zoom || 15;
	this.marker = {};
	this.marker.icon = params.marker || false;
	this.cetner = params.center || true;
	this.filter_by_bounds = params.filter_by_bounds || true;

	//build map
	var that = this;
	google.maps.event.addDomListener(window, 'load', function() {
		var latlng = new google.maps.LatLng(that.lat, that.lng);
		that.myOptions = { zoom: that.zoom, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
		that.map = new google.maps.Map(document.getElementById(that.dom_id), that.myOptions);
	});
}

Map.prototype.update = function ( ajax_response ) {
	if (ajax_response && ajax_response.aaData) {
		this.clear();
		for (var i = ajax_response.aaData.length - 1; i >= 0; i--) {
			this.create_listing_marker( ajax_response.aaData[i][1] );
		}
		// if filter by bounds, don't move the map, it's confusing
		if (this.center) {
			this.center();	
		}
	} else {
		this.show_empty();
	}
}

Map.prototype.clear = function () {
	if (this.markers) {
		for (var i = this.markers.length - 1; i >= 0; i--) {
			this.markers[i].setMap( null )
		}
        this.markers = [];
	}
}

Map.prototype.create_listing_marker = function ( listing ) {
	var marker = {};
	marker.latlng = new google.maps.LatLng(listing['location']['coords'][0], listing['location']['coords'][1]);

	if (listing['images'] && listing['images'][0] && listing['images'][0]['url']) {
    	var image_url = listing['images'][0]['url'];
    };
    marker.content = '<div id="content">'+
        '<div id="siteNotice">'+'</div>'+
          '<h2 id="firstHeading" class="firstHeading"><a href="'+ listing['cur_data']['url'] + '">' + listing['location']['full_address'] +'</a></h2>'+
          '<div id="bodyContent">'+
            '<img width="80px" height="80px" style="float: left" src="'+image_url+'" />' +
            '<ul style="float: right; width: 130px">' +
              '<li> Beds: '+ listing['cur_data']['beds'] +'</li>' +
              '<li> Baths: '+ listing['cur_data']['baths'] +'</li>' +
              '<li> Price: '+ listing['cur_data']['price'] +'</li>' +
            '</ul>' +
          '</div>' +
          '<div class="viewListing" style="margin: 15px 70px; float: left; font-size: 16px; font-weight: bold;"><a href="'+listing['cur_data']['url']+'">View Details</a></div>' +
          '<div class="clear"></div>' +
        '</div>'+
      '</div>';
    this.create_marker( marker );
}

Map.prototype.create_marker = function ( marker ) {
	var that = this;
	if (!this.marker.icon) {
		var marker_options = {position: marker.latlng};
	} else {
		var marker_options = {position: marker.latlng, icon: this.marker.icon };
	};
	var marker = new google.maps.Marker(marker_options);
	var infowindow = new google.maps.InfoWindow({content: marker.content});
	this.infowindows.push(infowindow);
	google.maps.event.addListener(marker, 'click', function() {
		for (var i = that.infowindows.length - 1; i >= 0; i--) {
			that.infowindows[i].setMap(null)
		};
		infowindow.open( that.map, marker );
	});
	marker.setMap(this.map);
	that.markers.push(marker);
}

Map.prototype.center = function () {
	var that = this;
	var bounds = new google.maps.LatLngBounds();
	
	//only reposition the map if it's not the first load (this.bounds) and the dev wants (this.filter_by_bounds)
	if ( this.markers && ( !this.filter_by_bounds || !this.bounds ) ) {
		for (var i = this.markers.length - 1; i >= 0; i--) {
			this.markers[i].setMap(this.map);
			bounds.extend(this.markers[i].getPosition());
		};

        if( typeof this.map != "undefined" ) {
        	this.map.fitBounds(bounds);
            google.maps.event.addListenerOnce(this.map, 'bounds_changed', function( event ) {
	            if ( that.map.getZoom() > 15 ) {
	            	that.map.setZoom( 15 );
	            }
            })
        }
	};
}

Map.prototype.get_bounds =  function () {
	if (!this.map && !this.bounds) {
		return this.bounds;
	}
	this.bounds = [];
	var map_bounds = this.map.getBounds();
	if ( typeof map_bounds == 'undefined' ) {
		return this.bounds;
	}
	this.bounds.push({'name' : 'polygon[0][lat]', 'value': map_bounds.getNorthEast().lat() });
	this.bounds.push({'name' : 'polygon[0][lng]', 'value': map_bounds.getNorthEast().lng() });

	this.bounds.push({'name' : 'polygon[1][lat]', 'value': map_bounds.getNorthEast().lat() });
	this.bounds.push({'name' : 'polygon[1][lng]', 'value': map_bounds.getSouthWest().lng() });

	this.bounds.push({'name' : 'polygon[2][lat]', 'value': map_bounds.getSouthWest().lat() });
	this.bounds.push({'name' : 'polygon[2][lng]', 'value': map_bounds.getSouthWest().lng() });

	this.bounds.push({'name' : 'polygon[3][lat]', 'value': map_bounds.getSouthWest().lat() });
	this.bounds.push({'name' : 'polygon[3][lng]', 'value': map_bounds.getNorthEast().lng() });

	return this.bounds;
}

Map.prototype.listeners = function () {}

Map.prototype.show_empty = function () {}