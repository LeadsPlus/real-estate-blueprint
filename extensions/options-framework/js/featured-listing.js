jQuery(document).ready(function($) {

	var search_datatable;
	var featured_datatable;

	$('.featured-listings').live('click', function(event) {
		event.preventDefault();
		$('#featured-listing-wrapper').dialog({width: 850});
		init_featured_picker();
	});



	function init_featured_picker() {
		//load two datatables
		search_datatable = $('#datatable_search_results').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            'sPaginationType': 'full_numbers',
            "sAjaxSource": ajaxurl, 
            "aoColumns" : [
                { sWidth: '300px' },    //address
                { sWidth: '50px' },    //add
            ], 
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value" : "list_options"} );
            }
		});
		featured_datatable = $('#datatable_featured_listings').dataTable({
			"aoColumns" : [
                { sWidth: '300px' },    //address
                { sWidth: '50px' },    //remove
            ]
		});
	}

	$('#pls_add_option_listing').live('click', function(event) {
		event.preventDefault();
		var listing_id = $(this).attr('ref');
		var cells = $(this).parent().parent().children('td');
		var address = $(cells[0]).html();
		featured_datatable.fnAddData( [address, '<a id="pls_remove_option_listing" href="#" ref="' + listing_id + '">Remove</a>']);
	});

	$('#pls_remove_option_listing').live('click', function(event) {
		event.preventDefault();
		featured_datatable.fnDeleteRow($(this).closest("tr").get(0));
	});
});