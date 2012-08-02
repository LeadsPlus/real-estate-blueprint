function List () {}

List.prototype.sEcho = 1;

List.prototype.init = function ( params ) {
	var that = this;
	//list settings
	this.loading_class = params.loading_class || '.dataTables_processing';
	this.dom_id = params.dom_id || '#placester_listings_list';
	this.class = params.class || false;
	this.settings = params.settings || { "bFilter": false, "bProcessing": true, "bServerSide": true, "sServerMethod": "POST", 'sPaginationType': 'full_numbers', "sAjaxSource": info.ajaxurl };
	this.table_row_selector = params.table_class || '.placester_properties tr';
	this.context = params.context || false;
	this.total_results_id = params.total_results_id || '#pls_num_results';
	this.num_results = params.num_results || 10;

	//objects
	this.listings = params.listings || alert('You need to include a listings object');
	this.map = params.map || false;

	//empty settings
	this.hide_on_empty = params.hide_on_empty || true;
	this.empty_id = params.empty_id || false;

	this.settings.fnServerData = function ( sSource, aoData, fnCallback ) {
		if (params.get_listings) {
			params.get_listings( that, sSource, aoData, fnCallback )
		} else {
			that.get_listings( that, sSource, aoData, fnCallback )
		};
	}
}

List.prototype.get_listings = function ( self, sSource, aoData, fnCallback ) {
	var that = self;
	that.show_loading();
	that.fnCallback = fnCallback;
	that.listings.get();

}

List.prototype.update = function (ajax_response) {
	this.total_results(ajax_response);
	this.fnCallback(ajax_response);
	this.update_favorites_through_cache();
	this.hide_loading();
	console.log(ajax_response.aaData);
	if (ajax_response.aaData.length > 0) {
		this.hide_empty();
	} else {
		this.show_empty();
	}
}

List.prototype.total_results = function ( ajax_response ) {
	jQuery(this.total_results_id).html(ajax_response.iTotalDisplayRecords);
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

List.prototype.row_mouseover = function ( listing_id ) {
	jQuery(this.table_row_selector).find('[data-listing=' + listing_id + ']').trigger('mouseenter');
}

List.prototype.row_mouseleave = function ( listing_id ) {
	jQuery(this.table_row_selector).find('[data-listing=' + listing_id + ']').trigger('mouseleave');
}

List.prototype.listeners = function () {
	var that = this;
	if ( this.map ) {

		jQuery(this.table_row_selector).live({
			mouseenter: function () {
				jQuery(this).addClass('hover');
				that.map.marker_mouseover( jQuery(this).children().children().attr('data-listing') )
			},
			mouseleave: function () {
				jQuery(this).removeClass('hover');
				that.map.marker_mouseout( jQuery(this).children().children().attr('data-listing') )
			}
		});
	}

}

List.prototype.show_loading = function () {
	jQuery(this.loading_class).css('visibility', 'visible');
}

List.prototype.hide_loading = function () {
	jQuery(this.loading_class).css('visibility', 'hidden');
}

List.prototype.show_empty = function () {
	if (this.hide_on_empty)
		jQuery(this.dom_id).hide();

	if (this.empty_id)
		jQuery(this.empty_id).show();
}

List.prototype.hide_empty = function () {
	if (this.hide_on_empty)
		jQuery(this.dom_id).show();	

	if (this.empty_id)
		jQuery(this.empty_id).hide();
}