<?php 

class PLS_Featured_Listing_Option {

	function init ( $params = array() ) {
		return '<button class="featured-listings">Pick featured listings</button>';
	}

	function load ( $params = array() ) {
		ob_start();
			extract( $params );
			include( trailingslashit( PLS_OPTRM_DIR ) . 'views/featured-listings.php' );
		echo ob_get_clean();
	}

	function get_filters ( $params = array() ) {
		ob_start();
			extract( $params );
			include( trailingslashit( PLS_OPTRM_DIR ) . 'views/featured-listings-filters.php' );
		echo ob_get_clean();	
	}

}

/*

<div class="featured-listing-search" id="featured-listing-search-<?php echo $value['id']; ?>">
				<div class="fls-address">
					<select name="<?php echo $value['id']; ?>" <?php echo $iterator ? 'ref="' . $iterator. '"' : '' ?> class="fls-address-select" id="fls-select-address"></select><div id="search_message" style="display:none; margin-top: 23px; font-weight: bold;">Searching...</div>
					<input type="submit" name="<?php echo $value['id']; ?>" value="Add Listing" class="fls-add-listing button <?php echo $for_slideshow ? 'for_slideshow' : '' ?>" id="add-listing-<?php echo $value['id']; ?>">	
					<input type="hidden" value="<?php echo esc_attr( $option_name . '[' . $value['id'] . ']' ) ?>" id="option-name">	
				</div>

				<h4 class="heading">Featured Listings</h4>
				<div class="fls-option">
					<div class="controls">
						<ul name="<?php echo $value['id']; ?>" id="fls-added-listings">
							<?php if ( isset($val) && !empty($val) && ( !isset($val['type']) || ( isset($val['type']) && $val['type'] == 'listing') ) ): ?>
								<?php foreach ($val as $key => $text): ?>
									<?php if ($key == 'type' || $key == 'html' || $key == 'image' || $key == 'link'): ?>
										<?php continue; ?>
									<?php endif ?>
									<li style='float:left; list-style-type: none;'>
										<div id='pls-featured-text' style='width: 200px; float: left;'>
											<?php echo $text ?>
										</div>
										<a style='float:left;' href='#' id='pls-option-remove-listing'>Remove</a>
										<?php if ($iterator == false): ?>
											<input type='hidden' name='<?php echo esc_attr( $option_name . '[' . $value['id'] . '][' . $key . ']=' ) ?>' value='<?php echo $text ?>' />	
										<?php else: ?>
											<input type='hidden' name='<?php echo esc_attr( $option_name . '[' . $value['id'] . ']['.$iterator.'][' . $key . ']=' ) ?>' value='<?php echo $text ?>' />	
										<?php endif ?>
										
									</li>
								<?php endforeach ?>
							<?php endif ?>
						</ul>
					</div>
					<div class="clear"></div>
				</div>
			</div>

			*/