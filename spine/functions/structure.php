<?php
/**
 * Returns the full path to the main template file
 * 
 * @since 0.0.1
 */
function pls_template_path() {
	return PLS_Wrapping::$main_template;
}

/**
 * Returns the base name for the template.
 * e.g. 'page' for 'page.php'
 * 
 * @since 0.0.1
 */
function pls_template_base() {
	return PLS_Wrapping::$base;
}

/**
 * A class that adds theme wrapping functionality
 *
 * This allows theme developers to avoid code repetition by adding the common 
 * surrounding code from templates to a wrapper.php file.
 *
 * @static
 * @link http://scribu.net/wordpress/theme-wrappers.html
 * @since 0.0.1
 */
class PLS_Wrapping {
	/**
	 * Stores the full path to the main template file
	 */
	static $main_template;

	/**
	 * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	 */
	static $base;

	static function wrap( $template ) {
		
		self::$main_template = $template;

		self::$base = substr( basename( self::$main_template ), 0, -4 );

		if ( 'index' == self::$base )
			self::$base = false;

		$templates = array( 'wrapper.php' );

        /**
         *  Looks for wrapper-[base].php and then for spine/wrappers/wrapper-[base].php
         */
		if ( self::$base )
			array_unshift( $templates, sprintf( 'wrapper-%s.php', self::$base ), sprintf( '/spine/wrappers/wrapper-%s.php', self::$base ) );

		return locate_template( $templates );
	}
}
// add_filter( 'template_include', array( 'PLS_Wrapping', 'wrap' ) );
