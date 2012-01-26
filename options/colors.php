<?php 

$background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat','position' => 'top center','attachment'=>'scroll');

PLS_Style::add(array( 
            "name" => "Colors & Buttons",
            "type" => "heading"));

PLS_Style::add(array( 
			"name" => "General",
			"type" => "info",
			"desc" => "testsetsetet"));


PLS_Style::add(array( 
			"name" =>  "Example Background",
			"desc" => "Change the background CSS.",
			"id" => "example_background",
			"std" => $background_defaults, 
			"type" => "background"));

PLS_Style::add(array( 
			"name" =>  "Button Style Options",
			"desc" => "The follow options apply to buttons accross your site",
			"type" => "info"));

PLS_Style::add(array( 
			"name" =>  "Button Text Size & Color",
			"desc" => "Change the background CSS.",
			"id" => "pls-all-button-text",
			"selector" => "body a.more-link", 
			"std" => $background_defaults, 
			"type" => "typography"));

PLS_Style::add(array( 
			"name" =>  "Button Rounded Corners",
			"desc" => "The background color of all the buttons",
			"id" => "pls-all-button-radius",
			"selector" => "body a.more-link",
			"class" => "mini",
			"type" => "text"));						

PLS_Style::add(array( 
			"name" =>  "Button Background Color",
			"desc" => "The background color of all the buttons",
			"id" => "pls-all-button-background-color",
			"style" => "background-color",
			"selector" => "body a.more-link", 
			"type" => "color"));			

PLS_Style::add(array( 
			"name" =>  "Button Hover Text Size & Color",
			"desc" => "Change the background CSS.",
			"id" => "pls-all-button-hover-text",
			"selector" => "body a.more-link:hover", 
			"std" => $background_defaults, 
			"type" => "typography"));			

PLS_Style::add(array( 
			"name" =>  "Button Background Color",
			"desc" => "The background color of all the buttons",
			"id" => "pls-all-button-background-color-hover",
			"style" => "background-color",
			"selector" => "body a.more-link:hover", 
			"type" => "color"));			





// Buttons (Color, Text Size, padding)




// Borders (color, width, radius)

