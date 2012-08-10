jQuery(document).ready(function($) {

	$('.featured-listings').live('click', function(event) {
		event.preventDefault();
		$('#featured-listing-wrapper').dialog({width: 850});
		init_featured_picker();
	});



	function init_featured_picker() {
		//load two datatables
		$('#datatable_search_results').dataTable();
		$('#datatable_featured_listings').dataTable();
	}


	
});