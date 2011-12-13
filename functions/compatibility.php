<?php
/**
 * Wrapper functions for the ones in the class.
 */
function pls_get_company_details() {
    return PLS_Plugin_API::get_company_details();
}

function pls_get_property_url( $id ) {
    return PLS_Plugin_API::get_property_url( $id );
}

function pls_get_property_list( $params ) {
    return PLS_Plugin_API::get_property_list( $params );
}

function pls_get_valid_property_list_fields( &$args ) {
    return PLS_Plugin_API::get_valid_property_list_fields( &$args );
}

function pls_get_property_list_fields( $field = '' ) {
    return PLS_Plugin_API::get_property_list_fields( $field = '' );
}

function pls_get_type_values( $type ) {
    return PLS_Plugin_API::get_type_values( $type );
}

function pls_get_location_list() {
    return PLS_Plugin_API::get_location_list();
}

function pls_get_listings_list( $args ) {
    return PLS_Plugin_API::get_listings_list( $args );
}

function pls_get_user_details() {
    return PLS_Plugin_API::get_user_details();
}

function pls_get_filter_form_extra( $form_dom_id ) {
    return PLS_Plugin_API::get_filter_form_extra( $form_dom_id );
}

/**
 * This class acts as a buffer between the theme and the plugin. All calls to 
 * functions from the plugin must be done using this class. If new functions 
 * that need to be available to theme developers are added to the plugin, they 
 * must be added to this class.
 *
 * @package PlacesterBlueprint
 * @subpackage Functions
 * @since 0.0.1
 */
class PLS_Plugin_API {

    /**
     * Verify if calling a plugin function throws any exceptions. If it throws 
     * a timeout exception, set the theme global error flag.
     * 
     * @static
     * @access private
     * @return mixed The result of the execution of the function if the plugin 
     * didn't throw any exceptions, false otherwise.
     * @since 0.0.1
     */
    static private function _try_for_exceptions() {

        if ( pls_has_plugin_error() ) 
            return false;

        $parameters = func_get_args();

        $function_name = array_shift( $parameters );

        try {
            /** Call the function with its parmamters. */
            $return = call_user_func_array( $function_name, $parameters );

        } catch ( Exception $e ) {

            /** Assumes an exception with a private message is a timeout. */
            if ( ! isset( $e->message ) ) {
                global $placester_blueprint;
                $placester_blueprint->has_plugin_error = 'timeout';
            }

            return false;
        }

        return $return;
    }

    /**
     * Returns the company details object.
     *
     * Object looks like this:
     * TODO
     * 
     * @static
     * @return mixed False if plugin data cannot be accessed, or the company 
     * details object otherwise.
     * @since 0.0.1
     */
    static function get_company_details() {

        /** Test the function for any exceptions. */
        $return = self::_try_for_exceptions( 'get_company_details' );

        /** If no exceptions were detected, return the result. */
        if ( $return )
            return $return;

        return false;
    }

    /**
     * Returns the local property url.
     *
     * Url is of the form:
     * http://www.mydomain.com/listing/$id
     * 
     * @static
     * @param string $id The property id.
     * @return mixed False if plugin data cannot be accessed, or the url 
     * otherwise.
     * @since 0.0.1
     */
    static function get_property_url( $id ) {

        /** Test the function for any exceptions. */
        $return = self::_try_for_exceptions( 'placester_get_property_url', $id );

        /** If no exceptions were detected, return the result. */
        if ( $return )
            return $return;

        return false;
    }

    /**
     * Return an object containing a list of properties.
     * 
     * @static
     * @param string $params The parameter array. Details can be found in the 
     * attached link.
     * @return mixed False if plugin data cannot be accessed, or the url 
     * otherwise.
     * @link http://docs.placester.com/rest/api/v1/properties/get.html
     * @since 0.0.1
     */
    static function get_property_list( $params ) {

        /** Test the function for any exceptions. */
        $return = self::_try_for_exceptions( 'placester_property_list', $params );

        /** If no exceptions were detected, return the result. */
        if ( $return ) {
            /** Initialize the global property post type slug. */
            global $placester_post_slug;
            $placester_post_slug = placester_post_slug();

            /** Return the raw data. */
            return $return;
        }

        return false;
    }

