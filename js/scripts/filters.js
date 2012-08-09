function Filters () {}

Filters.prototype.init = function ( params ) {
	this.filters = {};
	this.dom_id = params.dom_id || false;
	this.list = params.list || false;
	this.map = params.map || false;
	this.listings = params.listings || false;
	this.class = params.class || 'pls_search_form_listings';
	
	if (params.listener) {
		this.listener = {};
		this.listener.elements = params.listener.elements || this.class + ', #sort_by, #sort_dir'
		this.listener.events = params.listener.events || 'change submit';	
	} else {
		this.listener = {};
		this.listener.elements = this.class + ', #sort_by, #sort_dir'
		this.listener.events = 'change submit';	
	}
}

Filters.prototype.listeners = function (callback) {
	var that = this;
	jQuery(this.listener.elements).live(this.listener.events, function(event) {
        event.preventDefault();
        that.listings.get();
    });	
}

Filters.prototype.get_values = function () {
	var result = [];
	jQuery.each(jQuery(this.listener.elements).serializeArray(), function(i, field) {
		result.push({'name' : field.name, 'value' : field.value});
    });
	return result;
	
}