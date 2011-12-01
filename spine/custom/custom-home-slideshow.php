<?php 


/**
 * Filters the listings list on the home page to look like a slideshow
 * 
 */


add_filter( 'pls_slideshow_html_home', 'home_slideshow_custom_html', 10, 5 );
function home_slideshow_custom_html ($html, $data, $context, $context_var, $args ) {
	
    $html = array(
        'slides' => '',
        'captions' => '',
    );

    /** Create the slideshow */
    foreach( $data['images'] as $index => $slide_src ) {

		// make it look good!
		// set proper width / height for images
		$slide_src = PLS_Image::load($slide_src, array('resize' => array('w' => $args['width'], 'h' => $args['height'])));

        $extra_attr = array();

        /** Save the caption and the title attribute for the img. */
        if ( isset( $data['captions'][$index] ) ) {
            $html['captions'] .= $data['captions'][$index];
            $extra_attr['title'] = "#caption-{$index}";
        }

        /** Create the img element. */
        $slide = pls_h_img( $slide_src, false, $extra_attr );

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

	return $html;
}

