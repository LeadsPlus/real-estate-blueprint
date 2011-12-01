<?php 

add_filter('spine_custom_styles', 'custom_body_styles');
function custom_body_styles () {

	$options = optionsframework_options();
	$styles = '';

	if (!empty($options) && is_array($options)) {
		$styles = PLS_Style::create_css($options);	
	}
	
	
	echo $styles;
}


