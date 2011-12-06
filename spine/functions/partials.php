<?php
/**
 *  Define wrapper functions for the PLS_Partials methods.
 *  This is so the theme developers won't get confused by the class syntax.
 *  See {@link PLS_Partials} for more details about each method.
 */
function pls_get_listings( $args = '' ) {
    return PLS_Partials::get_listings( $args );
}
function pls_get_cities( $args ) {
    return PLS_Partials::get_cities( $args );
}
function pls_get_listings_search_form( $args ) {
    return PLS_Partials::get_listings_search_form( $args );
}
function pls_get_listings_list_ajax( $args = '' ) { 
    return PLS_Partials::get_listings_list_ajax( $args );
}

/**
 * This class contains methods that retrurn plguin data wrapped in html. Each 
 * method implements filters that allow the theme developer to modify the 
 * returned data contextually.
 *
 * @package PlacesterSpine
 * @since 0.0.1
 */
class PLS_Partials {

    /**
     * Returns a list of properties listed formated in a default html.
     *
     * This function takes the raw properties data returned by the plugin and 
     * formats wrapps it in html. The returned html is filterable in multiple 
     * ways.
     *
     * The defaults are as follows:
     *     'width' - Default 100. The listing image width. If set to 0, 
     *          width is not added.
     *     'height' - Default false. The listing image height. If set to 0, 
     *          width is not added.
     *     'placeholder_img' - Defaults to placeholder image. The path to the 
     *          listing image that should be use if the listing has no images.
     *     'context' - An execution context for the function. Used when the 
     *          filters are created.
     *     'context_var' - Any variable that needs to be passed to the filters 
     *          when function is executed.
     *     'limit' - Default is 5. Total number of listings to retrieve. Maximum 
     *          set to 50.
     * Defines the following filters:
     * pls_listings_request[_context] - Filters the request parameters.
     * pls_listing[_context] - Filters the individual listing html.
     * pls_listings[_context] - Filters the complete listings list html. 
     *
     * @static
     * @param array $args Optional. Overrides defaults.
     * @return string The html with the list of properties.
     * @since 0.0.1
     */
    static function get_listings( $args = '' ) {

        /** Define the default argument array. */
        $defaults = array(
            'width' => 100,
            'height' => 0,
            'placeholder_img' => PLS_IMG_URL . "/null/listing-100x100.png",
            'context' => '',
            'context_var' => false,
            /** Placester API arguments. */
            'limit' => 5,
            'sort_type' => 'asc',
        );

        /** Merge the arguments with the defaults. */
        $args = wp_parse_args( $args, $defaults );

        /** Process arguments that need to be sent to the API. */
        $request_params = PLS_Plugin_API::get_valid_property_list_fields( $args );

        /** Extract the arguments after they merged with the defaults. */
        extract( $args, EXTR_SKIP );
        
        /** Sanitize the width. */
        if ( $width ) 
            $width = absint( $width );
            
        /** Sanitize the height. */
        if ( $height ) 
            $height = absint( $height );

        /** Filter the request parameters. */
        $request_params = apply_filters( pls_get_merged_strings( array( 'pls_listings_request', $context ), '_', 'pre', false ), $request_params, $context_var );

        /** Request the list of properties. */
        $listings_raw = PLS_Plugin_API::get_property_list( $request_params );

        /** Display a placeholder if the plugin is not active or there is no API key. */
        if ( pls_has_plugin_error() && current_user_can( 'administrator' ) )
            return pls_get_no_plugin_placeholder( pls_get_merged_strings( array( $context, __FUNCTION__ ), ' -> ', 'post', false ) );

        /** Return nothing when no plugin and user is not admin. */
        if ( pls_has_plugin_error() )
            return NULL;

        /** Define variable which will contain the html string with the listings. */
        $return = '';

        /** Set the listing image attributes. */
        $listing_img_attr = array();
        if ( $width )
            $listing_img_attr['width'] = $width;
        if ( $height )
            $listing_img_attr['height'] = $height;

        /** Collect the html for each listing. */
        $listings_html = array();
        foreach ( $listings_raw->properties as $listing_data ) {

            /**
             * Curate the listing_data.
             */

            /** Overwrite the placester url with the local url. */
            $listing_data->url = PLS_Plugin_API::get_property_url( $listing_data->id );

            /** Use the placeholder image if the property has no photo. */
            if ( empty( $listing_data->images ) ) {
                $listing_data->images[0]->url = $placeholder_img;
                $listing_data->images[0]->order = 0;
            }

            /** Remove the ID for each image (not needed by theme developers) and add the image html. */
            foreach ( $listing_data->images as $image ) {
                unset( $image->id );
                $image->html = pls_h_img( $image->url, $listing_data->location->full_address, $listing_img_attr );
            }

            /**
             * Create the html for this listing.
             * 
             * This results in:
             * <code>
             * <section>
             *   <a href="[$listing_url]">[$listing_addr]</a>
             *   <section class="clearfix">
             *       <div class="thumbs">
             *           <img src="[$listing_img]" alt="[$listing_addr]" />
             *       </div>
             *       <div class="feat-txt">
             *           <p>2 bed, 4 baths for $300 available 11-October-2011</p>
             *           <a href="[$listing_url]">Learn More</a>
             *       </div>
             *   </section>
             * </section>
             * </code>
             */
             ob_start();
             ?>

        <article class="listing-item grid_8 alpha" id="post-<?php the_ID(); ?>">
            <header class="grid_8 alpha">
                <h3><a href="<?php echo $listing_data->url; ?>" rel="bookmark" title="<?php echo $listing_data->location->full_address ?>"><?php echo $listing_data->location->full_address ?></a></h2>
                <ul>
                    <li>Beds: <?php echo $listing_data->bedrooms; ?>, </li>
                    <li>Baths: <?php echo $listing_data->bathrooms; ?>, </li>
                    <li>Half Baths: <?php echo $listing_data->half_baths; ?>, </li>
                    <li>Price: <?php echo $listing_data->price; ?>, </li>
                    <li>Available On: <?php echo $listing_data->available_on; ?>, </li>
                </ul>
            </header>
            <div class="entry-summary grid_8 alpha">
                <p>
                    <?php if (is_array($listing_data->images)): ?>
                        <div id="listing-thumbnail" class="grid_3 alpha">
                            <div class="outline">
                                <?php echo PLS_Image::load($listing_data->images[0]->url, array('resize' => array('w' => 200, 'h' => 200), 'fancybox' => true, 'as_html' => true)); ?>    
                            </div>
                        </div>
                        <div id="listing-description" class="grid_5 omega">
                            <?php echo substr($listing_data->description, 0, 300); ?>    
                        </div>
                    <?php else: ?>
                        <div id="listing-description" class="grid_8 omega">
                            <?php echo substr($listing_data->description, 0, 300); ?>    
                        </div>
                    <?php endif ?>
                </p>                
            </div><!-- .entry-summary -->
            <div class="entry-meta">
                <a class="more-link" href="<?php echo $listing_data->url; ?>">View Details</a>
            </div><!-- .entry-meta -->
            <footer class="grid_8 alpha">
                
                <ul>
                    <li>This listing has: </li>
                <?php foreach ($listing_data as $key => $value): ?>
                    <li><?php echo $key; ?>,</li>
                <?php endforeach ?>    
                </ul>
            </footer>
        </article>


             <?php
             $listing_html = ob_get_clean();

            /** Filter (pls_listing[_context]) the resulting html for a single listing. */
            $listing_html = apply_filters( pls_get_merged_strings( array( 'pls_listing', $context ), '_', 'pre', false ), $listing_html, $listing_data, $request_params, $context_var );

            /** Append the html to an array. This will be passed to the final filter. */
            $listings_html[] = $listing_html;

            /** Merge all the listings html. */
            $return .= $listing_html;

        }

        /** Wrap the listings html. */
        $return = pls_h(
            'section',
            array( 'class' => "pls-listings pls-listings " . pls_get_merged_strings( array( 'pls-listing', $context ), '-', 'pre', false ) ),
            $return
        );

        /** Filter (pls_listings[_context]) the resulting html that contains the collection of listings.  */
        return apply_filters( pls_get_merged_strings( array( 'pls_listings', $context ), '_', 'pre', false ), $return, $listings_raw, $listings_html, $request_params, $context_var );
    }

