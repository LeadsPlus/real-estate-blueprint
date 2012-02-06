<?php 

$background_defaults = array('color' => '', 'image' => '', 'repeat' => '', 'position' => '','attachment'=>'scroll');

PLS_Style::add(array( 
		"name" => "Header Styles",
		"type" => "heading"));



		// Add single CSS option for change to site
		PLS_Style::add(array( 
					// div title in Theme Options Menu
					"name" =>  "H1 Title",
					// div descrition in Theme Options Menu
					"desc" => "Change main site title's size, font-family, styling, and color.",
					// div id in Theme Options Menu
					"id" => "h1_title",
					//
					"std" => $background_defaults,
					// selector of targeted tag being changed
					"selector" => "header h1 a",
					// Theme Options Tab (type) which holds option being changed.
					// examples: text, textarea, radio, images, textbox, multicheck,
					// color, upload, typography, background, info, heading
					"type" => "typography"));


		PLS_Style::add(array( 
					"name" =>  "H2 Subtitle",
					"desc" => "Change the site subtitle's size, font-family, styling, and color.",
					"id" => "h2_subtitle",
					"std" => $background_defaults,
					"selector" => "header h2",
					"type" => "typography"));


		PLS_Style::add(array( 
					"name" =>  "Header Email",
					"desc" => "Change the header's email size, font-family, styling, and color.",
					"id" => "header_email",
					"std" => $background_defaults,
					"selector" => ".h-email a",
					"type" => "typography"));


		PLS_Style::add(array( 
					"name" =>  "Header Phone",
					"desc" => "Change the header's phone size, font-family, styling, and color.",
					"id" => "header_phone",
					"std" => $background_defaults,
					"selector" => ".h-phone",
					"type" => "typography"));
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					