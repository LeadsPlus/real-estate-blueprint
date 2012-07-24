function List () {}

List.prototype.init = function ( params ) {
	console.log('list init')
	var that = this;
	this.listings = params.listings || alert('You need to include a listings object');

	this.dom_id = params.dom_id || '#placester_listings_list';
	this.class = params.class || false;
	this.settings = params.settings || { "bFilter": false, "bProcessing": true, "bServerSide": true, "sServerMethod": "POST", 'sPaginationType': 'full_numbers', "sAjaxSource": info.ajaxurl };
	
	this.settings.fnServerData = function ( sSource, aoData, fnCallback ) {
		if (params.get_listings) {
			params.get_listings( that, sSource, aoData, fnCallback )
		} else {
			that.get_listings( that, sSource, aoData, fnCallback )
		};
	}
	
	//boot up the datatable
	this.datatable = jQuery(this.dom_id).dataTable(this.settings);
}

List.prototype.get_listings = function (self, sSource, aoData, fnCallback ) {

	var that = self;

	that.listings.get( sSource, aoData, function (ajax_response) {
		that.total_results(ajax_response);
		fnCallback(ajax_response);
		that.update_favorites_through_cache();
	});

}

List.prototype.total_results = function ( ajax_response ) {
	jQuery(this.class +' #pls_num_results').html(ajax_response.iTotalDisplayRecords);
}

List.prototype.update_favorites_through_cache = function () {
	jQuery.post(info.ajaxurl, {action: 'get_favorites'}, function(data, textStatus, xhr) {
	        if (data) {
	            jQuery('#pl_add_remove_lead_favorites .pl_prop_fav_link').each(function(index) {
	                var flag = false;
	                for (var i = data.length - 1; i >= 0; i--) {
	                    //this listing should be a favorite
	                    if (jQuery(this).attr('href') == ('#' + data[i].id) ) {
	                        if (jQuery(this).attr('id') == 'pl_add_favorite') {
	                            jQuery(this).hide();
	                        } else {
	                            jQuery(this).show();
	                        };
	                        flag = true;
	                    } 
	                };
	                //this listing shouldn't be a favorite
	                if (!flag) {
	                    if (jQuery(this).attr('id') == 'pl_add_favorite') {
	                        jQuery(this).show();
	                    } else {
	                        jQuery(this).hide();
	                    };
	                };
	            });     
	        };
	    }, 'json');
} 