    /**
     * Returns the list of cities available wrapped in certain html element.
     * 
     * The defaults are as follows:
     *     'numberposts' - Default is 5. Total number of posts to retrieve.
     *     'offset' - Default is 0. See {@link WP_Query::query()} for more.
     *
     * @static
     * @param array $args Optional. Overrides defaults.
     * @return string The html with the list of cities.
     * @since 0.0.1
     */
    static function get_cities( $args ) {

        /** Define the default argument array. */
        $defaults = array(
            'wrapper_element' => 'option',
            'extra_attr' => array(),
        );

        /** Extract the arguments after they merged with the defaults. */
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

        /** Display a placeholder if the plugin is not active or there is no API key. */
        if ( pls_has_plugin_error() && current_user_can( 'administrator' ) )
            return pls_get_no_plugin_placeholder( pls_get_merged_strings( array( $context, __FUNCTION__ ), ' -> ', 'post', false ) );

        /** Return nothing when no plugin and user is not admin. */
        if ( pls_has_plugin_error() )
            return NULL;

        /** Get the location list from the plugin. */
        $locations = PLS_Plugin_API::get_location_list();

        /** Sort the cities */
        sort( $locations->city );

        $return = '';
        if ( $wrapper_element == 'option' ) 
            $return = pls_h_options( $locations->city, false, true );
        else 
            foreach( $location->city as $city ) 
                $return .= pls_h(
                    $wrapper_element,
                    $extra_attr,
                    $city
                );
            

        return $return; 
    }
    
