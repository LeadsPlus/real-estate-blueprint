<?php 

class PLS_Partials_Property_Details {
	
	function init ($content) {

		global $post;

	    if($post->post_type == 'property') {

            $html = '';
            $listing_data = unserialize($post->post_content);

            if (!$listing_data) {
	          	// Update listing data from the API
				$args = array('listing_ids' => array($post->post_name), 'address_mode' => 'exact');
				$response = PL_Listing::get($args);
				if ( !empty($response['listings']) ) {
					$listing_data = $response['listings'][0];
				}
          	}

	        $listing_data['location']['full_address'] = $listing_data['location']['address'] . ' ' . $listing_data['location']['locality'] . ' ' . $listing_data['location']['region'];

	        ob_start();
	        ?>

					<h2><?php echo $listing_data['location']['address'] . ' ' . $listing_data['location']['region'] . ' ' . $listing_data['location']['locality']; ?></h2>
					<div>
						<ul>
							<?php foreach (PLS_Taxonomy::get_links($listing_data['location']) as $label => $link): ?>
								<li><a href="<?php echo $link ?>"><?php echo $label ?></a></li>
							<?php endforeach ?>
						</ul>
					</div>
					<span class="listing_type"> <?php echo @$listing_data['zoning_types'][0] . ' ' . @$listing_data['purchase_types'][0] ?></span>

					<div class="clearfix"></div>

						<?php if ($listing_data['images']): ?>
							<div class="theme-default property-details-slideshow">
								<?php //echo PLS_Image::load($listing_data['images'][0]['url'], array('resize' => array('w' => 590, 'h' => 300), 'fancybox' => false, 'as_html' => true)) ?>
								<?php echo PLS_Slideshow::slideshow( array( 'anim_speed' => 1000, 'pause_time' => 15000, 'control_nav' => true, 'width' => 620, 'height' => 300, 'context' => 'home', 'data' => PLS_Slideshow::prepare_single_listing($listing_data) ) ); ?>
							</div>

							<div class="details-wrapper grid_8 alpha">

								<div id="slideshow" class="clearfix theme-default left bottomborder">
									<h3>Image Gallery</h3>
									<div class="grid_8 alpha">
										<ul class='property-image-gallery grid_8 alpha'>
											<?php foreach ($listing_data['images'] as $images): ?>
												<li><?php echo PLS_Image::load($images['url'], array('resize' => array('w' => 100, 'h' => 75), 'fancybox' => true)) ?></li>
											<?php endforeach ?>
										</ul>
									</div>

								</div>

							</div>
							<?php endif ?>
                

                <div class="details-wrapper grid_4 alpha">
                    <h3>Property Description</h3>
                    <?php if (!empty($listing_data['cur_data']['desc'])): ?>
                        <p> <?php echo $listing_data['cur_data']['desc']; ?> </p>
                    <?php else: ?>
                        <p> No description available </p>
                    <?php endif ?>
                </div>

                <div class="details-wrapper grid_4 omega">
                    <h3>Basic Details</h3>
                    <ul>
                        <li><span>Beds </span><?php echo $listing_data['cur_data']['beds'] ?></li>
                        <li><span>Baths </span><?php echo $listing_data['cur_data']['baths'] ?></li>
                        <li><span>Price </span><?php echo $listing_data['cur_data']['price'] ?></li>
                        <li><span>Half Baths </span><?php echo $listing_data['cur_data']['half_baths'] ?></li>
                        <li><span>Available </span><?php echo @$listing_data['cur_data']['avail_on'] ?></li>
                        <li><span>Square Feet </span><?php echo $listing_data['cur_data']['sqft'] ?></li>
                        <?php if (isset($listing_data['rets']) && isset($listing_data['rets']['mls_id'])): ?>
                        	<li><span>MLS Number: </span><?php echo $listing_data['rets']['mls_id'] ?></li>	
                        <?php endif ?>
                    </ul>
                </div>

                <?php $amenities = PLS_Format::amenities_but(&$listing_data, array('half_baths', 'beds', 'baths', 'url', 'sqft', 'avail_on', 'price')); ?>
               
                <?php if (isset($amenities['list'])): ?>
	                <div class="amenities grid_8 alpha">
	                    <h3>Listing Amenities</h3>
	                	<?php PLS_Format::translate_amenities(&$amenities['list']); ?>
	                    <?php foreach ($amenities['list'] as $amenity => $value): ?>
	                        <li><span><?php echo $amenity; ?></span> <?php echo $value ?></li>
	                    <?php endforeach ?>
	                </div>	
                <?php endif ?>
                
                <?php if (isset($amenities['ngb'])): ?>
	                <div class="amenities grid_8 alpha">
	                    <h3>Local Amenities</h3>
	                	<?php PLS_Format::translate_amenities(&$amenities['ngb']); ?>
	                    <?php foreach ($amenities['ngb'] as $amenity => $value): ?>
	                        <li><span><?php echo $amenity; ?></span> <?php echo $value ?></li>
	                    <?php endforeach ?>
	                </div>	
                <?php endif ?>
                
				<?php if (isset($amenities['uncur'])): ?>
	                <div class="amenities grid_8 alpha">
	                    <h3>Local Amenities</h3>
	                	<?php PLS_Format::translate_amenities(&$amenities['uncur']); ?>
	                    <?php foreach ($amenities['uncur'] as $amenity => $value): ?>
	                        <li><span><?php echo $amenity; ?></span> <?php echo $value ?></li>
	                    <?php endforeach ?>
	                </div>	
                <?php endif ?>

	            <div class="map-wrapper grid_8 alpha">
	                <h3>Property Map</h3>
                    <div class="map">
                    	<?php echo PLS_Map::lifestyle($listing_data, array('width' => 590, 'height' => 250, 'zoom' => 16, 'life_style_search' => true,'show_lifestyle_controls' => true, 'show_lifestyle_checkboxes' => true, 'lat'=>$listing_data['location']['coords'][0], 'lng'=>$listing_data['location']['coords'][1])); ?>
                    </div>
	            </div>
        		<?php PLS_Listing_Helper::get_compliance(array('context' => 'listings', 'agent_name' => $listing_data['rets']['aname'] , 'office_name' => $listing_data['rets']['oname'], 'office_phone' => PLS_Format::phone($listing_data['contact']['phone']))); ?>

			<?php
	        $html = ob_get_clean();
	        return apply_filters('property_details_filter',$html, $listing_data);
	    } 
	    return $content;
	}
}