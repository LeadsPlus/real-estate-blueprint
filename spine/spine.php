<?php
/**
 * Placester Spine - A Wordpress theme development framework that integrates 
 * with the Placester Real Estate Pro plugin
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * ( at your option ) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * Credit:
 * Big thanks to Justin Tadlock for inspiration with his HybridCore Framework.
 *
 * @package PlacesterSpine
 * @version 0.0.1 
 * @author Placester, Alex Ciobica, Matt Barba
 * @link http://placester.com TODO: Update
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * This class initializes the framework. It should be loaded and initialized before 
 * anything else within the theme is called to properly use the framework.  
 *
 * Any modifications to its behavior (add/remove support for features, define 
 * constants etc.) must be hooked in 'after_setup_theme' with a priority of 10 if the
 * framework is a parent theme or a priority of 11 if the theme is a child theme. This 
 * allows the class to add or remove theme-supported features at the appropriate time, 
 * which is on the 'after_setup_theme' hook with a priority of 12.
 *
 * @since 0.0.1
 */
class Placester_Spine {

	/**
     * Constructor method for the Placester_Spine class. This method adds other methods of 
     * the class to specific hooks within WordPress. It controls the load order 
     * of the required files for running the framework.
	 *
	 * @since 0.0.1
	 */
	function __construct() {

        /** Let the world know that this is a Placester theme. */
        global $i_am_a_placester_theme;
        $i_am_a_placester_theme = TRUE;

        global $placester_spine;

        /** Set the plugin error flag. */
        $placester_spine->has_plugin_error = $this->_has_plugin_error(); 

		/** Define the famework constants. */
		add_action( 'after_setup_theme', array( &$this, 'constants' ), 1 );

		/** Load the framework's core functions. */
		add_action( 'after_setup_theme', array( &$this, 'core' ), 2 );

		/** Initialize the framework's default actions and filters. */
		add_action( 'after_setup_theme', array( &$this, 'default_filters' ), 3 );

		/** Add default theme support. */
		add_action( 'after_setup_theme', array( &$this, 'default_theme_support' ), 4 );

		/** Language functions and translations setup. */
		add_action( 'after_setup_theme', array( &$this, 'locale' ), 5 );

		/** Load the framework components. */
		add_action( 'after_setup_theme', array( &$this, 'components' ), 12 );

        /* Load the framework extensions. */
		add_action( 'after_setup_theme', array( &$this, 'extensions' ), 13 );

        /** Create the listings and blog pages. */
        $this->create_pages();
	}

    private function _has_plugin_error() {

        $plugin_status = $this->_is_plugin_active();

        if ( function_exists( 'placester_get_api_key' ) &&  $plugin_status ) {
            try {
                placester_get_api_key();
            }
            catch ( PlaceSterNoApiKeyException $e ) {
                return 'no_api_key';
            }
        } else {
            return 'no_plugin';
        }

        return false;
    }

    /**
     * Verifies if the Placester plugin is activate. 
     * 
     * @access private
     * @return bool TRUE if the plugin is active, FALSE otherwise.
     * @since 0.0.1
     */
    private function _is_plugin_active() {

        $plugin = 'placester/placester.php';

        return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || $this->_is_plugin_active_for_network( $plugin );
    }

    private function _is_plugin_active_for_network ($plugin)
    {
            if ( !is_multisite() )
                return false;

            $plugins = get_site_option( 'active_sitewide_plugins');
            if ( isset($plugins[$plugin]) )
                return true;

            return false;
    }

