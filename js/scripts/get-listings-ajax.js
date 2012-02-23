$(document).ready(function($) {
    var my_listings_datatable = $('#placester_listings_list').dataTable( {
        "bFilter": false,
        "bProcessing": true,
        "bServerSide": true,
        "sServerMethod": "POST",
        'sPaginationType': 'full_numbers',
        'sDom': '<"dataTables_top"pi>lftpir',
        "sAjaxSource": info.ajaxurl, //wordpress url thing 
        "fnServerParams": function ( aoData ) {
            aoData.push( { "name": "action", "value" : "pls_listings_ajax"} );
            aoData = my_listings_search_params(aoData);
        }
    });

    // prevents default on search button
    $('#pls_admin_my_listings').live('change', function(event) {
        event.preventDefault();
        my_listings_datatable.fnDraw();
    });

    // parses search form and adds parameters to aoData
    function my_listings_search_params (aoData) {
        $.each($('#pls_admin_my_listings:visible').serializeArray(), function(i, field) {
            aoData.push({"name" : field.name, "value" : field.value});
        });
        return aoData;
    }
});