<?php 

class PLS_Partials_Property_Details {
	
	function init ($content) {
		
		global $post;
	    
	    if($post->post_type == 'property') {

	        $content = get_option('placester_listing_layout');

	        if(isset($content) && $content != '') {
	            return $content;
	        }

	        $listing_data = json_decode(stripslashes($post->post_content), true);
	        
	        ob_start();
	        ?>
	            <h2> <?php echo $listing_data['location']['full_address']; ?> </h2>
	            <div class="details-wrapper grid_8 alpha">
	                <div id="slideshow" class="clearfix theme-default left bottomborder">
	                    <h3>Image Gallery</h3>
	                    <div class="grid_8 alpha">
	                        <ul class='property-image-gallery grid_8 alpha'>
	                            <?php foreach ($listing_data['images'] as $images): ?>
	                            <li><?php echo PLS_Image::load($images['url'], array('resize' => array('w' => 200, 'h' => 200), 'fancybox' => true, 'as_html' => true)) ?></li>
	                            <?php endforeach ?>
	                        </ul>
	                    </div>
	                </div>
	            </div>

	            <div class="grid_8 alpha">
	                <h3>Property Map</h3>
	                <?php echo PLS_Map::dynamic($listing_data, array('lat'=>$listing_data['location']['coords']['latitude'], 'lng'=>$listing_data['location']['coords']['longitude'], 'width' => 620, 'height' => 250, 'zoom' => 16)); ?>
	            </div>

	            <div class="details-wrapper grid_8 alpha">
	                <h3>Property Description</h3>
	                <?php if (!empty($listing_data['description'])): ?>
	                    <p> <?php echo $listing_data['description']; ?> </p>
	                <?php else: ?>
	                    <p> No description available </p>
	                <?php endif ?>
	            </div>
	            
	            <div class="details-list-txt grid_8 alpha">
	                <h3>Property Attributes</h3>
	                <ul>
	                    <?php echo pls_quick_list($listing_data, true); ?>
	                </ul>
	            </div>    
	        <?php
	        $html = ob_get_clean();

	        return apply_filters('property_details_filter', $listing_data, $html);
	        
	    } 

	    return $content;
    

	}

}