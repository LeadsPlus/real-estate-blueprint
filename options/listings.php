<?php 

$background_defaults = array('color' => '', 'image' => '', 'repeat' => '', 'position' => '', 'attachment'=> 'scroll');
$typography_defaults = array('color' => '', 'face' => '', 'size' => '', 'style' => '');


PLS_Style::add(array( 
    "name" => "Listing Styles",
    "type" => "heading"));
            

     // NEED TO CHANGE GRADIENTS IN THEME
    // Add single CSS option for change to site
    PLS_Style::add(array( 
          // div title in Theme Options Menu
          "name" =>  "Listing Section Title",
          // div descrition in Theme Options Menu
          "desc" => "Change the listing section's headline title",
          // div id in Theme Options Menu
          "id" => "listings_section_title",
          //
          "std" => $typography_defaults,
          // selector of targeted tag being changed
          "selector" => "#main #listing h3",
          // Theme Options Tab (type) which holds option being changed.
          // examples: text, textarea, radio, images, textbox, multicheck,
          // color, upload, typography, background, info, heading
          "type" => "typography"));

    PLS_Style::add(array( 
          "name" =>  "Listing Section Title Background",
          "desc" => "Change the listing section's headline title's background",
          "id" => "listings_section_title_background",
          "std" => $typography_defaults,
          "selector" => "#main #listing h3, body.page-template-page-template-listings-php #main h3",
          "type" => "background"));

    PLS_Style::add(array(
          "name" =>  "Listing Titles/Addresses",
          "desc" => "Change the typography of individual listing titles/addresses",
          "id" => "listings_titles_addresses",
          "std" => $typography_defaults,
          "selector" => "#listing .lu-right h4 a, #placester_listings_list .lu-right h4 a",
          "type" => "typography"));

    PLS_Style::add(array(
          "name" =>  "Listing Titles/Addresses - on hover",
          "desc" => "Change the typography of individual listing titles/addresses on hover",
          "id" => "listings_titles_addresses_on_hover",
          "std" => $typography_defaults,
          "selector" => "#listing .lu-right h4 a:hover, #placester_listings_list .lu-right h4 a:hover",
          "type" => "typography"));

    PLS_Style::add(array(
          "name" =>  "'Request More Information' Link",
          "desc" => "Change the typography of the 'Request More Information' links in each listing.",
          "id" => "listings_request_more_info",
          "std" => $typography_defaults,
          "selector" => "#listing .lu-right .lu-links a.info, #placester_listings_list .lu-right a.info",
          "type" => "typography"));



























