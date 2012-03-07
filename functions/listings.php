<?php 

/**
* 
*/
PLS_Listing_Helper::init();
class PLS_Listing_Helper {
	
	function init() {
		add_action('wp_ajax_pls_listings_for_options', array(__CLASS__,'listings_for_options'));
	}

	function listings_for_options () {

	}
}