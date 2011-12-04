<?php
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */
    
    PLS_Style::add(array( 
            "name" => "Body Styles",
            "type" => "heading"));

    
    PLS_Style::add(array( "name" => "Fonts: Color and Sizes",
        "desc" => "Use this to change the fonts, and font sizes, for the entire site. These settings will be overwritten by other font & sizing settings for specific areas.",
        "type" => "info"));

    PLS_Style::add(array( "name" => "Typography",
        "desc" => "Example typography.",
        "id" => "body.font",
        "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
        "type" => "typography"));

    PLS_Style::add(array( "name" => "Colors",
        "desc" => "",
        "type" => "info"));

    PLS_Style::add(array( "name" => "Background Color",
        "desc" => "Background color of the document. White by default",
        "id" => "body.background_color",
        "std" => "",
        "style" => "background-color",
        "type" => "color"));

    PLS_Style::add(array( "name" => "Link Color",
        "id" => "link.color",
        "std" => "",
        "selector" => "a:link",
        "style" => "color",
        "important" => false, 
        "type" => "color"));

    PLS_Style::add(array( "name" => "Link Visted Color",
        "id" => "visited.color",
        "std" => "",
        "selector" => "a:visited",
        "style" => "color",
        "important" => false, 
        "type" => "color"));

    PLS_Style::add(array( "name" => "Header Styles",
                "type" => "heading"));
    
    PLS_Style::add(array( "name" => "H1 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h1.styles",
            "selector" => "h1",
            "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
            "type" => "typography"));
    
    PLS_Style::add(array( "name" => "H2 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h2.styles",
            "selector" => "h2",
            "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
            "type" => "typography"));

    PLS_Style::add(array( "name" => "H3 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h3.styles",
            "selector" => "h3",
            "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
            "type" => "typography"));

    PLS_Style::add(array( "name" => "H4 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h4.styles",
            "selector" => "h4",
            "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
            "type" => "typography"));

    PLS_Style::add(array( "name" => "H5 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h5.styles",
            "selector" => "h5",
            "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
            "type" => "typography"));

