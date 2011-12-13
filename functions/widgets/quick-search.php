<?php 

class PLS_Quick_Search_Widget extends WP_Widget {
    
    function __construct() {
    
        /* Set the widget textdomain. */
        $this->textdomain = pls_get_textdomain();
      
      
        $widget_ops = array( 'classname' => 'pls-quick-search',
                             'description' => esc_html__( 'Displays search filters for bedrooms, bathrooms, city, state, zip, minimum price, and maximum price', $this->textdomain )
                             );


        /* Create the widget. */
        parent::__construct( "pls-quick-search", esc_attr__( 'Listings Quick Search Widget', $this->textdomain ), $widget_options );

        
    }

    function widget($args, $instance) {
        extract($args);
 
        $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
        
        echo PLS_Partials::get_listings_search_form( 'context=listings&ajax=1&context=listing_search');
           
        echo '<input type="submit" value="Search" /></form>' . $after_widget;    
    }

    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));

        return $instance;
    }

    function form($instance){
        //Defaults
        $instance = wp_parse_args( (array) $instance, array('title'=>'') );

        $title = htmlspecialchars($instance['title']);

        // Output the options
        echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title:') . '</label><input class="widefat" type="text" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . $title . '" /></p>';
    }

}