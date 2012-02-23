<?php 
PLS_Partials_Get_Listings_Ajax::init();
class PLS_Partials_Get_Listings_Ajax {
	

	/**
     * Returns the list of listings managed by ajax. It includes pagination and 
     * 'sort by' controls.
     * 
     * The defaults are as follows:
     *     'placeholder_img' - Defaults to placeholder image. The path to the 
     *          listing image that should be use if the listing has no images.
     *     'loading_img' - Defaults to the Wordpress spinner. Path to the 
     *          loader image.
     *     'image_width' - Defaults to 100. The with of the listing image.
     *     'crop_description' - Defaults to false. Wether the description 
     *     should be cropped or not.
     *     'context' - An execution context for the function. Used when the 
     *          filters are created.
     *     'context_var' - Any variable that needs to be passed to the filters 
     *          when function is executed.
     * Defines the following hooks:
     *      pls_listings_list_ajax_item_html[_context] - Filters html for each 
     *          item in the list
     *      pls_listings_list_ajax_no_results_html[_context] - Filters what 
     *          should be displayed when no results are found.
     *      pls_listings_list_ajax_html[_context] - Filters the html for the 
     *          whole list.
     *      pls_listings_list_ajax_sort_by_options[_context] - Filters the 
     *          options from the "Sort by" select box.
     *
     * @static
     * @param array $args Optional. Overrides defaults.
     * @return string The html and js.
     * @since 0.0.1
     */
    function init() {
        // Hook the callback for ajax requests
        add_action('wp_ajax_pls_listings_ajax', array(__CLASS__, 'get' ) );
        add_action('wp_ajax_nopriv_pls_listings_ajax', array(__CLASS__, 'get' ) );
        wp_register_script( 'get-listings-ajax', trailingslashit( PLS_JS_URL ) . 'scripts/get-listings-ajax.js' , NULL, NULL, true );
        wp_enqueue_script('get-listings-ajax');
    }

    function load() {
    
        ob_start();
        ?>
            <div id="container" style="width: 99%">
              <table id="placester_listings_list" class="widefat post fixed placester_properties" cellspacing="0">
                <thead>
                  <tr>
                    <th><span></span></th>
                  </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                  <tr>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
        <?php
        echo ob_get_clean();
    }

