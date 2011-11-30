<?php


add_filter('pls_listing_widget', 'custom_widget_listings_html', 10, 4);
function custom_widget_listings_html ($listing_html, $listing_data, $request_params, $context_var) {

    ob_start();
	?>
		<article>

			<h4>
				<a href="<?php echo $listing_data->url ?>"><?php echo $listing_data->location->full_address ?></a>
			</h4>
			<div id="thumb">
				<?php echo PLS_Image::load($listing_data->images[0]->url, array('resize' => array('w' => 150, 'h'=> 150), 'fancybox' => true)); ?>
			</div>
			<div id="attributes">
				<ul> 
					<li> <strong>Beds:</strong> <?php echo $listing_data->bedrooms; ?> </li>
					<li> <strong>Baths:</strong> <?php echo $listing_data->bathrooms; ?> </li>
					<li> <strong>Price:</strong> <?php echo $listing_data->price; ?> </li>
				</ul>
			</div>
		</article>

	<?php
	$html = ob_get_clean();

	return $html;

	
}

/*
	object(stdClass)#87 (7) { ["city"]=> string(7) "Ashland" ["address"]=> string(20) "23 Hundred Oaks Lane" ["zip"]=> string(5) "01721" ["country"]=> string(2) "US" ["full_address"]=> string(39) "23 Hundred Oaks Lane, Ashland, MA 01721" ["state"]=> string(2) "MA" ["coords"]=> object(stdClass)#86 (2) { ["latitude"]=> float(42.238145) ["longitude"]=> float(-71.440079) } }
*/