$(document).ready(function($) {
    var markers = [];
    var my_listings_datatable = $('#placester_listings_list').dataTable( {
        "bFilter": false,
        "bProcessing": true,
        "bServerSide": true,
        "sServerMethod": "POST",
        'sPaginationType': 'full_numbers',
        "sAjaxSource": info.ajaxurl, //wordpress url thing
        "fnServerData": function ( sSource, aoData, fnCallback ) {
            aoData.push( { "name": "action", "value" : "pls_listings_ajax"} );
            aoData = my_listings_search_params(aoData);
            $.ajax({
                "dataType" : 'json',
                "type" : "POST",
                "url" : sSource,
                "data" : aoData,
                "success" : function(ajax_response) {
                    if (ajax_response && ajax_response['aaData']) {
                        for (var current_marker in markers) {
                          markers[current_marker].setMap(null);
                        }
                        markers = [];
                        var bounds = new google.maps.LatLngBounds();
                        for (var listing in ajax_response['aaData']) {
                            var listing_json = ajax_response['aaData'][listing][1];
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(listing_json['location']['coords'][0], listing_json['location']['coords'][1]),
                                map: pls_google_map,
                            });
                            marker.setMap(pls_google_map);
                            bounds.extend(marker.getPosition());
                            markers.push(marker);
                        }
                        pls_google_map.fitBounds(bounds);
                    };

                    //required to load the datatable
                   fnCallback(ajax_response)
                }
            });
        } 
    });

    // prevents default on search button
    $('#pls_search_form, #sort_by, #sort_dir').live('change', function(event) {
        event.preventDefault();
        my_listings_datatable.fnDraw();
    });

    // parses search form and adds parameters to aoData
    function my_listings_search_params (aoData) {
        $.each($('#pls_search_form:visible, .sort_wrapper').serializeArray(), function(i, field) {
            aoData.push({"name" : field.name, "value" : field.value});
        });
        return aoData;
    }

    //datepicker
    $("input#metadata-max_avail_on_picker, #metadata-min_avail_on_picker").datepicker({
            showOtherMonths: true,
            numberOfMonths: 2,
            selectOtherMonths: true
    });
});