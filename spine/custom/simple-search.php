<?php 

class PLS_Simple_Search {

    static function init() {

        /** Add the form */
        add_action( 'placester-spine_open_sidebar_primary', array( __CLASS__, 'add_simple_search'  ));

        $select_fields = array( 'bedrooms', 'bathrooms', 'available_on', 'cities', 'min_price', 'max_price' );
        $short_fields = array( 'bedrooms', 'bathrooms', 'min_price', 'max_price' );

        foreach ( $select_fields as $field )
            add_filter( "pls_listings_search_form_{$field}_attributes_home", array( __CLASS__, 'select_attributes' ), 10 );

        foreach ( $select_fields as $field )
            if ( in_array( $field, $short_fields ) )
                add_filter( "pls_listings_search_form_{$field}_html_home", array( __CLASS__, 'wrapper_short' ), 10, 3 );
            else
                add_filter( "pls_listings_search_form_{$field}_html_home", array( __CLASS__, 'wrapper_long' ), 10, 3 );

        add_filter(  'pls_listings_search_submit_home', array( __CLASS__, 'submit' ) );
        add_filter(  'pls_listings_search_form_inner_home', array( __CLASS__, 'form_inner' ), 10, 4 );
        add_filter(  'pls_listings_search_form_outer_home', array( __CLASS__, 'form_outer' ), 10 );
    }

    static function add_simple_search() {
    ?>
        <section id="simple-search">
            <h3><?php __( 'Simple Search', pls_get_textdomain() ) ?></h3>
            <?php echo PLS_Partials::get_listings_search_form( "context=home&ajax=0" ); ?>
        </section>
    <?php
    }

    static function select_attributes( $attr_array ) {

        return array( 'class' => 'sparkbox-custom' );
    }

    static function wrapper_short( $html, $options, $title ) {

        return pls_h_div( 
                $html,
                array( 'class' => 'cselect3' )
            );
    }

    static function wrapper_long( $html, $options, $title ) {

        return pls_h_div( 
                $html,
                array( 'class' => 'cselect' )
            );
    }

    static function submit( $html ) {

        return pls_h( 
            'input', 
            array( 
                'type' => 'submit', 
                'value' => __( 'Search', pls_get_textdomain() ),
                'class' => 'button b-blue'
            )
        );
    }

    static function form_inner( $inner_html, $html_array, $options, $titles ) {
        
        ob_start();
        ?>
    <div class="tbl-col-1">
        <label><?php _e( 'Cities', pls_get_textdomain() ) ?></label>
        <?php echo $html_array['cities'] ?>
    </div>

    <div class="tbl-col-2">

        <div class="tbl-col-3">
            <?php echo $titles['min_price'] ?><br>
            <?php echo $html_array['min_price'] ?>
        </div>

        <div class="tbl-col-4">
            <?php echo $titles['max_price'] ?><br>
            <?php echo $html_array['max_price'] ?>
        </div>

    </div>

    <div class="tbl-col-1">
        <label><?php _e( 'Beds', pls_get_textdomain() ) ?></label>
        <?php echo $html_array['bedrooms'] ?>
    </div>

    <div class="tbl-col-2">
        <label><?php _e( 'Baths', pls_get_textdomain() ) ?></label>
        <?php echo $html_array['bathrooms'] ?>
    </div>

    <div class="clr"></div>

    <div class="aR srch1"><?php echo $html_array['submit'] ?></div>
        <?php

        $html = ob_get_clean();

        return $html;
    }

    static function form_outer( $html ) {
        return pls_h_div( 
            $html,
            array( 'id' => 'form-home' )
        );
    }
}