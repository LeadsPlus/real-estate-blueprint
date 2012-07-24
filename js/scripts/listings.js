function Listings ( params ) {
	this.map = params.map || false;
	this.list = params.list || false;
	this.filter = params.filter || false;
	this.hook = params.hook || 'pls_listings_ajax';
}

Listings.prototype.get = function ( sSource, aoData, success ) {
	
	//get get current state of search filtes. 
	if (this.filter) {
		console.log(this.filter.get_values());
	}

	//get bounding box or polygon information
	if (this.map) {
		console.log(this.filter.get_bounds());
	}

	//get pagination and sorting information
	if (this.list && this.list.datatable) {
		aoData
	}

	//tell wordpress which hook to hit.
	aoData.push( { "name": "action", "value" : this.hook} );

	jQuery.ajax({
	    "dataType" : 'json',
	    "type" : "POST",
	    "url" : sSource,
	    "data" : aoData,
	    "success" : success
	});
}
