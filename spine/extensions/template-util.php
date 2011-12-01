<?php 

PLS_Template::init();

class PLS_Template {

	function init()
	{
		add_action( 'send_headers', array( __CLASS__, 'pls_get_template_part'  ));
		
	}
	
	function pls_get_template_part($slug = false, $name = null )
	{
	    pls_dump($slug);
	    return;

	    do_action( "get_template_part_{$slug}", $slug, $name );

	    $templates = array();
	    if ( isset($name) )
	        $templates[] = "{$slug}-{$name}.php";

	    $templates[] = "{$slug}.php";

	    self::pls_locate_template($templates, true, false);   
	}

	private static function pls_locate_template($template_names, $load = false, $require_once = true )
	{
	
		$located = '';
		foreach ( (array) $template_names as $template_name ) {
			if ( !$template_name )
				continue;
			if ( file_exists(STYLESHEETPATH . '/' . $template_name)) {
				$located = STYLESHEETPATH . '/' . $template_name;
				break;
			} else if ( file_exists(TEMPLATEPATH . '/' . $template_name) ) {
				$located = TEMPLATEPATH . '/' . $template_name;
				break;
			}
		}

		if ( $load && '' != $located )
			self::pls_load_template( $located, $require_once );

		return $located;
	}


	private static function pls_load_template( $_template_file, $require_once = true )
	{
		global $posts, $post, $wp_did_header, $wp_did_template_redirect, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		if ( is_array( $wp_query->query_vars ) )
			extract( $wp_query->query_vars, EXTR_SKIP );

		if ( $require_once )
			require_once( $_template_file );
		else
			require( $_template_file );
	}

}


 ?>