    /**
     * Returns a form that can be used to search for listings.
     * 
     * The defaults are as follows:
     *     'ajax' - Default is false. Wether the resulting form should use ajax 
     *          or not. If ajax is set to true, then for the form to work, the 
     *          results container should be defined on the page. 
     *          {@link PLS_Partials::get_listings_list_ajax()} should be used.
     *     'results_page_id' - Default is the id of the page with the name 
     *          'lisings'. The id of the page that will contain the 
     *          results. In play only if 'ajax' is set to false.
     *     'context' - An execution context for the function. Used when the 
     *          filters are created.
     *     'context_var' - Any variable that needs to be passed to the filters 
     *          when function is executed.
     * Defines the following hooks.
     *      pls_listings_search_form_bedrooms_array[_context] - Filters the 
     *          array with the data used to generate the select.
     *      pls_listings_search_form_bathrooms_array[_context]
     *      pls_listings_search_form_available_on_array[_context]
     *      pls_listings_search_form_cities_array[_context]
     *      pls_listings_search_form_min_price_array[_context]
     *      pls_listings_search_form_max_price_array[_context]
     *      
     *      pls_listings_search_form_bedrooms_attributes[_context] - Filters 
     *          the attribute array for the select. If extra attributes need to 
     *          be added to the select element, they should be provided in 
     *          a array( $attribute_key => $attribute_value ) form.
     *      pls_listings_search_form_bathrooms_attributes[_context]
     *      pls_listings_search_form_available_on_attributes[_context]
     *      pls_listings_search_form_cities_attributes[_context]
     *      pls_listings_search_form_min_price_attributes[_context]
     *      pls_listings_search_form_max_price_attributes[_context]
     *      
     *      pls_listings_search_form_bedrooms_html[_context] - Filters the html 
     *          for this option. Can be used to add extra containers.
     *      pls_listings_search_form_bathrooms_html[_context]
     *      pls_listings_search_form_available_on_html[_context]
     *      pls_listings_search_form_cities_html[_context]
     *      pls_listings_search_form_min_price_html[_context]
     *      pls_listings_search_form_max_price_html[_context]
     *      
     *      pls_listings_search_form_submit[_context] - Filters the form submit 
     *          button.
     *
     *      pls_listings_search_form_inner[_context] - Filters the form inner html.
     *      pls_listings_search_form_outer[_context] - Filters the form html.
     *
     * @static
     * @param array $args Optional. Overrides defaults.
     * @return string The html for the listings search form.
     * @since 0.0.1
     */
    static function get_listings_search_form( $args ) {

        /** Define the default argument array. */
        $defaults = array(
            'ajax' => false,
            'results_page_id' => get_page_by_title( 'listings' )->ID,
            'context' => '',
            'context_var' => null,
        );

        /** Extract the arguments after they merged with the defaults. */
        extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

        global $wp_rewrite;
        $search_page_id = $results_page_id;

        if ( $wp_rewrite->using_permalinks() ) {
            $form_data->action = get_permalink( $search_page_id );
            $form_data->hidden_field = '';             
        } else {
            $form_data->action = "index.php";
            $form_data->hidden_field = pls_h( 'input', array( 'type' => 'hidden', 'name' => 'page_id', 'value' => $search_page_id ) );
        }

        /**
         * Elements options arrays. Used to generate the HTML.
         */

        /** Prepend the default empty valued element. */
        $form_options['bedrooms'] = array( 'pls_empty_value' => __( 'Any', pls_get_textdomain() ) ) + range( 1, 9 );
        
        /** Prepend the default empty valued element. */
        $form_options['bathrooms'] = array( 'pls_empty_value' => __( 'Any', pls_get_textdomain() ) ) + range( 1, 9 );

        /** Prepend the default empty valued element. */
        $form_options['half_baths'] = array( 'pls_empty_value' => __( 'Any', pls_get_textdomain() ) ) + range( 1, 9 );

        /** Generate an array with the next 12 months. */
        $current_month = (int) date('m');
        for ( $i = $current_month; $i < $current_month + 12; $i++ ) {
            $form_options['available_on'][date( 'd-m-Y', mktime( 0, 0, 0, $i, 1 ) )] = date( 'F Y', mktime( 0, 0, 0, $i, 1 ) );
        }

        /** Get the property type options. */
        $form_options['property_type'] = array( 'pls_empty_value' => __( 'Any', pls_get_textdomain() ) ) + PLS_Plugin_API::get_type_values( 'property' );

        /** Get the listing type options. */
        $form_options['listing_types'] = array( 'pls_empty_value' => __( 'All', pls_get_textdomain() ) ) + PLS_Plugin_API::get_type_values( 'listing' );

        /** Get the zoning type options. */
        $form_options['zoning_types'] = array( 'pls_empty_value' => __( 'All', pls_get_textdomain() ) ) + PLS_Plugin_API::get_type_values( 'zoning' );

        /** Get the purchase type options. */
        $form_options['purchase_types'] = array( 'pls_empty_value' => __( 'All', pls_get_textdomain() ) ) + PLS_Plugin_API::get_type_values( 'purchase' );

        /** Prepend the default empty valued element. */
        $form_options['available_on'] = array( 'pls_empty_value' => __( 'Anytime', pls_get_textdomain() ) ) + $form_options['available_on'];

        /** Get the location list from the plugin. */
        $locations = PLS_Plugin_API::get_location_list();

        /** Display a placeholder if there is a plugin error. */
        if ( pls_has_plugin_error() && current_user_can( 'administrator' ) )
            return pls_get_no_plugin_placeholder( pls_get_merged_strings( array( $context, __FUNCTION__ ), ' -> ', 'post', false ) );

        /** Return nothing when plugin error and user is not admin. */
        if ( pls_has_plugin_error() )
            return NULL;

        /** Sort the states. */
        sort( $locations->city );

        /** Prepend the default empty valued element. */
        $form_options['cities'] = array( 'pls_empty_value' => __( 'All', pls_get_textdomain() ) ) + $locations->city;
        /** Sort the states. */
        sort( $locations->state );

        /** Prepend the default empty valued element. */
        $form_options['states'] = array( 'pls_empty_value' => __( 'All', pls_get_textdomain() ) ) + $locations->state;

        /** Sort the zip codes. */
        sort( $locations->zip );

        /** Prepend the default empty valued element. */
        $form_options['zips'] = array( 'pls_empty_value' => __( 'Any', pls_get_textdomain() ) ) + $locations->zip;

        /** Define the minimum price options array. */
        $form_options['min_price'] = array(
            'pls_empty_value' => __( 'Any', pls_get_textdomain() ),
            '200' => '200',
            '500' => '500',
            '1000' => '1,000',
            '2000' => '2,000',
            '3000' => '3,000',
            '4000' => '4,000',
            '5000' => '5,000'
        );

        /** Set the maximum price options array. */
        $form_options['max_price'] = $form_options['min_price'];
        unset( $form_options['max_price'][200] );

        /** Define an array for extra attributes. */
        $form_opt_attr = array();

        /** Filter form fields. */
        foreach( $form_options as $option_name => &$opt_array ) {

            /** Filter each of the fields options arrays. */
            $opt_array = apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_{$option_name}_array", $context ), '_', 'pre', false )
, $opt_array, $context_var );
            
            /**
             * Select fields attributes.
             */

            /** Form options array. */
            $form_opt_attr[$option_name] = apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_{$option_name}_attributes", $context ), '_', 'pre', false )
, array(), $context_var );

