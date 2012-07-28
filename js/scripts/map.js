

//polygon map
//lifestyle map
//lifestyle polygon map

// only trigger map reload after 70% move in a direction
// only trigger map reload after a zoom out.

function Map () {}

Map.prototype.init = function ( params ) {
	this.map = false;
	this.type = params.type || alert('You must define a map type for the method to work properly');
	this.infowindows = [];
	this.markers = params.markers ||[];
	this.markers_hash = {};
	this.bounds = false;
	this.list = params.list || false;

	this.dom_id = params.dom_id || 'map_canvas'
	this.listings = params.listings || alert('You must attach a lisitngs object. Every arm needs a head.');
	this.polygons = params.polygons || {};
	
	// map settings
	this.lat = params.lat || '42.37';
	this.lng = params.lng || '-71.03';
	this.zoom = params.zoom || 15;
	this.cetner = params.center || true;
	this.filter_by_bounds = params.filter_by_bounds || true;

	//marker settings
	this.marker = {}
	this.marker.icon = params.marker || false;
	this.marker.icon_hover = params.marker_hover || 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=|FF0000|000000'

	//polygon settings
	this.selected_polygon = params.selected_polygon || false;
	this.slug = params.slug || false;

	
	// map/list interaction
	Map.prototype.marker_click = params.marker_click || function ( listing_id ) {
	
	}
	Map.prototype.marker_mouseover = params.marker_mouseover || function ( listing_id ) {
		var marker = this.markers_hash[listing_id];
		marker.setIcon(this.marker.icon_hover);
	}
	Map.prototype.marker_mouseout = params.marker_mouseout || function ( listing_id ) {
		var marker = this.markers_hash[listing_id];
		marker.setIcon(null);
	}

	//build map
	var that = this;
	google.maps.event.addDomListener(window, 'load', function() {
		var latlng = new google.maps.LatLng(that.lat, that.lng);
		that.myOptions = { zoom: that.zoom, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
		that.map = new google.maps.Map(document.getElementById(that.dom_id), that.myOptions);
	});
}

Map.prototype.update = function ( ajax_response ) {
	console.log(ajax_response);
	if (ajax_response && ajax_response.aaData) {
		this.clear();
		for (var i = ajax_response.aaData.length - 1; i >= 0; i--) {
			this.create_listing_marker( ajax_response.aaData[i][1] );
		}
		// if filter by bounds, don't move the map, it's confusing
		if (this.center) {
			this.center();	
		}

		//show full overlay so users knows to zoom if they want.
		if ( ajax_response.iTotalRecords > 50) {
			this.show_full();
		} else {
			this.hide_full();
		}
	} else {
		this.show_empty();
	}
	this.hide_loading();
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
	var marker_options = {};
	//bind the listing data to the marker so it can be used later
	marker_options.listing = listing;
	marker_options.animation = google.maps.Animation.DROP
	marker_options.position = new google.maps.LatLng(listing['location']['coords'][0], listing['location']['coords'][1]);

	if (listing['images'] && listing['images'][0] && listing['images'][0]['url']) {
    	var image_url = listing['images'][0]['url'];
    };
    marker_options.content = '<div id="content">'+
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
    this.create_marker( marker_options );
}

Map.prototype.create_marker = function ( marker_options ) {
	var that = this;
	if (this.marker.icon) {
		marker_options.icon = this.marker.icon;
	}
	var marker = new google.maps.Marker(marker_options);

	marker.listing = marker_options.listing;
	
	var infowindow = new google.maps.InfoWindow({content: marker_options.content});
	this.infowindows.push(infowindow);

	google.maps.event.addListener(marker, 'click', function() {
		that.marker_click( marker.listing.id );

		for (var i = that.infowindows.length - 1; i >= 0; i--) {
			that.infowindows[i].setMap(null)
		}
		infowindow.open( that.map, marker );
	});

	google.maps.event.addListener(marker,"mouseover",function(){
		that.marker_mouseover( marker.listing.id );
		if ( that.list ) {
			that.list.row_mouseover( marker.listing.id );	
		}
		
	}); 

	google.maps.event.addListener(marker,"mouseout",function(){
		that.marker_mouseout( marker.listing.id );
		if ( that.list ) {
			that.list.row_mouseleave( marker.listing.id );
		}
	});

	that.markers.push(marker);
	that.markers_hash[marker.listing.id] = marker;
	marker.setMap(this.map);
}

Map.prototype.center = function () {
	var that = this;
	var bounds = new google.maps.LatLngBounds();
	
	//only reposition the map if it's not the first load (this.bounds) and the dev wants (this.filter_by_bounds)
	if ( this.markers.length > 0 && ( !this.filter_by_bounds || !this.bounds ) ) {
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

Map.prototype.listeners = function ( ) {
	var that = this;
	var timeout = false;
	google.maps.event.addDomListener(window, 'load', function() {
		//trigger a reload on any movement
		google.maps.event.addListener(that.map, 'bounds_changed', function() {
			//only reload the map once since bounds_changed is a little trigger happy
			clearTimeout(timeout);
			timeout = setTimeout(function () {
				google.maps.event.addListenerOnce(that.map, 'idle', function() {
					that.listings.get();				
				});
			}, 500);	
		});
	});
}

Map.prototype.show_empty = function () {
	jQuery('.map_wrapper #empty_overlay').show();
}
Map.prototype.hide_empty = function () {
	jQuery('.map_wrapper #empty_overlay').hide();
}
Map.prototype.show_loading = function () {
	jQuery('.map_wrapper #loading_overlay').show();
}
Map.prototype.hide_loading = function () {
	jQuery('.map_wrapper #loading_overlay').hide();
}
Map.prototype.show_full = function () {
	jQuery('#full_overlay').fadeIn();
}
Map.prototype.hide_full = function () {
	jQuery('#full_overlay').hide();
}