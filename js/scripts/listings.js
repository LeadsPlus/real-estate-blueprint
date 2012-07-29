function Listings ( params ) {
	this.map = params.map || false;
	this.list = params.list || false;
	this.filter = params.filter || false;
	this.hook = params.hook || 'pls_listings_ajax';
	this.sSource = params.sSource || info.ajaxurl;
	this.aoData = params.aoData || [];
	this.active_filters = [];
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
		this.list.datatable = jQuery(this.list.dom_id).dataTable(this.list.settings);
	}
}

Listings.prototype.get = function ( success ) {
	//if there's a pending request, do nothing.
	if ( Listings.prototype.pending ) {
		return;
	};
	//or, if we're dealing with a polygon map and there's not a selected polygon
	if ( this.map.type == 'polygon' && !this.map.selected_polygon ) {
		if ( this.list )
			this.list.update( {'aaData' : [], 'iDisplayLength': 0, 'iDisplayStart': 0, 'sEcho': this.list.sEcho} )

		if ( this.map )
			this.map.update( {'aaData' : [], 'iDisplayLength': 0, 'iDisplayStart': 0, 'sEcho': this.list.sEcho} )

		return false;
	}
	this.pending = true;

	var that = this;
	that.active_filters = [];

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
		that.active_filters.push( { "name": "iDisplayLength", "value" : 10} );
		that.active_filters.push( { "name": "iDisplayStart", "value" : 0} );
		that.active_filters.push( { "name": "sEcho", "value" : 1} );	
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

	//tell wordpress which hook to hit.
	that.active_filters.push( { "name": "action", "value" : this.hook} );
	jQuery.ajax({
	    "dataType" : 'json',
	    "type" : "POST",
	    "url" : this.sSource,
	    "data" : that.active_filters,
	    "success" : function ( ajax_response ) {
			that.pending = false;		
			that.ajax_response = ajax_response;
			if (that.map)
				that.map.update( ajax_response );

			if ( that.list )
				that.list.update( ajax_response );

	    }
	});
}