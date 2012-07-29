// finish styling filters
// 


//show number of results on the map


//polygon map
	// allow users to "zoom out" after a polygon

//lifestyle map
//lifestyle polygon map

// only trigger map reload after 70% move in a direction
// only trigger map reload after a zoom out.
// show the number of total results on the map
// infowindow alternative

function Map () {}

Map.prototype.init = function ( params ) {
	//where ever you go, know who you are.
	var that = this;

	this.map = false;
	this.map_options = params.map_options || { zoom: that.zoom, mapTypeId: google.maps.MapTypeId.ROADMAP, mapTypeControl: false, streetViewControl: false, zoomControl: true, zoomControlOptions: { style: google.maps.ZoomControlStyle.SMALL, position: google.maps.ControlPosition.RIGHT_TOP } };
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
	this.always_center = params.always_center || true;

	//map status box
	this.status_display = params.status_display || {};
	this.filter_by_bounds = params.filter_by_bounds || true;
	this.filter_translation = params.filter_translation || {'metadata[beds]': "Beds: " };
	this.filters_display = params.filters_display || ['metadata[beds]'];
	this.filter_position = params.filter_position || google.maps.ControlPosition.LEFT_TOP;

	//marker settings
	this.marker = {}
	this.marker.icon = params.marker || false;
	this.marker.icon_hover = params.marker_hover || 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=|FF0000|000000'

	//polygon settings
	this.polygon = {};
	this.polygon.strokeColor = params.strokeColor || null;
	this.polygon.strokeOpacity  = params.strokeOpacity || null;
	this.polygon.strokeWeight  = params.strokeWeight || null;
	this.polygon.fillColor  = params.fillColor || null;
	this.polygon.fillOpacity  = params.fillOpacity || null;
	this.polygons = [];
	this.polygons_verticies = [];
	this.polygons_exclude_center = false;
	this.selected_polygon = params.selected_polygon || false;
	this.allow_polygons_to_clear = params.allow_polygons_to_clear || false;
	this.slug = params.slug || false;
	Map.prototype.polygon_click = params.polygon_click || function ( polygon ) {
		that.selected_polygon = polygon;
		that.polygons_exclude_center = true;
		that.always_center = true;
		that.listings.get();
		for (var i = that.polygons.length - 1; i >= 0; i--) {
			that.polygons[i].setOptions({fillOpacity: "0.4"});
		}
		polygon.setOptions({fillOpacity: "0.6"});
	}
	Map.prototype.polygon_mouseover = params.polygon_mouseover || function ( polygon ) {
		polygon.setOptions({fillOpacity: "0.9"});
	}
	Map.prototype.polygon_mouseout = params.polygon_mouseout || function ( polygon ) {
		polygon.setOptions({fillOpacity: "0.4"});
	}

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
		// map options are defined in init
		that.map_options.center = new google.maps.LatLng(that.lat, that.lng);
		that.map = new google.maps.Map(document.getElementById(that.dom_id), that.map_options);
		//sets the initial div for the map status display
		
		if (that.status_display)
			that.add_control_container();
		
		if ( that.type == 'polygon' ) {
			//all neighborhoods shown
			that.polygon_init();
		} else if ( that.type == 'neighborhood' ) {
			//a specified neighborhood is shown
			that.neighborhood_init();
		} else if ( that.type == 'lifestyle' ) {
			//show points of interests on the map.
			that.lifestyle_init();
		} else if ( that.type == 'lifestyle_polygon' ) {
			//show points of interests on the map, then do listings searches with them.
			that.polygon_lifestyle_init();
		}
	});
}

Map.prototype.polygon_init = function () {
	var that = this;

	

	if (this.slug) {
		//if a specific polygon, get it
		//then get the listings for it.

	} else {
		//if no polygon, get all
		var filters = {};
		filters.action = 'get_polygons_by_type';
		filters.type = 'neighborhood';

		jQuery.ajax({
		    "dataType" : 'json',
		    "type" : "POST",
		    "url" : info.ajaxurl,
		    "data" : filters,
		    "success" : function ( neighborhoods ) {
		    	if ( neighborhoods.length > 0) {
		    		for (var i = neighborhoods.length - 1; i >= 0; i--) {
		    			var polygon_options = that.process_neighborhood_polygon( neighborhoods[i] );

		    			var polygon = that.create_polygon( polygon_options );


		    		};
		    		that.center();
		    	} 
				
		    }
		});

		
		//wait for the user to click on one
		//then go get all the listings of the polygon clicked	
	}
	
	
	//then wait for something to happen.

}

