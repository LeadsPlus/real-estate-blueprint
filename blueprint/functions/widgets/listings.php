<?php
/**
 * The listings widget allows theme developers to output a list of 
 * listings.
 *
 * @package PlacesterSpine
 * @subpackage Classes
 */

/**
 * Agent Widget Class
 *
 * @since 0.0.1
 */
class PLS_Widget_Listings extends WP_Widget {

	/**
	 * Textdomain for the widget.
	 * @since 0.0.1
	 */
	var $textdomain;

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.0.1
	 */
	function __construct() {

		/* Set the widget textdomain. */
		$this->textdomain = pls_get_textdomain();

		/* Set up the widget options. */
		$widget_options = array(
			'classname' => 'pls-listings',
			'description' => esc_html__( 'A widget that displays a list of listings of a certain type.', $this->textdomain )
		);

		/* Create the widget. */
        parent::__construct( "pls-listings", esc_attr__( 'Placester Listings List', $this->textdomain ), $widget_options );

	}

	/**
	 * Outputs and filters the widget.
     * 
     * The widget connects to the plugin using the framework plugin api class. 
     * If the class returns false, this means that either the plugin is 
     * missing, either the it has no API key set.
     *
	 * @since 0.0.1
	 */
	function widget( $args, $instance ) {

        /** Extract the arguments into separate variables. */
		extract( $args, EXTR_SKIP );

        /* Output the theme's $before_widget wrapper. */
        echo $before_widget;

        /* If a title was input by the user, display it. */
        $widget_title = '';
        if ( !empty( $instance['title'] ) && ! pls_has_plugin_error() )
            $widget_title = $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
		

        /** Get the list of listings using the partial. */
        $listings_args = array(
            'limit' => $instance['number'],
            'width' => $instance['width'],
            'height' => $instance['height'],
            'placeholder_img' => $instance['placeholder'],
            'crop_description' => 1,
            'context' => 'widget',
            'context_var' => $widget_id 
        );
        
        /** Set the listing type. */
        switch ( $instance['type'] ) {
            case 'new': 
                $listings_args['is_new'] = true;
            case 'featured': 
                $listings_args['is_featured'] = true;
        }

        /** If field is empty, remove it so that the function's defaults can be used. */
        if ( empty( $instance['placeholder'] ) )
            unset( $listings_args['placeholder_img'] );

        $widget_body = PLS_Partials::get_listings( $listings_args );

        /** Apply a filter on the whole widget */
        echo apply_filters( 'pls_widget_listings', $widget_title . $widget_body, $widget_title, $before_title, $after_title, $widget_body, $instance, $widget_id, $listings_args );
        /* Close the theme's widget wrapper. */
        echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
     *
	 * @since 0.0.1
	 */
	function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['placeholder'] = $new_instance['placeholder'];
        $instance['number'] = absint( $new_instance['number'] );
        $instance['width'] = absint( $new_instance['width'] );
        $instance['height'] = absint( $new_instance['height'] );
        $instance['type'] = $new_instance['type'];

        $instance['name'] = ( isset( $new_instance['name'] ) ? 1 : 0 );
        $instance['email'] = ( isset( $new_instance['email'] ) ? 1 : 0 );
        $instance['photo'] = ( isset( $new_instance['photo'] ) ? 1 : 0 );
        $instance['phone'] = ( isset( $new_instance['phone'] ) ? 1 : 0 );
        $instance['description'] = ( isset( $new_instance['description'] ) ? 1 : 0 );
        // $instance['extra'] = $new_instance['extra'];

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
     *
	 * @since 0.0.1
	 */
	function form( $instance ) {

		/** Set up the default form values. */
		$defaults = array(
			'title' => esc_attr__( 'Listings', $this->textdomain ),
            'type' => 'all',
			'placeholder' => '',
            'width' => 100,
            'height' => '',
            'number' => 5,

            'name' => true,
            'email' => true,
            'photo' => true,
            'phone' => true,
            'description' => true,
            // 'extra' => '',
		);

        $type_array = array( 
            'all' => esc_attr__( 'All', $this->textdomain ),
            'featured' => esc_attr__( 'Featured', $this->textdomain ),
            'new' => esc_attr__( 'New', $this->textdomain ),
        );

		/** Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );

        /** Print the backend widget form. */
        echo pls_h_div(
            /** Print the Title input */
            pls_h_p( 
                pls_h_label( 
                    __( 'Title', pls_get_textdomain() ) . ':' .
                    pls_h( 
                        'input',
                        array(
                            'type' => 'text',
                            'class' => 'widefat',
                            'id' => $this->get_field_id( 'title' ),
                            'name' => $this->get_field_name( 'title' ),
                            'value' => esc_attr( $instance['title'] )
                        ) 
                    ), 
                    $this->get_field_id( 'title' ) 
                ) 
            ) . 
            /** Print the Type options */
            pls_h_p( 
                pls_h_label( 
                    __( 'Listings Type', pls_get_textdomain() ) . ':' .
                    pls_h( 
                        'select',
                        array(
                            'type' => 'text',
                            'class' => 'widefat',
                            'id' => $this->get_field_id( 'type' ),
                            'name' => $this->get_field_name( 'type' ),
                            // 'value' => esc_attr( $instance['type'] )
                        ), 
                        pls_h_options( $type_array, $instance['type'] )
                    ), 
                    $this->get_field_id( 'type' ) 
                ) 
            ) . 
            /** Print the Photo input */
            pls_h_p( 
                pls_h_label( 
                    __( 'Photo placeholder URL', pls_get_textdomain() ) . ':' .
                    pls_h( 
                        'input',
                        array(
                            'type' => 'text',
                            'class' => 'widefat',
                            'id' => $this->get_field_id( 'placeholder' ),
                            'name' => $this->get_field_name( 'placeholder' ),
                            'value' => esc_attr( $instance['placeholder'] )
                        ) 
                    ), 
                    $this->get_field_id( 'placeholder' ) 
                ) 
            ) . 
            /** Print the Width text input */
            pls_h_p( 
                pls_h_label( 
                    __( 'Photo width', pls_get_textdomain() ) . ': ' .
                    pls_h( 
                        'input',
                        array(
                            'type' => 'text',
                            'size' => 4,
                            'id' => $this->get_field_id( 'width' ),
                            'name' => $this->get_field_name( 'width' ),
                            'value' => esc_attr( $instance['width'] )
                        ) 
                    ),
                    $this->get_field_id( 'width' ) 
                ) 
            ) . 
            /** Print the Height text input */
            pls_h_p( 
                pls_h_label( 
                    __( 'Photo height', pls_get_textdomain() ) . ': ' .
                    pls_h( 
                        'input',
                        array(
                            'type' => 'text',
                            'size' => 4,
                            'id' => $this->get_field_id( 'height' ),
                            'name' => $this->get_field_name( 'height' ),
                            'value' => esc_attr( $instance['height'] )
                        ) 
                    ),
                    $this->get_field_id( 'height' ) 
                ) 
            ) .
            /** Print the Number text input */
            pls_h_p( 
                pls_h_label( 
                    __( 'Number of listings', pls_get_textdomain() ) . ': ' .
                    pls_h( 
                        'input',
                        array(
                            'type' => 'text',
                            'size' => 4,
                            'id' => $this->get_field_id( 'number' ),
                            'name' => $this->get_field_name( 'number' ),
                            'value' => esc_attr( $instance['number'] )
                        ) 
                    ),
                    $this->get_field_id( 'number' ) 
                ) 
            ) 
        ); 
	}
}
