<?php 

// add lead login link to header 
add_filter( 'spine_close_header', 'spine_close_header' );
function spine_close_header () {

	if (PLS_Plugin_API::get_plugin_option('placester_activate_client_accounts')) {
		?>
		<div class="utility-nav">
			<?php 	echo do_shortcode( '[lead_user_navigation]' ); ?>
		</div>
		<?php
	}

}

add_filter( 'spine_site_title', 'spine_title', 10, 1);
function spine_title ($title) {

	$tag = 'h1';

	if ( !$title = get_bloginfo( 'name' ) ) {
		$title = '';
	}
	
	return $title = '<' . $tag . ' id="site-title"><a href="' . home_url() . '" title="' . esc_attr( $title ) . '" rel="home"><span>' . $title . '</span></a></' . $tag . '>';

}