            /** Make sure it is an array. */
            if ( ! is_array( $form_opt_attr[$option_name] ) ) 
                $form_opt_attr[$option_name] = array();

            /** Append the data-placeholder attribute. */
            if ( isset( $opt_array['pls_empty_value'] ) ) {
                $form_opt_attr[$option_name] = $form_opt_attr[$option_name] + array( 'data-placeholder' => $opt_array['pls_empty_value'] );
            }

        }

        /**
         * Elements HTML.
         */

        /** Add the bedrooms select element. */
        $form_html['bedrooms'] = pls_h( 
            'select',
            array( 'name' => 'bedrooms' ) + $form_opt_attr['bedrooms'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['bedrooms'], "" )
        );

        /** Add the bathroms select element. */
        $form_html['bathrooms'] = pls_h( 
            'select',
            array( 'name' => 'bathrooms' ) + $form_opt_attr['bathrooms'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['bathrooms'], "" )
        );

        /** Add the bathroms select element. */
        $form_html['half_baths'] = pls_h( 
            'select',
            array( 'name' => 'half_baths' ) + $form_opt_attr['half_baths'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['half_baths'], "" )
        );

        /** Add the property type select element. */
        $form_html['property_type'] = pls_h(
            'select',
            array( 'name' => 'property_type' ) + $form_opt_attr['property_type'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['property_type'], "" )
        );

        /** Add the listing type select element. */
        $form_html['listing_types'] = pls_h(
            'select',
            array( 'name' => 'listing_types', 'multiple' => true ) + $form_opt_attr['listing_types'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['listing_types'], "" )
        );

        /** Add the zoning type select element. */
        $form_html['zoning_types'] = pls_h(
            'select',
            array( 'name' => 'zoning_types', 'multiple' => true  ) + $form_opt_attr['zoning_types'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['zoning_types'], "" )
        );

        /** Add the purchase type select element. */
        $form_html['purchase_types'] = pls_h(
            'select',
            array( 'name' => 'purchase_types', 'multiple' => true  ) + $form_opt_attr['purchase_types'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['purchase_types'], "" )
        );

        /** Add the availability select element. */
        $form_html['available_on'] = pls_h(
            'select',
            array( 'name' => 'available_on' ) + $form_opt_attr['available_on'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['available_on'], "" )
        );
                                    
        /** Add the cities select element. */
        $form_html['cities'] = pls_h(
            'select',
            array( 'name' => 'location[city]' ) + $form_opt_attr['cities'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['cities'], "", true )
        );

        /** Add the cities select element. */
        $form_html['states'] = pls_h(
            'select',
            array( 'name' => 'location[state]' ) + $form_opt_attr['states'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['states'], "", true )
        );

        /** Add the cities select element. */
        $form_html['zips'] = pls_h(
            'select',
            array( 'name' => 'location[zip]' ) + $form_opt_attr['zips'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['zips'], "", true )
        );

        /** Add the minimum price select element. */
        $form_html['min_price'] = pls_h(
            'select',
            array( 'name' => 'min_price' ) + $form_opt_attr['min_price'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['min_price'] )
        );

        /** Add the maximum price select element. */
        $form_html['max_price'] = pls_h(
            'select',
            array( 'name' => 'max_price' ) + $form_opt_attr['max_price'],
            /** Get the list of options with the empty valued element selected. */
            pls_h_options( $form_options['max_price'] )
        );

        $section_title = array(
            'bedrooms' => __( 'Bedrooms', pls_get_textdomain() ),
            'bathrooms' => __( 'Bathrooms', pls_get_textdomain() ),
            'half_baths' => __( 'Half baths', pls_get_textdomain() ),
            'property_type' => __( 'Property Type', pls_get_textdomain() ),
            'zoning_types' => __( 'Zoning Types', pls_get_textdomain() ),
            'listing_types' => __( 'Listing Types', pls_get_textdomain() ),
            'purchase_types' => __( 'Purchase Types', pls_get_textdomain() ),
            'available_on' => __( 'Available', pls_get_textdomain() ),
            'cities' => __( 'Near', pls_get_textdomain() ),
            'states' => __( 'State', pls_get_textdomain() ),
            'zips' => __( 'Zip Code', pls_get_textdomain() ),
            'min_price' => __( 'Price from', pls_get_textdomain() ),
            'max_price' => __( 'Price to', pls_get_textdomain() ),
        );

        /** Apply filters on all the form elements html. */
        foreach( $form_html as $option_name => &$opt_html ) {

            $opt_html = apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_{$option_name}_html", $context ), '_', 'pre', false )
, $opt_html, $form_options[$option_name], $section_title[$option_name], $context_var );
        }

        /** Combine the form elements. */
        $form = '';
        foreach ( $form_html as $label => $select ) {
            $form .= pls_h(
                'section',
                array( 'class' => $label . ' pls_search_form' ),
                pls_h_label( $section_title[$label], $label ) .
                $select
            );
        }

        /** Add the filtered submit button. */
        $form_html['submit'] = apply_filters( 
            pls_get_merged_strings( array( "pls_listings_search_submit", $context ), '_', 'pre', false ), 
            pls_h( 'input', array('class' => 'pls_search_button', 'type' => 'submit', 'value' => __( 'Search', pls_get_textdomain() ) ) ),  
            $context_var
        );

        /** Append the form submit. */
        $form .= $form_html['submit'];

        /** Wrap the combined form content in the form element and filter it. */
        $form_id = pls_get_merged_strings( array( 'pls-listings-search-form', $context ), '-', 'pre', false );
        $form = pls_h(
            'form',
            array( 'action' => $form_data->action, 'method' => 'get', 'id' => $form_id ),
            $form_data->hidden_field . apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_inner", $context ), '_', 'pre', false ), $form, $form_html, $form_options, $section_title, $context_var )
        );

        /** Filter the form. */
        $return = apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_outer", $context ), '_', 'pre', false ), $form, $form_html, $form_options, $section_title, $context_var );

        /** Add the JS that makes this work ajaxomagically. */
        if ( $ajax ) 
            $return .= PLS_Plugin_API::get_filter_form_extra( $form_id );
        // placester_register_filter_form
        /**  */

        return $return;
    }

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
    static function get_listings_list_ajax( $args = '' ) {

        /** Define the default argument array. */
        $defaults = array(
            'placeholder_img' => PLS_IMG_URL . "/null/listing-100x100.png",
            'loading_img' => admin_url( 'images/wpspin_light.gif' ),
            'image_width' => 100,
            'sort_type' => 'asc',
            'crop_description' => 0,
            'listings_per_page' => get_option( 'posts_per_page' ),
            'context' => '',
            'context_var' => NULL,
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
                'sort_by' => 'bathrooms',
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
        $sort_by_html = pls_h(
            'select',
            array( 'id' => 'sort-by' ),
            pls_h_options( $sort_by_options, 'bathrooms asc' )
        );

        
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
        $return = apply_filters( pls_get_merged_strings( array( "pls_listings_list_ajax_html", $context ), '_', 'pre', false ), $sort_by_html . $loader_html . $listings_list . $pagination_html, $listings_list, $sort_by_html, $loader_html, $pagination_html );

        /** Append the extra javascript and return. */
        return $return . $row_rendering_js;
    }
}



