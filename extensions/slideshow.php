<?php 
/**
 *  Wrapper function for the slideshow function.
 */
function pls_slideshow( $args = '', $data = false ) {

    return PLS_Slideshow::slideshow( $args, $data );
}

/**
 * Slideshow - A slideshow that integrates with the Placester Plugin
 * 
 */

PLS_Slideshow::init();

class PLS_Slideshow {

    /**
     * Initializes the slideshow.
     */
    static function init() {

        self::enqueue();
    }

    static private function enqueue() {

        $slideshow_support = get_theme_support( 'pls-slideshow' );

        wp_register_script( 'pls-slideshow-nivo', trailingslashit( PLS_EXT_URL ) . 'slideshow/nivo-slider/jquery.nivo.slider.js' , array( 'jquery' ), NULL, true );
        wp_register_style( 'pls-slideshow-nivo', trailingslashit( PLS_EXT_URL ) . 'slideshow/nivo-slider/nivo-slider.css' );
        wp_register_style( 'pls-slideshow-nivo-default', trailingslashit( PLS_EXT_URL ) . 'slideshow/nivo-slider/themes/default/default.css' );

        if ( is_array( $slideshow_support ) ) {
            if ( in_array( 'script', $slideshow_support[0] ) ) 
                wp_enqueue_script( 'pls-slideshow-nivo' );

            if ( in_array( 'style', $slideshow_support[0] ) ) {
                wp_enqueue_style( 'pls-slideshow-nivo' );
                wp_enqueue_style( 'pls-slideshow-nivo-default' );
            }
            return;
        }

        wp_enqueue_script( 'pls-slideshow-nivo' );
        wp_enqueue_style( 'pls-slideshow-nivo' );
        wp_enqueue_style( 'pls-slideshow-nivo-default' );
        
    }

    /**
     * Slideshow
     * 
     * @param string $args 
     * @param mixed $data 
     * @static
     * @access public
     * @return void
     */
    static function slideshow( $args = '' ) {

        /** Define the default argument array. */
        $defaults = array(
            'anim_speed' => 1000,
            'pause_time' => 3000,
            'keyboard_nav' => true,
            'direction_nav' => true,
            'control_nav' => true,
            'width' => 590,
            'height' => 250,
            'context' => '',
            'context_var' => false,
            'listings' => 'limit=5&width=590&height=250&sort_by=price',
            'data' => false,
        );

        /** Merge the arguments with the defaults. */
        $args = wp_parse_args( $args, $defaults );

        /** Extract the arguments after they merged with the defaults. */
        extract( $args, EXTR_SKIP );

        if ( ! $data || ! is_array( $data ) ) {
            /** Process the listings args. */
            $listings_args = wp_parse_args( $listings );

            /** Process arguments that need to be sent to the API. */
            $request_params = PLS_Plugin_API::get_valid_property_list_fields( $listings_args );

            /** Request the list of properties. */
            $listings_raw = PLS_Plugin_API::get_property_list( $request_params );

            /** Display a placeholder if the plugin is not active or there is no API key. */
            if ( pls_has_plugin_error() && current_user_can( 'administrator' ) )
                return pls_get_no_plugin_placeholder( pls_get_merged_strings( array( $context, __FUNCTION__ ), ' -> ', 'post', false ) );

            /** Return nothing when no plugin and user is not admin. */
            if ( pls_has_plugin_error() )
                return NULL;

            /** Data assumed to take this form. */
            $data = array(
                'images' => array(),
                'links' => array(),
                'captions' => array(),
            );

            foreach ( $listings_raw->properties as $index => $listing ) {

                $listing_url = PLS_Plugin_API::get_property_url( $listing->id );
                /** Overwrite the placester url with the local url. */
                $data['links'][] = $listing_url;
                $data['images'][] = ! empty( $listing->images ) ?  $listing->images[0]->url : PLS_IMG_URL . "/null/listing-100x100.png";

                /** Get the listing caption. */
                ob_start();
				?>
	             <div id="caption-<?php echo $index ?>" class="nivo-html-caption">
	                <p><a href="<?php echo $listing_url ?>"><?php echo $listing->location->full_address ?></a></p>
	                <p><?php printf( __( ' <span class="price">%1$s beds</span>, <span class="baths">%2$s baths</span>', pls_get_textdomain() ), $listing->bedrooms, $listing->bathrooms ); ?></p>
	                <a class="button details" href="<?php echo $listing_url ?>"><span><?php _e( 'See Details', pls_get_textdomain() ) ?></span></a>
	            </div>
				<?php 
                $data['captions'][] = trim( ob_get_clean() );
            }
        }

        /** Filter the data array. */
        $data = apply_filters( pls_get_merged_strings( array( 'pls_slideshow_data', $context ), '_', 'pre', false ), $data, $context, $context_var );

        $html = array(
            'slides' => '',
            'captions' => '',
        );

        /** Create the slideshow */
        foreach( $data['images'] as $index => $slide_src ) {
            $extra_attr = array();

            /** Save the caption and the title attribute for the img. */
            if ( isset( $data['captions'][$index] ) ) {
                $html['captions'] .= $data['captions'][$index];
                $extra_attr['title'] = "#caption-{$index}";
            }

            /** Create the img element. */
            $slide = pls_h_img( PLS_Image::load($slide_src, array('resize' => array('w' => $width, 'h' => $height) ) ), false, $extra_attr );

            /** Wrap it in an achor if the anchor exists. */
            if ( isset( $data['links'][$index] ) )
                $slide = pls_h_a( $data['links'][$index], $slide );

            $html['slides'] .= $slide;
        }

        /** Combine the html. */
        $html = pls_h_div(
            $html['slides'],
            array( 'id' => 'slider', 'class' => 'nivoSlider' )
        ) . $html['captions'];

        /** Filter the html array. */
        $html = apply_filters( pls_get_merged_strings( array( 'pls_slideshow_html', $context ), '_', 'pre', false ), $html, $data, $context, $context_var, $args );


        /** The javascript needed for nivo. */
        ob_start();
		?>
		<style type="text/css">
		#slider {
		    width:<?php echo $width; ?>px !important;
		    height:<?php echo $height; ?>px !important;
		}
		</style>
		<?php 
		        /** Geth the css. */
		        $css = ob_get_clean();

		        /** The javascript needed for nivo. */
		        ob_start();
		?>
		<script type="text/javascript">
		$(window).load(function() {
		    $('#slider').nivoSlider({
		        effect: 'fade', 
		        slices: 2, 
		        animSpeed: <?php echo $anim_speed ?>,
		        pauseTime: <?php echo $pause_time ?>, 
		        startSlide: 0, 
		        directionNav: <?php echo $direction_nav ?>,
		        directionNavHide: true, 
		        controlNav: <?php echo $control_nav ?>, 
		        keyboardNav: <?php echo $keyboard_nav ?>, 
		        pauseOnHover: true, 
		        prevText: '<?php _e( 'Prev', pls_get_textdomain() ) ?>', 
		        nextText: '<?php _e( 'Next', pls_get_textdomain() ) ?>',
		    });
		});
		</script>
		<?php 
        /** Geth the js. */
        $js = ob_get_clean();
		$js = apply_filters( pls_get_merged_strings( array( 'pls_slideshow_js', $context ), '_', 'pre', false ), $js, $html, $data, $context, $context_var );

        return apply_filters( pls_get_merged_strings( array( 'pls_slideshow', $context ), '_', 'pre', false ), $css . $html . $js, $html, $js, $data, $context, $context_var, $args );
    }
}
