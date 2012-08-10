jQuery(document).ready(function($) {

	$('.featured-listings').live('click', function(event) {
		event.preventDefault();
		$('#featured-listing-wrapper').dialog({width: 850});
		init_featured_picker();
	});



	function init_featured_picker() {
		//load two datatables
		$('#datatable_search_results').dataTable({
			"bFilter": false,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            'sPaginationType': 'full_numbers',
            'sDom': '<"dataTables_top"pi>lftpir',
            "sAjaxSource": ajaxurl, 
            "aoColumns" : [
                { sWidth: '300px' },    //address
                { sWidth: '50px' },    //add
            ], 
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value" : "list_options"} );
            }
		});
		$('#datatable_featured_listings').dataTable();
	}


	
});