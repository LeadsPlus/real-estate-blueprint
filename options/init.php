<?php
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */


// standard path.
$imagepath =  trailingslashit( PLS_EXT_URL ) . 'options-framework/images/';


PLS_Style::add(array(
		"name" => "General",
		"type" => "heading"));

		PLS_Style::add(array( 
				"name" => "Site Logo",
				"desc" => "Upload your logo here. It will appear in the header.",
				"id" => "pls-site-logo",
				"type" => "upload"));

		PLS_Style::add(array( 
				"name" => "Site Favicon",
				"desc" => "Upload your favicon here. It will appear in your visitors url and bookmark bar.",
				"id" => "pls-site-favicon",
				"type" => "upload"));

		PLS_Style::add(array( 
				"name" => "Slideshow Listings",
				"desc" => "Use the thing on the left to add properties to be displayed",
				"id" => "slideshow-featured-listings",
				"type" => "featured-listing"));

		PLS_Style::add(array( 
				"name" => "Google Analytics Tracking Code",
				"desc" => "Add your google analytics tracking code here. It will be loaded just before the </body> tag in your site. Copy and paste your Google Analytics code, including the script tags.",
				"id" => "pls-google-analytics",
				"type" => "textarea"));

		PLS_Style::add(array(
				"name" => "Display Theme Debug Messages",
				"desc" => "Display the theme debug panel at the bottom of all non-admin pages. Great for debugging issues with the themes.",
				"id" => "display-debug-messages",
				"std" => "0",
				"type" => "checkbox"));