add_filter('the_content', 'property_details_filter', 11);
function property_details_filter($content) {
    global $post;
    
    if($post->post_type == 'property') {

        $content = get_option('placester_listing_layout');

        if(isset($content) && $content != '') {
            return $content;
        }

        $listing_data = json_decode(stripslashes($post->post_content), true);
        
        ob_start();
        ?>
            <h2> <?php echo $listing_data['location']['full_address']; ?> </h2>
            <div class="details-wrapper grid_8 alpha">
                <div id="slideshow" class="clearfix theme-default left bottomborder">
                    <h3>Image Gallery</h3>
                    <div class="grid_8 alpha">
                        <ul class='property-image-gallery grid_8 alpha'>
                            <?php foreach ($listing_data['images'] as $images): ?>
                            <li><?php echo PLS_Image::load($images['url'], array('resize' => array('w' => 200, 'h' => 200), 'fancybox' => true, 'as_html' => true)) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid_8 alpha">
                <h3>Property Map</h3>
                <?php echo PLS_Maps::map($listing_data, array('lat'=>$listing_data['location']['coords']['latitude'], 'lng'=>$listing_data['location']['coords']['longitude'], 'width' => 620, 'height' => 250, 'zoom' => 16)); ?>
            </div>

            <div class="details-wrapper grid_8 alpha">
                <h3>Property Description</h3>
                <?php if (!empty($listing_data['description'])): ?>
                    <p> <?php echo $listing_data['description']; ?> </p>
                <?php else: ?>
                    <p> No description available </p>
                <?php endif ?>
            </div>
            
            <div class="details-list-txt grid_8 alpha">
                <h3>Property Attributes</h3>
                <ul>
                    <?php echo pls_quick_list($listing_data, true); ?>
                </ul>
            </div>    
        <?php
        $html = ob_get_clean();

        return $html;
        
    }
    
}


