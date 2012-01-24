<?php 

    PLS_Style::add(array( 
            "name" => "Typography Options",
            "type" => "heading"));

    PLS_Style::add(array(
            "name" => "Paragraph Style Options",
            "desc" => "All the controls apply to all text on the page across the entire site. Your settings here will be overrided by more specific options you make below. For example, if you set the text color to be red here but below you set all headers to be blue - then your headers will be blue, while the rest of the text on your site will be blue.",
            "id" => "body.font",
            "std" => "",
            "type" => "typography"));



	PLS_Style::add(array(
            "name" => "Normal Link Styles",
            "desc" => "This is the base font style across your entire website.",
            "id" => "body.a",
            "std" => "",
            "type" => "typography"));


      PLS_Style::add(array(
            "name" => "Hover Link Styles",
            "desc" => "This is the base font style across your entire website.",
            "id" => "body.a:hover",
            "std" => "",
            "type" => "typography"));


      PLS_Style::add(array(
            "name" => "Visited Link Styles",
            "desc" => "This is the base font style across your entire website.",
            "id" => "pls-visited-link-styles",
            "selector" => "body.a:visited",
            "type" => "typography"));


    PLS_Style::add(array(
            "name" => "H1 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h1.styles",
            "selector" => "h1",
            "std" => "",
            "type" => "typography"));
        
        PLS_Style::add(array(
            "name" => "H2 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h2.styles",
            "selector" => "h2",
            "std" => "",
            "type" => "typography"));

        PLS_Style::add(array( 
            "name" => "H3 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h3.styles",
            "selector" => "h3",
            "std" => "",
            "type" => "typography"));

        PLS_Style::add(array( 
            "name" => "H4 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h4.styles",
            "selector" => "h4",
            "std" => "",
            "type" => "typography"));

        PLS_Style::add(array(
            "name" => "H5 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h5.styles",
            "selector" => "h5",
            "std" => array('size' => '','face' => '','style' => '','color' => ''),
            "type" => "typography")); 