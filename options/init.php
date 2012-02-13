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
                "id" => "pls-favicon",
                "type" => "upload"));

PLS_Style::add(array( 
                "name" => "Google Analytics Tracking Code",
                "desc" => "Add your google analytics tracking code here. It will be loaded just before the </body> tag in your site. Copy and paste your Google Analytics code, including the script tags.",
                "id" => "pls-google-analytics",
                "type" => "textarea"));

           