//converts raw neighborhood polygon data into a useable GMaps polygon object
Map.prototype.process_neighborhood_polygon = function ( neighborhood ) {
	// console.log( neighborhood );
	var polygon_options = {};
	polygon_options.paths = [];
	polygon_options.label = neighborhood.name || false;
	polygon_options.tax = neighborhood.tax || false;

	polygon_options.strokeColor = this.polygon.strokeColor || neighborhood.settings.border.color;
	polygon_options.strokeOpacity = this.polygon.strokeOpacity || neighborhood.settings.border.opacity;
	polygon_options.strokeWeight = this.polygon.strokeWeight || neighborhood.settings.border.weight;
	polygon_options.fillColor = this.polygon.fillColor || neighborhood.settings.fill.color;
	polygon_options.fillOpacity = this.polygon.fillOpacity || neighborhood.settings.fill.opacity;

	if ( neighborhood.vertices.length > 0 ) {
		var bounds = new google.maps.LatLngBounds();
		for (var i = neighborhood.vertices.length - 1; i >= 0; i--) {
			var point = neighborhood.vertices[i];
			var gpoint = new google.maps.LatLng( point['lat'], point['lng'] );
			polygon_options.paths.push( gpoint );	
			//store the verticies directly so we can center the map without relooping the the polygons
			this.polygons_verticies.push( gpoint );
			bounds.extend( gpoint );
		}
		polygon_options.label_center = bounds.getCenter();
	}
	//so we can attach directly to the polygon object
	polygon_options.vertices = neighborhood.vertices;

	return polygon_options;
}

Map.prototype.create_polygon = function ( polygon_options ) {
	var that = this;
	var polygon = new google.maps.Polygon( polygon_options );
	//faster to travers native arrays then using google's getters. We'll risk the collision
	polygon.vertices = polygon_options.vertices;
	
	if ( polygon_options.label && polygon_options.label_center ) {
		new TxtOverlay( polygon_options.label_center, polygon_options.label, "polygon_text_area", this.map );	
	}
	
	polygon.setMap(this.map);
	this.polygons.push(polygon);
	
	google.maps.event.addListener(polygon, 'click', function() {
		that.polygon_click( polygon );
	});

	google.maps.event.addListener(polygon,"mouseover",function(){
		that.polygon_mouseover( polygon );
	}); 

	google.maps.event.addListener(polygon,"mouseout",function(){
		that.polygon_mouseout( polygon );
	});

}

Map.prototype.neighborhood_init = function () {

}
Map.prototype.lifestyle_init = function () {

}
Map.prototype.lifestyle_polygon_init = function () {

}

