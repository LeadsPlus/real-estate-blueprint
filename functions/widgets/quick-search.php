<?php 

class PLS_Quick_Search_Widget extends WP_Widget {
    
    function __construct() {
    
        /* Set the widget textdomain. */
        $this->textdomain = pls_get_textdomain();
      
      
        $widget_options = array( 'classname' => 'pls-quick-search',
                             'description' => esc_html__( 'Displays search filters for bedrooms, bathrooms, city, state, zip, minimum price, and maximum price', $this->textdomain )
                             );

        /* Create the widget. */
        parent::__construct( "pls-quick-search", esc_attr__( 'Listings Quick Search Widget', $this->textdomain ), $widget_options );

        
    }

    function widget($args, $instance) {
        extract($args);
 
        $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);

        $search_form_filter_string = '';

        $search_form_filter_string .= 'context=' . apply_filters('pls_widget_quick_search_context', 'quick_search_widget');

        $search_form_filter_string .= apply_filters('pls_widget_quick_search_filter_string', '&ajax=1
                                                    &property_type=0
                                                    &listing_types=0
                                                    &zoning_types=0
                                                    &purchase_types=0
                                                    &zips=0');
           echo $before_widget;
            echo "<h3>" . $title . "</h3>";
            echo PLS_Partials::get_listings_search_form($search_form_filter_string);
        echo "</section>";   
        echo $after_widget;    
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