<?php  

$background_defaults = array('color' => '', 'image' => '', 'repeat' => '', 'position' => '', 'attachment'=> 'scroll');
$typography_defaults = array('color' => '', 'face' => '', 'size' => '', 'style' => '');


PLS_Style::add(array( 
    "name" => "Navigation Styles",
    "type" => "heading"));

      // NEED TO CHANGE GRADIENTS IN THEME
     // Add single CSS option for change to site
     PLS_Style::add(array( 
           // div title in Theme Options Menu
           "name" =>  "Navigation Background Color",
           // div descrition in Theme Options Menu
           "desc" => "Change the site's nav bar color.",
           // div id in Theme Options Menu
           "id" => "h1_title",
           //
           "std" => $background_defaults,
           // selector of targeted tag being changed
           "selector" => "header .primary, header .primary ul",
           // Theme Options Tab (type) which holds option being changed.
           // examples: text, textarea, radio, images, textbox, multicheck,
           // color, upload, typography, background, info, heading
           "type" => "background"));

      PLS_Style::add(array( 
          "name" =>  "Navigation Links",
          "desc" => "Change the site navigation links.",
          "id" => "nav_links",
          "std" => $typography_defaults,
          "selector" => "header .primary ul li a",
          "type" => "typography"));

      // NEED TO CHANGE GRADIENTS IN THEME
      PLS_Style::add(array( 
          "name" =>  "Navigation Active Link",
          "desc" => "Change the site navigation's single current link.",
          "id" => "nav_active_link",
          "std" => $background_defaults,
          "selector" => "header .primary ul li.current_page_item",
          "type" => "background"));















