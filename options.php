<?php
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
    

    // Pull all the categories into an array
    $options_categories = array();  
    $options_categories_obj = get_categories();
    foreach ( $options_categories_obj as $category ) {
        $options_categories[$category->cat_ID] = $category->cat_name;
    }

    // Pull all the pages into an array
    $options_pages = array();  
    $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
    $options_pages[''] = 'Select a page:';
    foreach ($options_pages_obj as $page) {
        $options_pages[$page->ID] = $page->post_title;
    }

    $options = array();

    $options[] = array( "name" => "Body Styles",
        "type" => "heading");

    $options[] = array( "name" => "Fonts: Color and Sizes",
        "desc" => "Use this to change the fonts, and font sizes, for the entire site. These settings will be overwritten by other font & sizing settings for specific areas.",
        "type" => "info");

    $options[] = array( "name" => "Typography",
        "desc" => "Example typography.",
        "id" => "body.font",
        "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
        "type" => "typography");            
;

    $options[] = array( "name" => "Colors",
        "desc" => "",
        "type" => "info");    

    $options[] = array( "name" => "Background Color",
        "desc" => "Background color of the document. White by default",
        "id" => "body.background_color",
        "std" => "",
        "style" => "background-color",
        "type" => "color");


    $options[] = array( "name" => "Link Color",
        "id" => "link.color",
        "std" => "",
        "selector" => "a:link",
        "style" => "color",
        "important" => false, 
        "type" => "color");

    $options[] = array( "name" => "Link Visted Color",
        "id" => "visited.color",
        "std" => "",
        "selector" => "a:visited",
        "style" => "color",
        "important" => false, 
        "type" => "color");

    $options[] = array( "name" => "Header Styles",
                "type" => "heading");

    $options[] = array( "name" => "H1 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h1.styles",
            "selector" => "h1",
            "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
            "type" => "typography"); 

    $options[] = array( "name" => "H2 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h2.styles",
            "selector" => "h2",
            "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
            "type" => "typography");             
        
    $options[] = array( "name" => "H3 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h3.styles",
            "selector" => "h3",
            "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
            "type" => "typography");                 

    $options[] = array( "name" => "H4 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h4.styles",
            "selector" => "h4",
            "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
            "type" => "typography");             
    
    $options[] = array( "name" => "H5 Styles",
            "desc" => "This style will apply to all the h1s",
            "id" => "h5.styles",
            "selector" => "h5",
            "std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
            "type" => "typography");             

    return $options;
}
