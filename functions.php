<?php
/* Load the Placester Spine Theme Framework. */
require_once( trailingslashit( TEMPLATEPATH ) . 'spine/spine.php' );
new Placester_Spine();

/**
 * Any modifications to its behavior (add/remove support for features, define 
 * constants etc.) must be hooked in 'after_setup_theme' with a priority of 10 if the
 * framework is a parent theme or a priority of 11 if the theme is a child theme. This 
 * allows the class to add or remove theme-supported features at the appropriate time, 
 * which is on the 'after_setup_theme' hook with a priority of 12.
 * 
 */
add_action( 'after_setup_theme', 'pls_setup', 10 );
function pls_setup() {

    add_theme_support( 'pls-default-style' );
    add_theme_support( 'pls-js', array( 'chosen' => array( 'script' => true, 'style' => true ) ) );
    add_theme_support( 'pls-theme-options' );
    add_theme_support( 'pls-image-util', array('fancybox') );
    add_theme_support( 'pls-slideshow', array( 'script', 'style' ) );
    add_theme_support( 'pls-maps-util');
    add_theme_support( 'pls-style-util');
    add_theme_support( 'pls-debug');
    
    // remove_theme_support( 'pls-sidebars' );
}

/**
 * 	Filter the default prefix used in 'pls_do_atomic' and 'pls_apply_atomic'.
 * 	All add_filters that hook into events set by pls_do_atomic will need to catch
 * 	the prefix_event_name for example:
 *
 *	spine will mean that you need to hook against spine_close_header, or spine_open_header
 */

add_filter( 'pls_prefix', 'spine_prefix' );
function spine_prefix() {
    return 'spine';
}

// custom header
@include_once( trailingslashit( TEMPLATEPATH ) . 'include/header.php' );

// include our custom footer widget
// include_once( trailingslashit( TEMPLATEPATH ) . 'include/footer.php' );
// PLS_Footer::init();

// custom single property page
@include_once( trailingslashit( TEMPLATEPATH ) . 'include/custom-property-page.php' );

// custom listings list
@include_once( trailingslashit( TEMPLATEPATH ) . 'include/custom-search-list.php' );

// custom slideshow output
@include_once( trailingslashit( TEMPLATEPATH ) . 'include/custom-home-slideshow.php' );

// custom slideshow output
@include_once( trailingslashit( TEMPLATEPATH ) . 'include/custom-property-slideshow.php' );

// custom listings list on the homepage
@include_once( trailingslashit( TEMPLATEPATH ) . 'include/custom-home-list.php' );

// custom listing widget
@include_once( trailingslashit( TEMPLATEPATH ) . 'include/custom-listings-widget.php' );

// custom agent widget
@include_once( trailingslashit( TEMPLATEPATH ) . 'include/agent-widget.php' );

// include our custom simple search widget
@include_once( trailingslashit( TEMPLATEPATH ) . 'include/simple-search.php' );
// PLS_Simple_Search::init();

// custom styles
@include_once( trailingslashit( TEMPLATEPATH ) . 'include/custom-styles.php' );


//custom stylesheets
add_action( 'template_redirect', 'pls_theme_styles' );
function pls_theme_styles() {
    wp_enqueue_style( 'main', get_stylesheet_directory_uri() . '/css/style.css' );
}

// custom scripts
add_action( 'template_redirect', 'pls_theme_scripts' );
function pls_theme_scripts() {
    wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/js/scripts.js', array( 'chosen', 'jquery' ), false, true );
}
