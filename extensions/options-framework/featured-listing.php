<?php 

class PLS_Featured_Listing_Option {

	function load ( $params ) {
		ob_start();
			extract( $params );
			include( trailingslashit( PLS_OPTRM_DIR ) . 'views/featured-listings.php' );
		echo ob_get_clean();
	}
}