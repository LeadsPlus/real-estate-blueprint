function List () {}

List.prototype.sEcho = 1;

List.prototype.init = function ( params ) {
	var that = this;
	this.listings = params.listings || alert('You need to include a listings object');

	this.loading_class = params.loading_class || '.dataTables_processing'

	this.map = params.map || false;

	this.dom_id = params.dom_id || '#placester_listings_list';
	this.class = params.class || false;
	this.settings = params.settings || { "bFilter": false, "bProcessing": true, "bServerSide": true, "sServerMethod": "POST", 'sPaginationType': 'full_numbers', "sAjaxSource": info.ajaxurl };
	this.table_row_selector = params.table_class || '.placester_properties tr';

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
				jQuery(this).css('background-color', 'red');
				that.map.marker_mouseover( jQuery(this).children().children().attr('data-listing') )
			},
			mouseleave: function () {
				jQuery(this).css('background-color', 'inherit');
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