<?php

PLS_Style::add(array( 
		"name" => "Listing Styles",
		"type" => "heading"));

		PLS_Style::add(array(
				"name" => "Listing Address Link",
				"desc" => "",
				"id" => "listing_address",
				"selector" => ".listing-item h3 a, h3 a:visited",
				"type" => "typography"));

		PLS_Style::add(array(
				"name" => "Listing Address link on hover",
				"desc" => "",
				"id" => "listing_address_hover",
				"selector" => ".listing-item h3 a:hover",
				"type" => "typography"));

		PLS_Style::add(array(
				"name" => "Listing Details",
				"desc" => "",
				"id" => "listing_details",
				"selector" => ".listing-item ul li, .listing-item .basic-details p",
				"type" => "typography"));

		PLS_Style::add(array(
				"name" => "Listing Image",
				"desc" => "",
				"id" => "listing_featured_image",
				"selector" => ".listing-item .listing-thumbnail img",
				"type" => "typography"));

		PLS_Style::add(array(
				"name" => "Listing Image Border",
				"desc" => "",
				"id" => "listing_image_border",
				"selector" => ".listing-item .listing-thumbnail img",
				"type" => "border"));

		PLS_Style::add(array(
				"name" => "Listing Image Background",
				"desc" => "",
				"id" => "listing_image_background",
				"selector" => ".listing-item .listing-thumbnail img",
				"type" => "background"));

		PLS_Style::add(array(
				"name" => "Listing Description",
				"desc" => "",
				"id" => "listing_description",
				"selector" => ".listing-item .listing-description",
				"type" => "typography"));

		PLS_Style::add(array(
				"name" => "Listing 'View Property Details' link",
				"desc" => "",
				"id" => "listing_view_details_link",
				"selector" => ".listing-item a.more-link",
				"type" => "typography"));