jQuery(document).ready(function($) {

	$('.featured-listings').live('click', function(event) {
		event.preventDefault();
		$('#featured-listing-wrapper').dialog({width: 850});
	});



	function init_featured_picker() {
		//load two datatables

	}


	
});