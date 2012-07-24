function Filters () {}

Filters.prototype.init = function ( params ) {
	this.dom_id = params.dom_id || false;
	this.list = params.filter || false;
	this.map = params.map || false;
	this.class = params.class || false;
}

Filters.prototype.update = function () {
	var result = {};
	if (this.map) {
		result.push(this.verticies());	
	};
	
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