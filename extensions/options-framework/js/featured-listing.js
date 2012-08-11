jQuery(document).ready(function($) {

	var search_datatable;
	var featured_datatable;
    init_featured_picker();

    var dialogWidth = 850;
    $('#featured-listing-wrapper').dialog({autoOpen: false,width: dialogWidth,position: [($(window).width() / 2) - (dialogWidth / 2), 50]});
	
    $('.featured-listings').live('click', function(event) {
		event.preventDefault();
        $('#featured-listing-wrapper').dialog('open');
		
        var calling_button_id = $(this).attr('id');

        //id of the save button in the dialog box
        $('#save-featured-listings').attr('class', calling_button_id);

        var listings_container = $(this).closest('.featured-listings-wrapper').find('div.featured-listings ul li');
        featured_datatable.fnClearTable();
        $(listings_container).each(function(event) {
            console.log(this);
            var address = $(this).children('div#pls-featured-text').html();
            var listing_id = $(this).children('div#pls-featured-text').attr('ref');
            featured_datatable.fnAddData( [address, '<a id="pls_remove_option_listing" href="#" ref="' + listing_id + '">Remove</a>']);
        });
        console.log(listings_container);

	});



	function init_featured_picker() {
		//load two datatables
		search_datatable = $('#datatable_search_results').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bFilter" : false,
            "sServerMethod": "POST",
            "sAjaxSource": ajaxurl, 
            "aoColumns" : [
                { sWidth: '260px' },    //address
                { sWidth: '100px' },    //add
            ], 
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value" : "list_options"} );
                // aoData.push( { "name": "sSearch", "value" : $('input#address_search').val() })
                aoData = options_filters(aoData);
                // console.log(aoData);
            }
		});
		featured_datatable = $('#datatable_featured_listings').dataTable({
            "bFilter" : false,
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

	$('#options-filters').live('change', function(event) {
        event.preventDefault();
        search_datatable.fnDraw();
    });

    $('#save-featured-listings').live('click', function(event) {
    	event.preventDefault();
    	save_options();
    });


    function options_filters (aoData) {
        $.each($('#options-filters').serializeArray(), function(i, field) {
            aoData.push({"name" : field.name, "value" : field.value});
        });
        return aoData;
    }

    function save_options () {
    	var listings_container;
        var featured_listings = '';
        featured_listings += '<ul>';
    	$('#datatable_featured_listings tr').each(function(event) {
    		var calling_id = '#' + $('#save-featured-listings').attr('class');
            listings_container = $(calling_id).closest('.featured-listings-wrapper').find('div.featured-listings');
            var option_name = $(listings_container).attr('id');
            var option_id = $(listings_container).attr('ref');

            var rows = $(this).find('td');
    		var address = $(rows[0]).html();
    		var id = $(rows[1]).find('a').attr('ref');

            if (address) {
                // featured_listings.push({'address' : address, 'listing_id': id});  
                featured_listings += '<li>';
                featured_listings += '<div id="pls-featured-text">' + address + '</div>';
                featured_listings += '<input type="hidden" name="' + option_name + '[' + option_id + '][' + id + ']=" value="' + address + '">';
                featured_listings += '</li>';
            }

    	});
        featured_listings += '</ul>';
        $(listings_container).html(featured_listings);
        $('#featured-listing-wrapper').dialog('close');
    }
});