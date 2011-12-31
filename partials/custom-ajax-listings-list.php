<?php 

class PLS_Partials_Custom_Ajax_Listing_List {
	
	function init ($listing_item_html, $listing, $context_var) {
			
			// return $listing_item_html;

		/** Start output buffering. The buffered html will be returned to the filter. */
		ob_start();
		// pls_dump($listing);
		?>

		  <article class="listing-item grid_8 alpha" id="post-<?php the_ID(); ?>">
		    <header class="grid_8 alpha">
		        <h3><a href="<?php echo $listing['url']; ?>" rel="bookmark" title="<?php echo $listing['address'] . ' ' . $listing['city'] . ', ' . $listing['state'] ?>"><?php echo $listing['address'] . ' ' . $listing['city'] . ', ' . $listing['state'] ?></a></h2>
		        <ul>
		            <li>Beds: <?php echo $listing['bedrooms']; ?>, </li>
		            <li>Baths: <?php echo $listing['bathrooms']; ?>, </li>
		            <li>Half Baths: <?php echo $listing['half_baths']; ?>, </li>
		            <li>Price: <?php echo $listing['price']; ?>, </li>
		            <li>Available On: <?php echo $listing['available_on']; ?>, </li>
		        </ul>
		    </header>
		    <div class="entry-summary grid_8 alpha">
		        <p>
		            <?php if (isset($listing['image_url'])): ?>
		                <div id="listing-thumbnail" class="grid_3 alpha">
		                    <div class="outline">
		                        <img src="<?php echo $listing['image_url'] ?>" width=200 alt="">
		                    </div>
		                </div>
		                <div id="listing-description" class="grid_5 omega">
		                    <?php echo substr($listing['description'], 0, 300); ?>    
		                </div>
		            <?php else: ?>
		                <div id="listing-description" class="grid_8 omega">
		                    <?php echo substr($listing['description'], 0, 300); ?>    
		                </div>
		            <?php endif ?>
		        </p>                
		    </div><!-- .entry-summary -->
		    <div class="entry-meta">
		        <a class="more-link" href="<?php echo $listing['url']; ?>">View Details</a>
		    </div><!-- .entry-meta -->
		    <footer class="grid_8 alpha">
		        
		        <ul>
		            <li>This listing has: </li>
		        <?php foreach ($listing as $key => $value): ?>
		            <li><?php echo $key; ?>,</li>
		        <?php endforeach ?>    
		        </ul>
		    </footer>
		</article>



		<?php

		$html = ob_get_clean();

		// current js build throws a fit when newlines are present
		// will need to strip them. 
		// added EMCA tag will solve in the future.
		$html = preg_replace('/[\n\r\t]/', ' ', $html);

		return $html;

	}

}