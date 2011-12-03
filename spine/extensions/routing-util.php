<?php 

PLS_Route::init();

class PLS_Route {

	static $request;

	/**
	 * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	 */
	static $base;

	static $debug_messages = array();

	// hooks take care of everything, developer has full control over
	// file system.
	function init()
	{
		
		// hooks into template_routing for auto wrapping header
		// and footer. Not used now. Will revisit when we add
		// support for automated routing.
		//
		//add_action( 'template_redirect', array( __CLASS__, 'wrap' ));
		 add_filter( 'template_include', array( __CLASS__, 'routing_logic' ) );

		
		// for catching specific templates
		// 
		add_action( '404_template', array( __CLASS__, 'handle_404'  ), 10, 1);
		add_action( 'search_template', array( __CLASS__, 'handle_search'  ), 10, 1);
		// Taxonomy: No need to touch taxonomy pages.
		add_action( 'home_template', array( __CLASS__, 'handle_home'  ), 10, 1);	
		// Front Page: No need to touch "Front Page", we'll assume home.
		// Attachments: Need to figure out what to do with attachments
		add_action( 'attachment_template', array( __CLASS__, 'handle_attachment'  ), 10, 1);	
		add_action( 'archive_template', array( __CLASS__, 'handle_archive'  ), 10, 1);	
		// Comments: Can't override wordpress's need for a comments file
		add_action( 'single_template', array( __CLASS__, 'handle_single'  ), 10, 1);	
		add_action( 'page_template', array( __CLASS__, 'handle_page'  ), 10, 1);	
		add_action( 'category_template', array( __CLASS__, 'handle_category'  ), 10, 1);	
	}

	function routing_logic ($template)
	{
		
		// debug messages;
		self::add_msg("routing_logic used.....");
		self::add_msg("We've recorded the request as: " . self::$request);
		self::add_msg('Wordpress wants:' . $template);
		
		$new_template = '';

		// if wrapping is true. TODO: Make wrapping optional.
		if (true) {
			// fire wrapper
			self::wrapper();
			// if wrapper is used, it will handle the proper
			// loading. returning blank will clear the filter
			// causing no additional pages to be included. 
			
		} else {
			// check the request var to see what template is being
			// requested, load that template if theme, child, or blueprint
			// has it. Handle dynamic does this naturally. 

			$new_template = self::handle_dynamic();
		}		

	
		pls_dump(self::$debug_messages);

		return $new_template;
	}


	// checks for a user defined file, if not present returns the required blueprint template.

	// direct copy + paste of WP's locate function
	// modified to alternate searching for the dev's
	// templates, then look for blueprints.
	function router ($template_names, $load = false, $require_once = true) 
	{

		self::add_msg('Hit Router! Searching for: ');
		self::add_msg($template_names);

		$located = self::locate_blueprint_template($template_names);

		self::add_msg('Template Located: ' . $located);

		if ( $load && '' != $located  ) {
			self::add_msg('Load Requested: ' . $located);
			load_template( $located, $require_once);
		}
			
		return $located;
		
	}

	// determines which file to load
	// broken off from router so it can be reused. 
	function locate_blueprint_template ($template_names)
	{
		$located = '';

		foreach ( (array) $template_names as $template_name ) {
			if ( !$template_name )
				continue;
			if ( file_exists(STYLESHEETPATH . '/' . $template_name)) {
				$located = STYLESHEETPATH . '/' . $template_name;
				break;
			} else if ( file_exists(PLS_TPL_DIR . '/' . $template_name) ) {
				$located = PLS_TPL_DIR . '/' . $template_name;
				break;
			}
		}
		return $located;
	}
	
	// displays the html of a given page. 
	//
	// needs to be updated so it can be safely overwritten 
	// by dropping in a properly named file into the theme root. 
	function get_template_part($slug, $name = null)
	{
		self::add_msg('Get Template Requested for: ' . $slug . ' and ' . $name);
		do_action( "get_template_part_{$slug}", $slug, $name );

		$templates = array();
		if ( isset($name) )
			$templates[] = "{$slug}-{$name}.php";

		$templates[] = "{$slug}.php";

		self::router($templates, true, false);
	}


	//
	//	Public utility functions that can be used to intellegently
	//	request the correct template. These will naturally use the
	// 	the router which respects templates from the theme, and 
	// 	child theme before falling back to blueprint
	//

	function handle_dynamic() {
		return self::router(self::$request, true);
	}

	function handle_header() {
		// Header is loaded directly rather then
		// being set as a request and then looping
		// the routing table.
		//
		return self::router('header.php', true);
	}

	function handle_sidebar() {
		// Sidebar is loaded directly rather then
		// being set as a request and then looping
		// the routing table.
		//
		return self::router('sidebar.php', true);
	}

	function handle_footer() {
		// Footer is loaded directly rather then
		// being set as a request and then looping
		// the routing table.
		//
		return self::router('footer.php', true);
	}

	// hooked to 404
	function handle_404($template) {
		
		// sets the request for the standard 404
		// template
		self::$request = '404.php';
	}

	// hooked to search
	function handle_search($template) {
		
		// sets the request for the standard search
		// template
		self::$request = 'search.php';
	}

	// hooked to home + index
	function handle_home($template) {

		//check for index.php, same hook as home.
		if ( strrpos($template, 'index.php')) {
			self::$request = 'index.php';
		} else {
			self::$request = 'home.php';
		}			
	}

	// attachment pages, not sure what to do with this.
	// needs some additional logic so blueprint can handle
	// all the different template types
	function handle_attachment() {
		self::$request = 'attachment.php';
	}

	// needs additional logic to handle different types of 
	// post type archives. 
	function handle_archive($template) {
		self::$request = 'archive.php';
		// return self::router('archive.php');
	}

	// hooked to handle single templates
	function handle_single($template) {
		
		self::$request = 'single.php';
		// return self::router('single.php');
	}

	// hooked to handle page templates
	function handle_page($template) {
		$id = get_queried_object_id();
		$template = get_post_meta($id, '_wp_page_template', true);
		$pagename = get_query_var('pagename');

		if ( !$pagename && $id > 0 ) {
			// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
			$post = get_queried_object();
			$pagename = $post->post_name;
		}

		if ( 'default' == $template )
			$template = '';

		$templates = array();
		if ( !empty($template) && !validate_file($template) )
			$templates[] = $template;
		if ( $pagename )
			$templates[] = "page-$pagename.php";
		if ( $id )
			$templates[] = "page-$id.php";
		$templates[] = 'page.php';

		self::$request = $templates;
		// return self::router($templates, 'page');
	}

	// hooked to handle page templates
	function handle_category($template) {
		self::$request = 'category.php';
		// return self::router($template, 'category')	;
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
	static function wrapper() {
			
		self::add_msg('Wrapper used..');

		
		self::$base = substr( basename( self::$request), 0, -4 );

		if ( 'index' == self::$base )
			self::$base = false;

		$templates = array( 'wrapper.php' );

        /**
         *  Looks for wrapper-[base].php and then for spine/wrappers/wrapper-[base].php
         */
		if ( self::$base )
			array_unshift( $templates, sprintf( 'wrapper-%s.php', self::$base ), sprintf( '/spine/wrappers/wrapper-%s.php', self::$base ) );

		
		// if wrapper is being used, it will load attempt
		// to load the various wrapper iterations.
		// wrapper needs to have PLS_Route::handle_dynamic to 
		// actually load the requested page after wrapper is 
		// loaded. 
		return self::router( $templates, true );
	}


	static function add_msg ($new_message)
	{
		self::$debug_messages[] = $new_message;
	}


// end class	
}

 ?>