    /**
     * Processes a list of arguments and selects only the valid ones that can 
     * be used to make a request to the API.
     * 
     * @static
     * @param array $args The argument array.
     * @uses PLS_Plugin_API::get_property_list_fields();
     * @since 0.0.1
     */
    static function get_valid_property_list_fields( &$args ) {

        /** Get the list of arguments accepted by the api function. */
        $api_valid_args = self::get_property_list_fields();

        /** Process arguments that need to be sent to the API. */
        $request_params = array();
        foreach( $args as $key => $value ) {
            /** If the argument is meant for the API request. */
            if ( array_key_exists( $key, $api_valid_args ) ) {

                /** The field valid type. */
                $api_valid_args_type = $api_valid_args[$key];

                /** Verify if the argument value is valid. */
                $has_valid_value = empty( $api_valid_args_type ) ||
                    ( 
                        is_array( $api_valid_args_type ) && 
                        array_key_exists( $value, $api_valid_args_type )
                    ) ||
                    ( 
                        is_string( $api_valid_args_type ) && 
                        function_exists( "is_{$api_valid_args_type}" ) && 
                        call_user_func( "is_{$api_valid_args_type}", $value )
                    );

                /** If it's valid, add the argument to the request parameters. */ 
                if ( $has_valid_value ) {
                    $request_params[$key] = $value;
                    unset( $args[$key] );
                }

            }
        }

        return $request_params;
    }


    /**
     * Returns a list of arguments allowed by the 'placester_property_list' 
     * function.
     *
     * The value of the array contains the allowed type of the argument, the 
     * subset of allowed values if it's an array, or anything if empty.
     * 
     * @static
     * @return array The allowed arguments array>
     */
    static function get_property_list_fields( $field = '' ) {

        $return = array(
            'only_verified' => '',
            'include_disabled' => '',
            'property_ids' => 'array',
            'property_type' => array(
                'apartment' => true,
                'penthouse' => true,
                'townhouse' => true,
                'brownstone' => true,
                'family_home' => true,
                'multi_fam_home' => true,
                'flat' => true,
                'loft' => true,
                'cottage' => true,
                'villa' => true,
                'mansion' => true,
                'ranch' => true,
                'island' => true,
                'log_cabin' => true,
                'tent' => true,
            ) ,
            'listing_types' => 'array', 
            'zoning_types' => 'array', 
            'purchase_types' => 'array', 
            'bedrooms' => 'numeric', 
            'bathrooms' => 'numeric', 
            'half_baths' => 'numeric', 
            'min_price' => 'float', 
            'max_price' => 'float', 
            'price' => 'float', 
            'available_on' => '', 
            'location[zip]' => 'string', 
            'location[state]' => 'string', 
            'location[city]' => 'string', 
            /** Country not supported by the API. */
            'box[min_latitude]' => 'numeric',
            'box[max_latitude]' => 'numeric',
            'box[min_longitude]' => 'numeric',
            'box[max_longitude]' => 'numeric',
            'address_mode' => array( 'polygon' => true, 'exact' => true ),
            'limit' => 'numeric',
            'skip' => 'numeric',
            'is_featured' => '',
            'is_new' => '',
            /** The commented ones are not supported by the list of listings. */
            'sort_by' => array( 
                'price' => __( 'Price', pls_get_textdomain() ),
                // 'sqft' => __( 'Square Feet', pls_get_textdomain() ),
                'description' => __( 'Description', pls_get_textdomain() ), 
                'bedrooms' => __( 'Bedroom', pls_get_textdomain() ),
                'half_baths' => __( 'Half Baths', pls_get_textdomain() ),
                'available_on' => __( 'Available On', pls_get_textdomain() ),
                'location.address' => __( 'Address', pls_get_textdomain() ),
                'location.city' => __( 'City', pls_get_textdomain() ),
                'location.state' => __( 'State', pls_get_textdomain() ),
                'location.zip' => __( 'Zip', pls_get_textdomain() ),
                // 'location.neighborhood' => __( 'Neighborhood', pls_get_textdomain() ),
                'location.country' => __( 'Country', pls_get_textdomain() ),
            ),
            'sort_type' => array( 'asc' => true, 'desc' => true )
        );

        if ( ! empty( $field ) && array_key_exists( $field, $return ) )
            return $return[$field];

        return $return;
    }

