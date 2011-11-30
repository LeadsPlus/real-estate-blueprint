<?php

add_filter('the_content', 'single_property_filter', 11);
function single_property_filter($content) {
	global $post;
	
    if($post->post_type == 'property') {

        $content = get_option('placester_listing_layout');

        if(isset($content) && $content != '') {
			return $content;
		}

        $listing_data = json_decode(stripslashes($post->post_content), true);
		
		ob_start();
		?>
			<div class="details-wrapper">
				<div id="slideshow" class="clearfix theme-default left bottomborder">
					<h3>Image Slideshow</h3>
					<?php echo PLS_Slideshow::slideshow(array( 'anim_speed' => 1000, 'pause_time' => 15000, 'control_nav' => true, 'width' => 700, 'height' => 300, 'context' => 'property'), $listing_data); ?>
				</div>
			</div>

			<div>
				<h3>Property Map</h3>
				<?php echo PLS_Maps::map($listing_data, array('lat'=>$listing_data['location']['coords']['latitude'], 'lng'=>$listing_data['location']['coords']['longitude'], 'width' => 700, 'height' => 250, 'zoom' => 16)); ?>
			</div>

			<div class="details-wrapper">
				<h3>Property Description</h3>
				<p> <?php echo $listing_data['description']; ?> </p>
			</div>
			
			<div class="details-list-txt">
				<ul>
					<?php echo pls_quick_list($listing_data, true); ?>
				</ul>
	        </div>
			
				
		<?php
		$html = ob_get_clean();

		return $html;
		
	}
	
}