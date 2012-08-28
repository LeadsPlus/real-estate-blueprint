function Listings ( params ) {
	this.map = params.map || false;
	this.list = params.list || false;
	this.filter = params.filter || false;
	this.hook = params.hook || 'pls_listings_ajax';
	this.sSource = params.sSource || info.ajaxurl;
	this.aoData = params.aoData || [];
	this.active_filters = [];
	this.single_listing = params.single_listing || false;
	this.default_filters = params.default_filters || [];
	this.filter_override = params.filter_override || false;
}

Listings.prototype.pending = false;

Listings.prototype.init = function () {
	var that = this;

	if (this.filter) {
		this.filter.listeners(function () {
			that.update();
		});
	}

	if (this.map) {
		this.map.listeners();
	}

	if (this.list) {
		this.list.listeners();

		//boot up the datatable
		if ( this.map.filter_by_bounds ) {
			google.maps.event.addDomListenerOnce(window, 'load', function() {
				google.maps.event.addDomListenerOnce(that.map.map, 'idle', function() {
					that.list.datatable = jQuery(that.list.dom_id).dataTable(that.list.settings);			
				});
			});
		} else {
			this.list.datatable = jQuery(this.list.dom_id).dataTable(this.list.settings);	
		}
		
	}

	if ( this.single_listing ) {
		this.get();
	}
}

Listings.prototype.get = function ( success ) {
	//if there's a pending request, do nothing.
	if ( Listings.prototype.pending ) {
		return;
	};

	//if there's a single listing, always return that
	if ( this.map.type == 'single_listing' ) {
		this.map.update( {'aaData' : [['', this.single_listing]], 'iDisplayLength': 0, 'iDisplayStart': 0, 'sEcho': this.list.sEcho} );
		return false;
	}

	//or, if we're dealing with a polygon map and there's not a selected polygon
	if ( ( this.map.type == 'neighborhood' && !this.map.selected_polygon && !this.map.neighborhood.neighborhood_override ) ) {
		if ( this.list )
			this.list.update( {'aaData' : [], 'iDisplayLength': 0, 'iDisplayStart': 0, 'sEcho': this.list.sEcho} )

		if ( this.map )
			this.map.update( {'aaData' : [], 'iDisplayLength': 0, 'iDisplayStart': 0, 'sEcho': this.list.sEcho} )

		return false;
	}
	this.pending = true;

	var that = this;

	if (that.default_filters.length > 0) {
		that.active_filters = that.default_filters;
	}

	//get pagination and sorting information
	if (this.list && this.list.datatable) {
		this.list.show_loading();
		var fnSettings = this.list.datatable.fnSettings();
		that.active_filters.push( { "name": "iDisplayLength", "value" : fnSettings._iDisplayLength } );
		that.active_filters.push( { "name": "iDisplayStart", "value" : fnSettings._iDisplayStart } );
		this.list.sEcho++
		that.active_filters.push( { "name": "sEcho", "value" :  this.list.sEcho} );	
		// aoData;
	} else if ( this.list ) {
		this.list.show_loading();
		that.active_filters.push( { "name": "iDisplayLength", "value" : this.list.limit_default} );
		that.active_filters.push( { "name": "iDisplayStart", "value" : 0} );
		that.active_filters.push( { "name": "sEcho", "value" : 1} );	
	}

	if (this.list && this.list.context) {
		that.active_filters.push( { "name": "context", "value" : this.list.context} );
	}
  
	//get get current state of search filtes. 
	if (this.filter) {
		that.active_filters = that.active_filters.concat(this.filter.get_values());
	}

	//get bounding box or polygon information
	if (this.map) {
		this.map.show_loading();
		that.active_filters = that.active_filters.concat(this.map.get_bounds());
	}

	if (that.filter_override) {
		for (var i = that.filter_override.length - 1; i >= 0; i--) {
			that.active_filters.push(that.filter_override[i]);
		};
	};
	that.active_filters.push( { "name": "action", "value" : this.hook} );
	
	jQuery.ajax({
	    "dataType" : 'json',
	    "type" : "POST",
	    "url" : this.sSource,
	    "data" : that.active_filters,
	    "success" : function ( ajax_response ) {
			that.pending = false;		
			that.ajax_response = ajax_response;
			if (that.map && that.map.map)
				that.map.update( ajax_response );

			if ( that.list )
				that.list.update( ajax_response );

	    }
	});
}