<?php 

PLS_Style::add(array( 
    "name" => "Colors & Buttons",
    "type" => "heading"));


PLS_Style::add(array(
    "name" => "General",
    "type" => "info",
    "desc" => "The follow options apply to attributes across your site"));


// Add single CSS option for change to site
PLS_Style::add(array( 
			// div title in Theme Options Menu
			"name" =>  "Example Background",
			// div descrition in Theme Options Menu
			"desc" => "Change the background CSS.",
			// div id in Theme Options Menu
			"id" => "example_background",
			//
			"std" => $background_defaults,
			// selector of targeted tag being changed
			"selector" => "",
			// Theme Options Tab (type) which holds option being changed.
			// examples: text, textarea, radio, images, textbox, multicheck,
			// color, upload, typography, background, info, heading
			"type" => "background"));
