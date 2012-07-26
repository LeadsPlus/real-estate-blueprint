function Listings ( params ) {
	this.map = params.map || false;
	this.list = params.list || false;
	this.filter = params.filter || false;
	this.hook = params.hook || 'pls_listings_ajax';
	this.sSource = params.sSource || info.ajaxurl;
	this.aoData = params.aoData || [];
}

Listings.prototype.request = 0;

Listings.prototype.pending = false;

Listings.prototype.init = function () {
	var that = this;
	if (this.filter) {
		this.filter.listeners(function () {
			that.update();
		});
	}

	if (this.map) {
		this.map.listeners(this.get);
	}

	if (this.list) {
		this.list.listeners(this.get);

		//boot up the datatable
		this.list.datatable = jQuery(this.list.dom_id).dataTable(this.list.settings);
	}
}

Listings.prototype.get = function ( success ) {
	//if there's a pending request, do nothing.
	if ( Listings.prototype.pending ) {
		return;
	};
	this.pending = true;

	var that = this;
	var filters = [];

	//get pagination and sorting information
	if (this.list && this.list.datatable) {
		this.list.show_loading();
		var fnSettings = this.list.datatable.fnSettings();
		filters.push( { "name": "iDisplayLength", "value" : fnSettings._iDisplayLength } );
		filters.push( { "name": "iDisplayStart", "value" : fnSettings._iDisplayStart } );
		this.list.sEcho++
		filters.push( { "name": "sEcho", "value" :  this.list.sEcho} );	
		// aoData;
	} else if ( this.list ) {
		this.list.show_loading();
		filters.push( { "name": "iDisplayLength", "value" : 10} );
		filters.push( { "name": "iDisplayStart", "value" : 0} );
		filters.push( { "name": "sEcho", "value" : 1} );	
	}

	//get get current state of search filtes. 
	if (this.filter) {
		filters = filters.concat(this.filter.get_values());
	}

	//get bounding box or polygon information
	if (this.map) {
		console.log(this.map.get_bounds());
	}

	//tell wordpress which hook to hit.
	filters.push( { "name": "action", "value" : this.hook} );

	jQuery.ajax({
	    "dataType" : 'json',
	    "type" : "POST",
	    "url" : this.sSource,
	    "data" : filters,
	    "success" : function ( ajax_response ) {
			that.pending = false;		

			if (that.map)
				that.map.update( ajax_response );

			if ( that.list )
				that.list.update( ajax_response );

	    }
	});
}