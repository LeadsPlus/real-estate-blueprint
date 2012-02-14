<?php
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */


// standard path.
$imagepath =  trailingslashit( PLS_EXT_URL ) . 'options-framework/images/';


PLS_Style::add(array( 
		"name" => "Advanced Options",
		"type" => "heading"));

PLS_Style::add(array(
		"name" => "Activate general controls options",
		"desc" => "Allows you to set general controls over the layout of your site. Will override any theme defaults. Active by default.",
		"id" => "pls-color-options",
		"std" => "1",
		"type" => "checkbox"));

PLS_Style::add(array(
		"name" => "Activate global typography options",
		"desc" => "Allows you to set the typography across the entire site. Will override the default theme styles. Activated by default.",
		"id" => "pls-typography-options",
		"std" => "1",
		"type" => "checkbox"));

PLS_Style::add(array(
		"name" => "Activate header style options",
		"desc" => "Allows you to style the header of your website. The header appears at the top of every page. Will override default theme styles. Activated by default.",
		"id" => "pls-header-options",
		"std" => "1",
		"type" => "checkbox"));

PLS_Style::add(array(
		"name" => "Activate navigation style options",
		"desc" => "Allows you to style the navigation area of your website. The navigation appears below the header at the top of every page. Will override default theme styles. Activated by default.",
		"id" => "pls-navigation-options",
		"std" => "1",
		"type" => "checkbox"));

PLS_Style::add(array(
		"name" => "Activate listing style options",
		"desc" => "Allows you to style the way listings are displayed. In both search results and details pages. Will override theme defaults. Activated by default.",
		"id" => "pls-listing-options",
		"std" => "1",
		"type" => "checkbox"));

PLS_Style::add(array(
		"name" => "Activate blog style options",
		"desc" => "Allows you to style the way blog posts and pages are displayed. Will override theme defaults. Activated by default.",
		"id" => "pls-post-options",
		"std" => "1",
		"type" => "checkbox"));

PLS_Style::add(array(
		"name" => "Activate widget style options",
		"desc" => "Allows you to style the way sidebar widgets are displayed. Will override theme defaults. Activated by default.",
		"id" => "pls-widget-options",
		"std" => "1",
		"type" => "checkbox"));

PLS_Style::add(array(
		"name" => "Activate custom css options",
		"desc" => "Allows you to enter custom css directly. Will override theme defaults, as well as any options you've set",
		"id" => "pls-css-options",
		"std" => "1",
		"type" => "checkbox"));

PLS_Style::add(array(
		"name" => "Display Theme Debug Messages",
		"desc" => "Display the theme debug panel at the bottom of all non-admin pages. Great for debugging issues with the themes.",
		"id" => "display-debug-messages",
		"std" => "0",
		"type" => "checkbox"));
