<?php
/* Load the Placester Blueprint Theme Framework. */
require_once( trailingslashit( TEMPLATEPATH ) . 'blueprint/blueprint.php' );
new Placester_Blueprint();

/**
 * Any modifications to its behavior (add/remove support for features, define 
 * constants etc.) must be hooked in 'after_setup_theme' with a priority of 10 if the
 * framework is a parent theme or a priority of 11 if the theme is a child theme. This 
 * allows the class to add or remove theme-supported features at the appropriate time, 
 * which is on the 'after_setup_theme' hook with a priority of 12.
 * 
 */
// add_action( 'after_setup_theme', 'pls_setup', 10 );
// function pls_setup() {

    // add like this:
    // add_theme_support( 'pls-slideshow', array( 'script', 'style' ) );
    // add_theme_support( 'pls-maps-util');

    // remove like this:
    // remove_theme_support( 'pls-sidebars' );
    // remove_theme_support( 'pls-slideshow' );
// }

/**
 * 	Filter the default prefix used in 'pls_do_atomic' and 'pls_apply_atomic'.
 * 	All add_filters that hook into events set by pls_do_atomic will need to catch
 * 	the prefix_event_name for example:
 *
 *	blueprint will mean that you need to hook against blueprint_close_header, or blueprint_open_header
 */

// add_filter( 'pls_prefix', 'blueprint_prefix' );
    // function blueprint_prefix() {
    //     return 'blueprint';
// }