Map.prototype.update = function ( ajax_response ) {

	if (ajax_response && ajax_response.aaData) {
		if (this.markers.length > 0 )
			this.clear();

		for (var i = ajax_response.aaData.length - 1; i >= 0; i--) {
			this.create_listing_marker( ajax_response.aaData[i][1] );
		}
		// if filter by bounds, don't move the map, it's confusing
		if (this.always_center) {
			this.center();	
		}

		//show full overlay so users knows to zoom if they want.
		if ( ajax_response.iTotalRecords > 50) {
			this.show_full();
		} else {
			this.hide_full();
		}

		//displaying map status bars
		if ( this.status_display && this.listings.active_filters && this.map ) {
			if ( this.type == 'polygon' ) {
				this.update_filters_polygon();				
			} else if ( this.type == 'neighborhood' ) {
				this.update_filters_neighborhood();				
			} else if ( this.type == 'lifestyle' ) {
				this.update_filters_lifestyle();				
			} else if ( this.type == 'lifestyle_polygon' ) {
				this.update_filters_lifestyle_polygon();				
			} else if ( this.type == 'listings' )
				this.update_filters_listings();				
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

	if ( this.allow_polygons_to_clear && this.polygons ) {
		for (var i = this.polygons.length - 1; i >= 0; i--) {
			this.polygons[i].setMap( null );
		}
		this.polygons = [];
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


Map.prototype.update_filters_polygon = function () {
	console.log('here');

	//
	jQuery('#map_filter_area_wrapper').append('<span>A Title</span>');

	// this.update_control_container({});
}
Map.prototype.update_filters_neighborhood = function () {}
Map.prototype.update_filters_lifestyle = function () {}
Map.prototype.update_filters_lifestyle_polygon = function () {}
Map.prototype.update_filters_listings = function () {}

Map.prototype.add_filters = function ( ) {
	
	console.log(this.listings.active_filters);
	this.clear_controls();
	var filters = this.listings.active_filters;

	for (var i = filters.length - 1; i >= 0; i--) {

		if ( ( jQuery.inArray(filters[i].name, ['metadata[beds]']) === -1 ) || filters[i].value == "") {
			continue;
		}
			

		if (this.filter_translation[filters[i].name])
			filters[i].name = this.filter_translation[filters[i].name];

		var control_settings = {}
		control_settings.innerHTML = filters[i].name + filters[i].value;
		this.add_control( control_settings );
	};
}

Map.prototype.clear_controls = function ( ) {
	this.map.controls[ this.filter_position ].clear();
}

Map.prototype.add_control_container = function () {
	var that = this;
	var controlDiv = document.createElement('div');
	controlDiv.id = 'map_filter_area';
	controlDiv.className = 'map_filter_area';
	controlDiv.style.marginTop = '9px';
	controlDiv.style.marginLeft = '7px'; 
	controlDiv.style.padding = '5px';
	
	// Set CSS for the control border.
	var controlUI = document.createElement('div');
	controlUI.id = 'map_filter_area_wrapper';
	controlDiv.appendChild(controlUI);

	that.map.controls[ that.filter_position ].push(controlDiv);
}


Map.prototype.center = function () {
	var that = this;
	var listener = false;
	//only reposition the map if it's not the first load (this.bounds) and the dev wants (this.filter_by_bounds)
	if ( !this.filter_by_bounds || !this.bounds || this.selected_polygon) {
		clearTimeout(listener);

		var bounds = new google.maps.LatLngBounds();

		if ( this.markers.length > 0 ) {
			for (var i = this.markers.length - 1; i >= 0; i--) {
				bounds.extend(this.markers[i].getPosition());
			}
		}
		
		if ( !this.polygons_exclude_center && this.polygons_verticies.length > 0 ) {
			for (var i = this.polygons_verticies.length - 1; i >= 0; i--) {
				bounds.extend(this.polygons_verticies[i]);
			}
		}
		
        if ( this.map ) {
        	this.map.fitBounds(bounds);
            listener = setTimeout( function () {
				google.maps.event.addListener(that.map, 'bounds_changed', function( event ) {
				    if ( that.map.getZoom() > 15 ) {
				    	that.map.setZoom( 15 );
				    }
				})
            }, 750 );
        }
	}
}


Map.prototype.center_on_markers = function () {
	var bounds = new google.maps.LatLngBounds();

	for (var i = this.markers.length - 1; i >= 0; i--) {
		bounds.extend(this.markers[i].getPosition());
	}	

	this.map.fitBounds(bounds);
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

	if (this.type == 'polygon' && this.selected_polygon) {
		console.log(this.selected_polygon);
		for (var i = this.selected_polygon.vertices.length - 1; i >= 0; i--) {
			var point = this.selected_polygon.vertices[i];
			this.bounds.push({'name' : 'polygon[' + i + '][lat]', 'value': point['lat'] });
			this.bounds.push({'name' : 'polygon[' + i + '][lng]', 'value': point['lng'] });
		}
	} else {
		this.bounds.push({'name' : 'polygon[0][lat]', 'value': map_bounds.getNorthEast().lat() });
		this.bounds.push({'name' : 'polygon[0][lng]', 'value': map_bounds.getNorthEast().lng() });

		this.bounds.push({'name' : 'polygon[1][lat]', 'value': map_bounds.getNorthEast().lat() });
		this.bounds.push({'name' : 'polygon[1][lng]', 'value': map_bounds.getSouthWest().lng() });

		this.bounds.push({'name' : 'polygon[2][lat]', 'value': map_bounds.getSouthWest().lat() });
		this.bounds.push({'name' : 'polygon[2][lng]', 'value': map_bounds.getSouthWest().lng() });

		this.bounds.push({'name' : 'polygon[3][lat]', 'value': map_bounds.getSouthWest().lat() });
		this.bounds.push({'name' : 'polygon[3][lng]', 'value': map_bounds.getNorthEast().lng() });	
	}
	

	return this.bounds;
}

Map.prototype.listeners = function ( ) {
	var that = this;
	var timeout = false;

	if (this.type == 'listings') {
		google.maps.event.addDomListener(window, 'load', function() {
			//trigger a reload on any movement
			google.maps.event.addListener(that.map, 'bounds_changed', function() {
				//only reload the map once since bounds_changed is a little trigger happy
				clearTimeout(timeout);
				timeout = setTimeout(function () {
					google.maps.event.addListenerOnce(that.map, 'idle', function() {
						that.listings.get();				
					});
				}, 750);	
			});
		});	
	}
	
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