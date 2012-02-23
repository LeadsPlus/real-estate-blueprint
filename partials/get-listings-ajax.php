<?php 

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
	function init ($args = '') {
			
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
            'append_to_map' => true
        );

        /** Extract the arguments after they merged with the defaults. */
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

        /** The crop settings. [start, length, sentence/word/midword crop, suffix]. */
        $crop_settings = array( 0, 200, 2, '.' );

        /** Set the description cropping settings and filter them. */
        $crop_description = $crop_description ? $crop_settings : $crop_description; 
        $crop_description = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_description_crop", $context ), '_', 'pre', false ), $crop_description, $context_var );

        /** Get the listings list markup and javascript. */
        $listings_list = PLS_Plugin_API::get_listings_list(
            array(
                'table_type' => 'html',
                'sort_by' => 'price',
								'sort_type' => 'desc',
                'js_row_renderer' => 'placesterListLone_createRowHtml',
                'loading' => array (
                    'render_in_dom_element' => 'loader'
                ),
                'pager' => array(
                    'render_in_dom_element' => 'listings-pagination',
                    'rows_per_page' => $listings_per_page, 
                    'css_current_button' => 'current',
                    'css_not_current_button' => '',
                    'first_page' => array( 'visible' => false, 'label' => 'First' ),
                    'previous_page' => array( 'visible' => true, 'label' => 'Prev' ),
                    'numeric_links' => array(
                        'visible' => false, 
                        'max_count' => 10,
                        'more_label' => __( 'More...', pls_get_textdomain() ),
                        'css_outer' => 'pager_numberic_block'
                    ),
                    'next_page' => array(
                        'visible' => true,
                        'label' => __( 'Next', pls_get_textdomain() ) 
                    ),
                    'last_page' => array (
                        'visible' => false,
                        'label' => __( 'Last', pls_get_textdomain() ) 
                    )
                ),
                'attributes' => array (
                    'bathrooms',
                    'half_baths',
                    'bedrooms',
                    'price',
                    'images',
                    'description',
                    'url',
                    'location.city',
                    'location.state',
                    'location.address',
                    'location.zip',
                    'location.coords.latitude',
                    'location.coords.longitude',
                    'id',
                    'available_on',
                    'amenities'
                ), 
                'crop_description' => $crop_description,
            )
        );

        /** Display a placeholder if the plugin is not active or there is no API key. */
        if ( pls_has_plugin_error() && current_user_can( 'administrator' ) )
            return pls_get_no_plugin_placeholder( pls_get_merged_strings( array( $context, __FUNCTION__ ), ' -> ', 'post', false ) );

        /** Return nothing when no plugin and user is not admin. */
        if ( pls_has_plugin_error() )
            return NULL;

        /** Define an array with placeholders to be passed through the filter. */
        $listings_placeholders = array(
            'url' => 'LISTING_URL',
            'available_on' => 'LISTING_AVAILABLE_ON',
            'description' => 'LISTING_DESCRIPTION',
            'image_url' => 'LISTING_IMAGE_URL',
            'address' => 'LISTING_ADDRESS',
            'city' => 'LISTING_CITY',
            'state' => "LISTING_STATE",
            'bedrooms' => "LISTING_BEDROOMS",
            'bathrooms' => "LISTING_BATHROOMS",
            'half_baths' => "LISTING_HALF_BATHS",
            'price' => "LISTING_PRICE",
            'zip' => "LISTING_ZIP",
            'amenities' => "LISTING_AMENITIES",
        );
        ksort( $listings_placeholders );

        /** Create the listing html template used when rendering the listings list. */
        $listing_item_html = pls_h(
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

        /** Filter the listing item html. */
        $listing_item_html = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_item_html", $context ), '_', 'pre', false ), htmlspecialchars_decode( $listing_item_html ), $listings_placeholders, $context_var );

        /**
         * Define the replacements for each placeholder. 
         * Needs to have the same keys as $listings_placeholders.
         */
        $listing_html = array(
            'url' => "' + row.url + '",
            'available_on' => "' + row.available_on + '",
            'description' => "' + row.description + '",
            'image_url' => "' + image + '",
            'address' => "' + row.location.address + '",
            'city' => "' + row.location.city + '",
            'state' => "' + row.location.state + '",
            'bedrooms' => "' + row.bedrooms + '",
            'bathrooms' => "' + row.bathrooms + '",
            'half_baths' => "' + row.half_baths + '",
            'price' => "' + row.price + '",
            'zip' => "' + row.zip + '",
            'amenities' => "' + row.amenities + '",
        );
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

        /** Start outbuffering to save the js that deals with rendering the listings, and with the sort event. */
        ob_start();
?>
<script>
        <?php /** This function is needed by the javascript in $listings_list. */ ?>
        function placesterListLone_createRowHtml( row ) {
            <?php /** Placeholder image. */ ?>
            var null_image = "<?php echo $placeholder_img; ?>";
            <?php /** Get the first image if it exists, user placeholder otherwise. */ ?>
            if ( row.images.length > 0 ) {
                var images_array = ( '' + row.images ).split( ',' );
                var image = '';
                if ( images_array.length > 0 && images_array[0].length > 0 ) 
                    image = images_array[0];
            } else {
                var image = null_image;
            };

            <?php if ($append_to_map) {
                ?>
                    
                    pls_js_add_marker(row);

                    if(typeof pls_js_render_markers == 'function') { 
                        pls_js_render_markers(); 
                    }
                
                <?php
            }; ?>

            return '<?php echo str_replace( $listings_placeholders, $listing_html, $listing_item_html ); ?>';
        };
        <?php /** Set on change event for the listings sorter. */ ?>
        $( '#sort-by' ).change(function() {
            var v = $( '#sort-by' ).val();
            a = v.split(' ');
            placesterListLone_setSorting( a[0], a[1] );
        });
        <?php /** Set the no results text. */ ?>
        function custom_empty_listings_loader( dom_object ) {
            var empty_property_search = '<?php echo $no_results_html; ?>';
            dom_object.html( empty_property_search );
        }
</script>
<?php 
        /** Save the buffered javascript. */
        $row_rendering_js = ob_get_clean();

        /** Filter the concatenated html. This allows developers to wrap the components in different markup and change their order. */
        // $return = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_html", $context ), '_', 'pre', false ), $sort_by_html . $loader_html . $listings_list . $pagination_html, $listings_list, $sort_by_html, $loader_html, $pagination_html );
				// Sort-by removed
        $return = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_html", $context ), '_', 'pre', false ), $loader_html . $listings_list . $pagination_html, $listings_list, $loader_html, $pagination_html );

        /** Append the extra javascript and return. */
        return $return . $row_rendering_js;
	}

}//end of class