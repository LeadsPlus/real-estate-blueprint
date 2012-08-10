<?php 

class PLS_Javascript_Widget extends WP_Widget {
    
    function __construct() {
    
        /* Set the widget textdomain. */
        $this->textdomain = pls_get_textdomain();
            
        $widget_options = array( 'classname' => 'pls-javascript','description' => 'Allows you to add a javascript link which will be loaded into the widget');

        /* Create the widget. */
        parent::__construct( "pls-javascript", 'Placester: js/iframe Widget', $widget_options );        
    }

    function widget($args, $instance) {
        // pls_dump($instance);
        list($args, $instance) = self::process_defaults($args, $instance);
        // pls_dump($args);
        extract( $args, EXTR_SKIP );

        $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
        $javascript_url = apply_filters('widget_javascript_url', $instance['javascript_url'] );

        $search_form_filter_string = '';

        $search_form_filter_string .= 'context=' . apply_filters('pls_widget_javascript_context', 'javascript_widget');
   
        echo $before_widget;
        if (!empty($title)) {
            echo "<h3>" . $title . "</h3>";
        }
        echo $javascript_url;
        echo "<section class='clear'></section>";
        echo "</section>";
        echo $after_widget;
    }

    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = strip_tags(stripslashes($new_instance['title']));
        $instance['javascript_url'] = preg_replace('/<\?.*?(\?>|$)/', '',strip_tags($new_instance['javascript_url'], "<a><p><script><div><span><section><scr'+'ipt>"));

        return $instance;
    }

    function form($instance){
        // pls_dump($instance);
        //Defaults
        $instance = wp_parse_args( (array) $instance, array('title'=>'', 'javascript_url' => '') );

        $title = htmlspecialchars($instance['title']);
        $javascript_url = $instance['javascript_url'];

        // Output the options
        echo '<p><label for="' . $this->get_field_name('title') . '"> Title: </label><input class="widefat" type="text" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . $title . '" /></p>';
        echo '<p><label for="' . $this->get_field_name('javascript_url') . '"> Javascript Url: </label><textarea class="widefat" style="height: 150px;" id="' . $this->get_field_id('javascript_url') . '" name="' . $this->get_field_name('javascript_url') . '" >'.$javascript_url.'</textarea></p>';
    }

    function process_defaults ($args, $instance) {

        /** Define the default argument array. */
        $arg_defaults = array(
            'title' => '',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
            'before_widget' => '<section id="pls-javascript-3" class="widget pls-javascript widget-pls-javascript">',
            'after_widget' => '</section>',
            'widget_id' => '',
            'javascript_url' => ''
        );

        /** Merge the arguments with the defaults. */
        $args = wp_parse_args( $args, $arg_defaults );


        /** Define the default argument array. */
        $instance_defaults = array(
            'widget_id' => ''
        );

        /** Merge the arguments with the defaults. */
        $instance = wp_parse_args( $instance, $instance_defaults );


        return array($args, $instance);
    }

} //end of class