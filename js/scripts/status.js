function Status_Window () {}

Status_Window.prototype.init = function ( params ) {

	this.listings = params.listings || alert('You must attach a listings object to you status object');

	console.log(this.listings.map);

	this.map = this.listings.map || alert('You need to attach a map to the listings object if you want the status object actually work');
	
	this.filter_position = params.fitler_position || google.maps.ControlPosition.LEFT_TOP;

	this.class = params.class || 'map_filter_area';
	this.dom_id = params.dom_id || 'map_filter_area';
	

	Status_Window.prototype.on_load = params.onload || function () {
		var that = this;
		//set the initial state of the polygon menu
		jQuery(function () {
			google.maps.event.addDomListenerOnce(that.map.map, 'idle', function() {
				var content = '<div id="polygon_display_wrapper">';
				content += '<h5>' + that.map.type + ' Search</h5>';
				if (that.map.type == 'polygon') {
					content += '<p id="start_warning">Select a polygon to start searching</p>';
				} else if ( that.map.type == 'listings' ) {
					content += '<p id="start_warning">Oh man, edge case</p>';
				}	
				
				content += '</div>';

				jQuery('#' + that.dom_id).append(content);
				

				jQuery('#polygon_unselect').live('click', function () {
					that.map.selected_polygon = false;
					that.map.listings.get();	
					that.map.center_on_polygons();			
					jQuery('#' + that.dom_id).append('<p id="start_warning">Select a polygon to start searching</p>');
				});
			});
		});
	}

	Status_Window.prototype.update = params.update || function () {
		jQuery(' #polygon_display_status').remove();
		
		var content = '<div id="polygon_display_status">';
		if (this.map.selected_polygon) {
			jQuery('#' + this.map.status_display.dom_id + ' #start_warning').remove();
			content += '<a id="polygon_unselect">Unselect Neighborhood</a>';
			content += '<div>Selected Neighborhood: ' + this.map.selected_polygon.label + '</div>';
			content += '<div>Number of Listings:' + this.map.listings.ajax_response.iTotalRecords + '</div>';
		}

		var formatted_filters = this.map.get_formatted_filters();
		if ( formatted_filters.length > 0 ) {
			content += '<ul>';
			for (var i = formatted_filters.length - 1; i >= 0; i--) {
				content += '<li>' + formatted_filters[i].name + formatted_filters[i].value + '</li>'
			};
			content += '</ul>';
		}

		content += '</div>';
		jQuery('#' + this.map.status_display.dom_id).append(content);
	}
}

Status_Window.prototype.get_formatted_filters = function ( ) {
	var filters = this.listings.active_filters;
	var formatted_filters = [];
	for (var i = filters.length - 1; i >= 0; i--) {

		if ( ( jQuery.inArray(filters[i].name, ['metadata[beds]']) === -1 ) || filters[i].value == "")
			continue;
			
		if (this.filter_translation[filters[i].name])
			filters[i].name = this.filter_translation[filters[i].name];

		formatted_filters.push({ name: filters[i].name, value: filters[i].value })
	}
	return formatted_filters;
}

Status_Window.prototype.add_control_container = function () {
	var that = this;
	var controlDiv = document.createElement('div');
	controlDiv.id = this.dom_id;
	controlDiv.className = this.class;
	controlDiv.style.marginTop = '9px';
	controlDiv.style.marginLeft = '7px'; 
	controlDiv.style.padding = '5px';
	
	// Set CSS for the control border.
	var controlUI = document.createElement('div');
	controlUI.id = 'map_filter_area_wrapper';
	controlDiv.appendChild(controlUI);

	that.map.map.controls[ that.filter_position ].push(controlDiv);
}