	function get ($args = array()) {	
        /** Display a placeholder if the plugin is not active or there is no API key. */
        if ( pls_has_plugin_error() && current_user_can( 'administrator' ) ) {
            return pls_get_no_plugin_placeholder( pls_get_merged_strings( array( $context, __FUNCTION__ ), ' -> ', 'post', false ) );
        } elseif ( pls_has_plugin_error() ) {
            return NULL;
        }
        
		/** Define the default argument array. */
        $defaults = array(
            'placeholder_img' => PLS_IMG_URL . "/null/listing-100x100.png",
            'loading_img' => admin_url( 'images/wpspin_light.gif' ),
            'image_width' => 100,
            'sort_type' => 'desc',
            'crop_description' => 0,
            'listings_per_page' => get_option( 'posts_per_page' ),
            'context' => '',
            'context_var' => NULL,
            'append_to_map' => true,
            'search_query' => $_POST
        );

        /** Extract the arguments after they merged with the defaults. */
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

        /** Get the listings list markup and javascript. */
        $api_response = PLS_Plugin_API::get_listings_list($search_query);
        

        $response = array();

        // Sorting
        $columns = array('images','location.address', 'location.locality', 'location.region', 'location.postal', 'zoning_types', 'purchase_types', 'listing_types', 'property_type', 'cur_data.beds', 'cur_data.baths', 'cur_data.price', 'cur_data.sqft', 'cur_data.avail_on');
        $_POST['sort_by'] = $columns[$_POST['iSortCol_0']];
        $_POST['sort_type'] = $_POST['sSortDir_0'];

        // Pagination
        $_POST['limit'] = $_POST['iDisplayLength'];
        $_POST['offset'] = $_POST['iDisplayStart'];     
        
        // build response for datatables.js
        $listings = array();
        foreach ($api_response['listings'] as $key => $listing) {
            if (empty($listing['images'])) {
                $listing['images'][0] = array('url' => $placeholder_img);
            }
            ob_start();
            // pls_dump($listing);
            ?>
            <div class="listing-item grid_8 alpha" id="post-<?php the_ID(); ?>">
                <header class="grid_8 alpha">
                    <h3><a href="<?php echo $listing['cur_data']['url']; ?>" rel="bookmark" title="<?php echo $listing['location']['address'] ?>"><?php echo $listing['location']['address'] . ', ' . $listing['location']['locality'] . ' ' . $listing['location']['region'] ?></a></h2>
                </header>
                <div class="listing-item-content grid_8 alpha">
                    <div class="grid_8 alpha">
                        <!-- If we have a picture, show it -->
                            <div class="listing-thumbnail">
                                <div class="outline">
                                    <?php echo PLS_Image::load($listing['images'][0]['url'], array('resize' => array('w' => 250, 'h' => 150), 'fancybox' => true, 'as_html' => true)); ?>
                                </div>
                            </div>

                        <div class="basic-details">
                            <p>Beds: <?php echo @$listing['cur_data']['beds']; ?></p>
                            <p>Baths: <?php echo @$listing['cur_data']['baths']; ?></p>
                            <p>Half Baths: <?php echo @$listing['cur_data']['half_baths']; ?></p>
                            <p>Price: <?php echo @$listing['cur_data']['price']; ?></p>
                            <p>Available On: <?php echo @$listing['cur_data']['avail_on']; ?></p>
                        </div>

                        <div class="listing-description">
                            <?php echo substr($listing['cur_data']['desc'], 0, 300); ?>
                        </div>
                        <div class="actions">
                            <a class="more-link" href="<?php echo $listing['cur_data']['url']; ?>">View Property Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $listings[$key][] = ob_get_clean();
        }

        // Required for datatables.js to function properly.
        $response['sEcho'] = $_POST['sEcho'];
        $response['aaData'] = $listings;
        $response['iTotalRecords'] = $api_response['total'];
        $response['iTotalDisplayRecords'] = $api_response['total'];
        echo json_encode($response);

        //wordpress echos out a 0 randomly. die prevents it.
        die();


        pls_h(
            'article',
            array( 'class' => 'pls-listing clearfix' ),
            pls_h_a( $listings_placeholders['url'], pls_h_img( $listings_placeholders['image_url'], $listings_placeholders['address'], array( 'width' => $image_width ) ) ) . 
            pls_h(
                'section',
                array( 'class' => 'info' ),
                pls_h( 
                    'h5',
                    pls_h_a(
                        $listings_placeholders['url'],
                        pls_h_span( $listings_placeholders['address'], array( 'class' => 'address' ) ) . ', ' .
                        pls_h_span( $listings_placeholders['city'], array( 'class' => 'city' ) ) . ', ' .
                        pls_h_span( $listings_placeholders['state'], array( 'class' => 'state' ) )
                    )
                ) .
                pls_h_div(
                    $listings_placeholders['description'],
                    array( 'class' => 'description' )
                ) . 
                pls_h_div(
                    '$' . $listings_placeholders['price'],
                    array( 'class' => 'price' )
                ) .
                pls_h_a( $listings_placeholders['url'], __( 'See More Details', pls_get_textdomain() ), array( 'class' => 'more' ) )
            )
        );

        

        //here!!!!!!!!!!!!
            


        /** Filter the listing item html. */
        $listing_item_html = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_item_html", $context ), '_', 'pre', false ), htmlspecialchars_decode( $listing_item_html ), $listings_placeholders, $context_var );

        ksort( $listing_html );

        /** Set the options for the "Sort by" select. */
        $sort_by_allowed_fields = PLS_Plugin_API::get_property_list_fields( 'sort_by' );
        $sort_by_options = array();
        foreach ( $sort_by_allowed_fields as $sort_field => $label ) {
            $sort_by_options["{$sort_field} {$sort_type}"] = $label;
        }

        /** Filter the "Sort by" options. */
        $sort_by_options = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_sort_by_options", $context ), '_', 'pre', false ), $sort_by_options, $context_var );

        /** Define the "Sort by" select html. */
        $sort_by_html = pls_h_div(
            pls_h('label','Sort By: ') . 
            pls_h('select', array( 'id' => 'sort-by' ),
                pls_h_options( $sort_by_options, 'bathrooms asc' )
                ),
            array('class' => 'sort_by_wrapper')
        );
        $sort_by_html .= "<div class='clear'></div>";

        
        /** The loader div html. */
        $loader_html = pls_h_div(
            pls_h_img( $loading_img ) .
            pls_h( 'span', __( 'Loading', pls_get_textdomain() ) ),
            array( 'id' => 'loader' )
        );

        /** The pagination html. */
        $pagination_html = pls_h_div(
            pls_h_a( "#", __( 'Prev', pls_get_textdomain() ), array( 'class' => 'prev-btn' ) ).
            pls_h_a( "#", __( 'Next', pls_get_textdomain() ), array( 'class' => 'next-btn' ) ),
            array( 'id' => 'listings-pagination', 'class' => 'pagination' )
        );

        /** What should be displayed if no results are found. */
        $no_results_html = pls_h(
            'section',
            array( 'class' => 'no-results' ),
            pls_h( 'h5', __( 'No results', pls_get_textdomain() ) ) .
            pls_h_p( __( 'Sorry, no listings match that search. Maybe try somthing a bit broader?' , pls_get_textdomain() ) )
        );

        /** Filter the no results html. */
        $no_results_html = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_no_results_html", $context ), '_', 'pre', false ), $no_results_html, $context_var );

        /** Filter the concatenated html. This allows developers to wrap the components in different markup and change their order. */
        // $return = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_html", $context ), '_', 'pre', false ), $sort_by_html . $loader_html . $listings_list . $pagination_html, $listings_list, $sort_by_html, $loader_html, $pagination_html );
				// Sort-by removed
        $return = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_html", $context ), '_', 'pre', false ), $loader_html . $listings_list . $pagination_html, $listings_list, $loader_html, $pagination_html );

        /** Append the extra javascript and return. */
        return $return . $row_rendering_js;
	}

}//end of class