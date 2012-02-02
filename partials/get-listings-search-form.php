<?php 

class PLS_Partials_Listing_Search_Form {
	

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
	function init ($args = '') {
		
		/** Define the default argument array. */
        $defaults = array(
            'ajax' => false,
            'results_page_id' => get_page_by_title( 'listings' )->ID,
            'context' => '',
            'context_var' => null,
            'bedrooms' => 1,
            'bathrooms' => 1,
            'price' => 1,
            'half_baths' => 1,
            'property_type' => 1,
            'listing_types'=> 1,
            'zoning_types' => 1,
            'purchase_types' => 1,
            'available_on' => 1,
            'cities' => 1,
            'states' => 1,
            'zips' => 1,
            'min_price' => 1,
            'max_price' => 1

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
				// removed "All" - it's not giving all listings. jquery needs to change to not include "[]"s
        // $form_options['zoning_types'] = PLS_Plugin_API::get_type_values( 'zoning' ); // for Multiple, not for single, see below

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
        if ($bedrooms == 1) {
            $form_html['bedrooms'] = pls_h( 
                'select',
                array( 'name' => 'bedrooms') + $form_opt_attr['bedrooms'],
                    /** Get the list of options with the empty valued element selected. */
                    pls_h_options( $form_options['bedrooms'], "" )
                );
        }
        

        /** Add the bathroms select element. */
        if ($bathrooms == 1) {
            $form_html['bathrooms'] = pls_h( 
                'select',
                array( 'name' => 'bathrooms' ) + $form_opt_attr['bathrooms'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['bathrooms'], "" )
            );            
        }

        /** Add the bathroms select element. */
        if ($half_baths == 1) {
            $form_html['half_baths'] = pls_h( 
                'select',
                array( 'name' => 'half_baths' ) + $form_opt_attr['half_baths'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['half_baths'], "" )
            );
        }
        

        /** Add the property type select element. */
        if ($property_type == 1) {
            $form_html['property_type'] = pls_h(
                'select',
                array( 'name' => 'property_type' ) + $form_opt_attr['property_type'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['property_type'], "" )
            );
        }

        /** Add the listing type select element. */
        if ($listing_types == 1) {
            $form_html['listing_types'] = pls_h(
                'select',
                array( 'name' => 'listing_types', 'multiple' => true ) + $form_opt_attr['listing_types'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['listing_types'], "" )
            );
        }
        
        /** Add the zoning type select element. */
        if ($zoning_types == 1) {
            $form_html['zoning_types'] = pls_h(
                'select',
                array( 'name' => 'zoning_types[zoning_type]'  ) + $form_opt_attr['zoning_types'],
                // array( 'name' => 'zoning_types[zoning_type]', 'multiple' => true  ) + $form_opt_attr['zoning_types'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['zoning_types'], "" )
            );
        }
        

        /** Add the purchase type select element. */
        if ($purchase_types == 1) {
            $form_html['purchase_types'] = pls_h(
                'select',
                array( 'name' => 'purchase_types', 'multiple' => true  ) + $form_opt_attr['purchase_types'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['purchase_types'], "" )
            );
        }
        

        /** Add the availability select element. */
        if ($available_on == 1) {
            $form_html['available_on'] = pls_h(
                'select',
                array( 'name' => 'available_on' ) + $form_opt_attr['available_on'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['available_on'], "" )
            );
        }
        
                                    
        /** Add the cities select element. */
        if ($cities == 1) {
            $form_html['cities'] = pls_h(
                'select',
                array( 'name' => 'location[city]' ) + $form_opt_attr['cities'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['cities'], "", true )
            );
        }
        
        /** Add the cities select element. */
        if ($states == 1) {
                $form_html['states'] = pls_h(
                'select',
                array( 'name' => 'location[state]' ) + $form_opt_attr['states'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['states'], "", true )
            );
        }
        

        /** Add the cities select element. */
        if ($zips == 1) {
            $form_html['zips'] = pls_h(
                'select',
                array( 'name' => 'location[zip]' ) + $form_opt_attr['zips'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['zips'], "", true )
            );
        }
        

        /** Add the minimum price select element. */
        if ($min_price == 1) {
            $form_html['min_price'] = pls_h(
                'select',
                array( 'name' => 'min_price' ) + $form_opt_attr['min_price'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['min_price'] )
            );
        }
        

        /** Add the maximum price select element. */
        if ($max_price == 1) {
            $form_html['max_price'] = pls_h(
                'select',
                array( 'name' => 'max_price' ) + $form_opt_attr['max_price'],
                /** Get the list of options with the empty valued element selected. */
                pls_h_options( $form_options['max_price'] )
            );
        }
        

        $section_title = array(
            'bedrooms' => __( 'Beds', pls_get_textdomain() ),
            'bathrooms' => __( 'Baths', pls_get_textdomain() ),
            'half_baths' => __( 'Half Baths', pls_get_textdomain() ),
            'property_type' => __( 'Property Type', pls_get_textdomain() ),
            'zoning_types' => __( 'Zoning Type', pls_get_textdomain() ),
            'listing_types' => __( 'Listing Type', pls_get_textdomain() ),
            'purchase_types' => __( 'Purchase Type', pls_get_textdomain() ),
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
        $return = apply_filters( pls_get_merged_strings( array( "pls_listings_search_form_outer", $context ), '_', 'pre', false ), $form, $form_html, $form_options, $section_title, $form_data, $form_id, $context_var );

        /** Add the JS that makes this work ajaxomagically. */
        if ( $ajax ) 
            $return .= PLS_Plugin_API::get_filter_form_extra( $form_id );
        // placester_register_filter_form
        /**  */

        return $return;

	}
}