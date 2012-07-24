function Filters () {}

Filters.prototype.init = function ( params ) {
	this.filters = {};
	this.dom_id = params.dom_id || false;
	this.list = params.list || false;
	this.map = params.map || false;
	this.listing = params.listing || false;
	this.class = params.class || 'pls_search_form_listings';
	
	// if (params.listeners) {
	// 	this.listeners.elements = params.listeners.elements || this.filter.class + ', #sort_by, #sort_dir'
	// 	this.listeners.events = params.listeners.events || 'change submit';	
	// }
	console.log('filter init');
}

Filters.prototype.listeners = function () {
	var that = this;
	jQuery(this.listeners.elements).live(this.listeners.events, function(event) {
        event.preventDefault();
        that.update();
    });	
}

Filters.prototype.get_values = function () {
	
	var result = {};
	jQuery.each(jQuery('.'+ this.class +', .sort_wrapper').serializeArray(), function(i, field) {
		result[field.name] = field.value;
    });
	return result;
	
}

Filters.prototype.verticies = function () {

}


// function my_listings_search_params (aoData) {
            //     var results = get_search_filters();
            //     console.log(results);
            //     for (filter in results) {
            //         aoData.push({"name" : filter, "value" : results[filter]});
            //     }
            //     aoData.push({"name": "context", "value" : $('#context').attr('class')});
            //     return aoData;
            // }