    static function get_type_values( $type ) {

        /** Define the supported types. */
        $supported_types = array( 
            'property' => array(
                'apartment' => __( 'Apartment', pls_get_textdomain() ),
                'penthouse' => __( 'Penthouse', pls_get_textdomain() ),
                'townhouse' => __( 'Townhouse', pls_get_textdomain() ),
                'brownstone' => __( 'Brownstone', pls_get_textdomain() ),
                'family_home' => __( 'Single Family Home', pls_get_textdomain() ),
                'multi_fam_home' => __( 'Multi Family Home', pls_get_textdomain() ),
                'flat' => __( 'Flat', pls_get_textdomain() ),
                'loft' => __( 'Loft', pls_get_textdomain() ),
                'cottage' => __( 'Cottage', pls_get_textdomain() ),
                'villa' => __( 'Villa', pls_get_textdomain() ),
                'mansion' => __( 'Mansion', pls_get_textdomain() ),
                'ranch' => __( 'Ranch', pls_get_textdomain() ),
                'island' => __( 'Island', pls_get_textdomain() ),
                'log_cabin' => __( 'Log Cabin', pls_get_textdomain() ),
                'tent' => __( 'Tent', pls_get_textdomain() ),
            ), 
            'listing' => array(
                'storage' => __( 'Storage', pls_get_textdomain() ),
                'housing' => __( 'Housing', pls_get_textdomain() ),
                'parking' => __( 'Parking', pls_get_textdomain() ),
                'sublet' => __( 'Sublet', pls_get_textdomain() ),
                'vacation' => __( 'Vacation', pls_get_textdomain() ),
                'land' => __( 'Land', pls_get_textdomain() ),
                'other' => __( 'Other', pls_get_textdomain() ),
            ), 
            'zoning' => array(
                'residential' => __( 'Residential', pls_get_textdomain() ),
                'commercial' => __( 'Commercial', pls_get_textdomain() ),
            ), 
            'purchase' => array(
                'rental' => __( 'Rental', pls_get_textdomain() ),
                'sale' => __( 'Sale', pls_get_textdomain() ),
            )
        );

        /** If not a valid type, return empty handed. */
        if ( empty( $type ) || ! array_key_exists( $type, $supported_types ) )
            return;

        return $supported_types[$type];
    }

    /**
     * Gets an object containing the list of cities, zip codes and states of 
     * the available properties.
     *
     * The object looks like this: 
     * <code>
     * object(stdClass)#59 (3) {
     *   ["city"]=> array(8) { [0]=> string(7) "City 1", ... }
     *   ["zip"]=> array(8) { [0]=> string(7) "Zip Code 1", ... }
     *   ["state"]=> array(6) { [0]=> string(2) "State Code 1", ... }
     * }
     * </code>
     * 
     * @return mixed The object containing the data if the plugin is active and 
     * has a API key, FALSE otherwise.
     * @since 0.0.1
     */
    static function get_location_list() {

        /** Test the function for any exceptions. */
        $return = self::_try_for_exceptions( 'placester_location_list' );

        /** If no exceptions were detected, return the result. */
        if ( $return ) 
            return $return;

        return false;
    }

