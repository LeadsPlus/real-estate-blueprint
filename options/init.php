<?php
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

// standard path.
$imagepath =  trailingslashit( PLS_EXT_URL ) . 'options-framework/images/';


// Colors    
    PLS_Style::add(array( 
            "name" => "Body Styles",
            "type" => "heading"));

    
        PLS_Style::add(array( 
            "name" => "Fonts: Color and Sizes",
            "desc" => "Use this to change the fonts, and font sizes, for the entire site. These settings will be overwritten by other font & sizing settings for specific areas.",
            "type" => "info"));

        PLS_Style::add(array(
            "name" => "Typography",
            "desc" => "Example typography.",
            "id" => "body.font",
            "std" => "",
            "type" => "typography"));

        PLS_Style::add(array(
            "name" => "Colors",
            "desc" => "",
            "type" => "info"));

        PLS_Style::add(array(
            "name" => "Background Color",
            "desc" => "Background color of the document. White by default",
            "id" => "body.background_color",
            "std" => "",
            "style" => "background-color",
            "type" => "color"));

        PLS_Style::add(array( 
            "name" => "Link Color",
            "id" => "link.color",
            "std" => "",
            "selector" => "a:link",
            "style" => "color",
            "important" => false, 
            "type" => "color"));

        PLS_Style::add(array(
            "name" => "Link Visted Color",
            "id" => "visited.color",
            "std" => "",
            "selector" => "a:visited",
            "style" => "color",
            "important" => false, 
            "type" => "color"));


// header stuff
    PLS_Style::add(array(
        "name" => "Header Styles",
        "type" => "heading"));
    
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


// Layout
    PLS_Style::add(array(
        "name" => "Layout Test",
        "type" => "heading"));


        PLS_Style::add(array(
            "name" => "Example Image Selector",
            "desc" => "Images for layout.",
            "id" => "layout-test",
            "std" => "2c-l-fixed",
            "type" => "images",
            "options" => array(
                '1col-fixed' => $imagepath . '1col.png',
                '2c-l-fixed' => $imagepath . '2cl.png',
                '2c-r-fixed' => $imagepath . '2cr.png')
            ));
    
    PLS_Style::add(array(
        "name" => "Theme Utilities",
        "type" => "heading"));


        PLS_Style::add(array(
            "name" => "Display Theme Debug Messages",
            "desc" => "Display the theme debug pannel at the bottom of all non-admin pages. Great for debugging issues with the themes.",
            "id" => "display-debug-messages",
            "std" => "0",
            "type" => "checkbox"));