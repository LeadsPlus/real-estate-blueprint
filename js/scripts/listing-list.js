function List () {}

List.prototype.init = function ( params ) {
	var that = this;
	this.dom_id = params.dom_id;
	this.filter = params.filter || false;
	this.map = params.map || false;
	this.class = params.class || false;

	this.settings = params.settings || { "bFilter": false, "bProcessing": true, "bServerSide": true, "sServerMethod": "POST", 'sPaginationType': 'full_numbers', "sAjaxSource": info.ajaxurl };
	this.settings.fnServerData = function (  sSource, aoData, fnCallback  ) {
		console.log(this.dom_id);
		
		// if (that.filter) {
		// 	aoData = that.filter.update( aoData );	
		// };
		aoData.push( { "name": "action", "value" : "pls_listings_ajax"} );
	    jQuery.ajax({
	        "dataType" : 'json',
	        "type" : "POST",
	        "url" : sSource,
	        "data" : aoData,
	        "success" : function(ajax_response) {
	            if (ajax_response && ajax_response['aaData']) {

	                that.total_results(ajax_response);
	                
	                if (typeof pls_google_map !== 'undefined') {
	                pls_clear_markers(pls_google_map);
	                
	                  if (typeof window['google'] != 'undefined') {
	                    for (var listing in ajax_response['aaData']) {
	                        var listing_json = ajax_response['aaData'][listing][1];
	                        pls_create_listing_marker(listing_json, pls_google_map);
	                    }
	                  }
	                }
	              };
	            //required to load the datatable
	           fnCallback(ajax_response);
	           that.update_favorites_through_cache();
	        }
	    });
	}
	this.datatable = jQuery(this.dom_id).dataTable(this.settings);

	if (this.filter) {
		jQuery(this.filter.class + ', #sort_by, #sort_dir').live('change submit', function(event) {
	        event.preventDefault();
	        my_listings_datatable.fnDraw();
	    });	
	};
	
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