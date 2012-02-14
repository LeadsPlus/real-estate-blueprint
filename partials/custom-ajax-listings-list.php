<?php 

class PLS_Partials_Custom_Ajax_Listing_List {
	
	function init ($listing_item_html, $listing, $context_var) {
			
			// return $listing_item_html;

		/** Start output buffering. The buffered html will be returned to the filter. */
		ob_start();
//		 pls_dump($listing);
//        $listing['description'] =trim($listing['description']);
//        var_dump($listing['description']);
		?>
        <div class="listing-item grid_8 alpha" id="post-<?php the_ID(); ?>">
            <header class="grid_8 alpha">
                <h3><a href="<?php echo $listing['url']; ?>" rel="bookmark" title="<?php echo $listing['address'] ?>"><?php echo $listing['address'] . ', ' . $listing['city'] . ' ' . $listing['state'] ?></a></h2>
            </header>
            <div class="listing-item-content grid_8 alpha">
                <div class="grid_8 alpha">
                    <!-- If we have a picture, show it -->
                    <?php if (isset($listing['image_url'])): ?>
                        <div class="listing-thumbnail">
                            <div class="outline">
                                <?php echo PLS_Image::load($listing['image_url'], array('resize' => array('w' => 250, 'h' => 150), 'fancybox' => true, 'as_html' => true)); ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <div class="basic-details">
                        <p>Beds: <?php echo @$listing['bedrooms']; ?></p>
                        <p>Baths: <?php echo @$listing['bathrooms']; ?></p>
                        <p>Half Baths: <?php echo @$listing['half_baths']; ?></p>
                        <p>Price: <?php echo @$listing['price']; ?></p>
                        <p>Available On: <?php echo @$listing['available_on']; ?></p>
                    </div>

                    <div class="listing-description">
                        <?php echo substr($listing['description'], 0, 300); ?>
                    </div>
                    <div class="actions">
                        <a class="more-link" href="<?php echo $listing['url']; ?>">View Property Details</a>
                    </div>
                </div>
            </div>
        </div>

		<?php

		$html = ob_get_clean();

		// current js build throws a fit when newlines are present
		// will need to strip them. 
		// added EMCA tag will solve in the future.
		$html = preg_replace('/[\n\r\t]/', '', $html);

		return $html;

	}

}