    /**
     * Verifies if the Placester plugin has an api key set.
     * 
     * @access private
     * @return bool TRUE if the plugin has an api key, FALSE otherwise.
     * @since 0.0.1
     */
    private function _has_plugin_api_key() {

        if ( function_exists( 'placester_get_api_key' ) ) {
            try {
                placester_get_api_key();
            }
            catch ( PlaceSterNoApiKeyException $e ) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

	/**
	 * Defines the theme framework constants.
	 *
	 * @since 0.0.1
	 */
	function constants() {

        /** Placester Spine Version */
        define( 'PLS_VERSION', '0.0.1' );

        /** Parent theme directory path and url */
        define( 'PARENT_DIR', get_template_directory() );
        define( 'PARENT_URL', get_template_directory_uri() );

        /** Child theme directory path and url */
        define( 'CHILD_DIR', get_stylesheet_directory() );
        define( 'CHILD_URL', get_stylesheet_directory_uri() );

        /** Placester Spine directory path and url */
        define( 'PLS_DIR', trailingslashit( PARENT_DIR ) . 'spine' );
        define( 'PLS_URL', trailingslashit( PARENT_URL ) . 'spine' );
        
        /** Scripts directory path and url */
        define( 'PLS_JS_DIR', trailingslashit( PLS_DIR ) . 'js' );
        define( 'PLS_JS_URL', trailingslashit( PLS_URL ) . 'js' );

        /** Styles directory path and url */
        define( 'PLS_CSS_DIR', trailingslashit( PLS_DIR ) . 'css' );
        define( 'PLS_CSS_URL', trailingslashit( PLS_URL ) . 'css' );

        /** Extensions directory path and url */
        define( 'PLS_EXT_DIR', trailingslashit( PLS_DIR ) . 'extensions' );
        define( 'PLS_EXT_URL', trailingslashit( PLS_URL ) . 'extensions' );

        /** Template directory path and url */
        define( 'PLS_TPL_DIR', trailingslashit( PLS_DIR ) . 'templates' );
        define( 'PLS_TPL_URL', trailingslashit( PLS_URL ) . 'templates' );

        /** Styles directory path and url */
        define( 'PLS_IMG_DIR', trailingslashit( PLS_DIR ) . 'i' );
        define( 'PLS_IMG_URL', trailingslashit( PLS_URL ) . 'i' );

        /** Functions directory path and url */
        define( 'PLS_FUNCTIONS_DIR', trailingslashit( PLS_DIR ) . 'functions' );
        define( 'PLS_FUNCTIONS_URL', trailingslashit( PLS_URL ) . 'functions' );
        
        /** Languages directory path and url only if not already defined. */
        if ( ! defined( 'PLS_LANGUAGES_DIR' ) ) /** So it can be defined by the theme developer. */
            define( 'PLS_LANGUAGES_DIR', trailingslashit( PLS_DIR ) . 'languages' );
        if ( ! defined( 'PLS_LANGUAGES_URL' ) ) /** So it can be defined by the theme developer. */
            define( 'PLS_LANGUAGES_URL', trailingslashit( PLS_URL ) . 'languages' );

        define( 'PLS_WIDGETS_URL', trailingslashit( PLS_URL ) . 'widgets' );
        define( 'PLS_WIDGETS_DIR', trailingslashit( PLS_DIR ) . 'widgets' );

	}

	/**
	 * Adds default theme support.
	 *
	 * @since 0.0.1
	 */
    function default_theme_support() {

        /** Add theme support for theme wrappers */
        add_theme_support( 'pls-routing-util' );

        /** Add theme support for menus */
        add_theme_support( 'pls-menus', array( 'primary', 'subsidiary' ) );

        /** Add theme support for sidebars */
        add_theme_support( 'pls-sidebars', array( 'primary', 'subsidiary' ) );

        // Adds default styling out of the box
        add_theme_support( 'pls-default-style' );
        add_theme_support( 'pls-js', array( 'chosen' => array( 'script' => true, 'style' => true ) ) );
        add_theme_support( 'pls-theme-options' );
        add_theme_support( 'pls-image-util', array('fancybox') );
        add_theme_support( 'pls-slideshow', array( 'script', 'style' ) );
        add_theme_support( 'pls-maps-util');
        add_theme_support( 'pls-style-util');
        add_theme_support( 'pls-debug');

    }

	/**
	 * Adds the default framework actions and filters.
	 *
	 * @since 0.0.1 
	 */
	function default_filters() {

		/** Move the WordPress generator to a better priority. */
		remove_action( 'wp_head', 'wp_generator' );
		add_action( 'wp_head', 'wp_generator', 1 );

        /** Remove plugin scripts. */
        define( 'PL_NO_SCRIPTS', 100 );

		/** Make text widgets and term descriptions shortcode aware. */
		add_filter( 'widget_text', 'do_shortcode' );
		add_filter( 'term_description', 'do_shortcode' );
	}

	/**
	 * Handles the locale functions file and translations.
	 *
     * @uses load_theme_textdomain() Loads the theme's translated strings.
	 * @since 0.0.1
	 */
	function locale() {

		/** Load theme textdomain. Filterable  */
        load_theme_textdomain( pls_get_textdomain(), PLS_LANGUAGES_DIR );

		/** Get the user's locale. */
        $locale = get_locale();
	}

	/**
	 * Loads the framework core files.
	 *
     * @uses trailingslashit() Appends a trailing slash.
	 * @since 0.0.1
	 */
	function core() {
        
        /** Load the core functions */
        require_once( trailingslashit ( PLS_FUNCTIONS_DIR ) . 'core.php' );

        /** Load the html functions */
        require_once( trailingslashit ( PLS_FUNCTIONS_DIR ) . 'html.php' );
	}

	/**
	 * Loads the framework component files.
	 *
     * @uses trailingslashit() Appends a trailing slash.
     * @uses require_if_theme_supports() Adds a feature only if theme supports it.
	 * @since 0.0.1
	 */
	function components() {
        
        /** Load the utility functions. */
        require_once( trailingslashit ( PLS_FUNCTIONS_DIR ) . 'util.php' );

        /** Load the compatibility class. */
        require_once( trailingslashit ( PLS_FUNCTIONS_DIR ) . 'compatibility.php' );

        /** Load the partials. */
        require_once( trailingslashit ( PLS_FUNCTIONS_DIR ) . 'partials.php' );

        /** Load the sidebars. */
        require_once( trailingslashit ( PLS_FUNCTIONS_DIR ) . 'sidebars.php' );

        /** Load the widgets. */
        require_once( trailingslashit ( PLS_FUNCTIONS_DIR ) . 'widgets.php' );

        /** Load the styles functions. */
        require_once( trailingslashit ( PLS_CSS_DIR ) . 'styles.php' );

        /** Load the scripts functions. */
        require_once( trailingslashit ( PLS_JS_DIR ) . 'scripts.php' );

        /** Load the menus if supported. */
        require_if_theme_supports( 'pls-menus', trailingslashit ( PLS_FUNCTIONS_DIR ) . 'menus.php' );

        /** Load the theme wrapping functionality if supported. */
        require_if_theme_supports( 'pls-theme-wrappers', trailingslashit ( PLS_FUNCTIONS_DIR ) . 'structure.php' );

        /** Load the debug functionality if supported. */
        require_if_theme_supports( 'pls-debug', trailingslashit ( PLS_FUNCTIONS_DIR ) . 'debug.php' );
	}

	/**
	 * Loads the framework extension files. Extensions are pieces of 
     * functionality that are conceptually sepparated by the framework core. 
     * Themes must add support with add_theme_support( 'extension-name' );
	 *
     * @uses trailingslashit() Appends a trailing slash.
     * @uses require_if_theme_supports() Adds a feature only if theme supports it.
	 * @since 0.0.1
	 */
	function extensions() {

        /** Load the Routing Util extension if supported. */
        require_if_theme_supports( 'pls-routing-util', trailingslashit ( PLS_EXT_DIR ) . 'routing-util.php' );

        /** Load the Slideshow extension if supported. */
        require_if_theme_supports( 'pls-slideshow', trailingslashit ( PLS_EXT_DIR ) . 'slideshow.php' );

        /** Load the Options Framework extension if supported. */
        require_if_theme_supports( 'pls-theme-options', trailingslashit ( PLS_EXT_DIR ) . 'options-framework.php' );

        /** Load the Options Framework extension if supported. */
        require_if_theme_supports( 'pls-image-util', trailingslashit ( PLS_EXT_DIR ) . 'image-util.php' );

        /** Load the Maps Util extension if supported. */
        require_if_theme_supports( 'pls-maps-util', trailingslashit ( PLS_EXT_DIR ) . 'maps-util.php' );

        /** Load the Style Util extension if supported. */
        require_if_theme_supports( 'pls-maps-util', trailingslashit ( PLS_EXT_DIR ) . 'style-util.php' );
	}

    /**
     * On activation, creates the blog and listings pages and assignes them 
     * templates.
     *
     * @since 0.0.1
     */
    function create_pages() {

       global $pagenow;

       if ( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' ) {

            $page_list[] = array( 'title' => 'Blog', 'template' => 'page-template-blog.php' );
            $page_list[] = array( 'title' => 'Listings', 'template' => 'page-template-listings.php' );

            if (function_exists('placester_create_pages'))
                placester_create_pages( $page_list ); 
        } 
    }
}