    /**
     * Prints a standalone list of properties.
     * 
     * @param array $parameters - configuration data.
     *        configuration elements are different based on list mode.
     *        there are different modes defined by 'table_type' parameter.
     *
     *        for table_type = datatable list is displayed using
     *		  <a href="http://datatables.net">datatables.net</a> library. 
     *        parameters are:
     *			- table_type => 'datatable'
     *			- paginate =>
     *            number of rows for each page
     *			- attributes
     *            array, fields to display, where key is field name
     *				- fieldname =>
     *				- label =>
     *                name of field, how to display it
     *				- width =>
     *                width of field
     *			- js_renderer
     *                js function called to convert field content and return
     *                html representation of field to display
     *
     *        for table_type = html list is displayed as sequence of pure html &lt;div&gt;
     *        elements where each element represent single listing.
     *        parameters are:
     *				- table_type => 'html'
     *				- js_row_renderer =>
     *            js function name taking array of property fields data and 
     *            returning html to print.
     *				- pager =>
     *            array. elements are:
     *					- render_in_dom_element =>
     *              if specified - pager will be rendered to that dom id
     *            		- rows_per_page =>
     *              number of properties to print at single page
     *            		- css_current_button =>
     *              css style of "current page" button
     *            		- css_not_current_button =>
     *              css style of other page-switch buttons
     *            		- first_page =>
     *              array, configuration of "first page" button of pager.
     *              parameters are:
     *						- visible =>
     *                true / false
     *						- label =>
     *                html of button' text
     *            		- previous_page =>
     *              array, configuration of "previous page" button of pager.
     *              same as for "first page"
     *            		- next_page =>
     *              array, configuration of "next page" button of pager.
     *              same as for "first page"
     *            		- last_page' => 
     *              array, configuration of "last page" button of pager.
     *              same as for "first page"
     *            		- numeric_links =>
     *              array, configuration of numeric links buttons of pager.
     *              parameters are:
     *						- visible =>
     *                true / false
     *						- max_count => 
     *                maximum number of page links to show
     *						- more_label
     *                if there are more pages than printed, this html is inserted
     *						- css_outer
     *                css class of outer div for numberic links
     *				- attributes =>
     *            array of fields name to extract from data storage.
     *            dont ask for fields not displayed - that will
     *            unreasonably slow down requests.
     * @return mixed The html and js for the list if there are no plugin errors, FALSE otherwise.
     * @since 0.0.1
     */
    static function get_listings_list( $args ) {

        /** Test the function for any exceptions. */
        $return = self::_try_for_exceptions( 'placester_listings_list', $args, true );

        /** If no exceptions were detected, return the result. */
        if ( $return ) 
            return $return;

        return false;
    }

    /**
     * Returns object containing user details.
     *
     * @return mixed False if plugin data cannot be accessed, or the user 
     * details object otherwise.
     * @since 0.0.1
     */
    static function get_user_details() {

        /** Test the function for any exceptions. */
        $return = self::_try_for_exceptions( 'placester_get_user_details' );

        /** If no exceptions were detected, return the result. */
        if ( $return ) 
            return $return;

        return false;
    }

    /**
     * Registers filter form on a page which will control 
     * property lists / property maps on this page by importing the needed 
     * JavaScript.
     * 
     * @param string $form_dom_id - DOM id of form object containing filter
     * @param bool $echo Wether to echo or return the content.
     * @return mixed The needed JavaScript if the plugin connection works, false 
     * otherwise.
     * @since 0.0.1
     */
    static function get_filter_form_extra( $form_dom_id ) {

        /** Test the function for any exceptions. */
        $return = self::_try_for_exceptions( 'placester_register_filter_form', $form_dom_id, false );

        /** If no exceptions were detected, return the result. */
        if ( $return ) 
            return $return;

        return false;
    }

	// get theme options using the plugin theme option getter
    static function get_option( $options ) {

        if ( ! pls_has_plugin_error() ) {
	
            return placester_option_getter( $options );
        } 

        return false;
    }

	// set options using the plugin's theme option setter. 
    static function set_option( $options ) {

        if ( ! pls_has_plugin_error() ) {

            placester_option_setter( $options );
             
            return;
        } 

        return false;
    }

	// get plugin options directly from the DB
    static function get_plugin_option( $options ) {

        if ( ! pls_has_plugin_error() ) {
	
            return get_option( $options );
        } 

        return false;
    }
}
