<div style="display:none">
	<div id="featured-listing-wrapper">

		<!-- filters wrapper -->
		<div class="filter-wrapper">
			<?php PLS_Featured_Listing_Option::get_filters(); ?>
		</div>

		<!-- datatable wrapper -->
		<div class="datatable-wrapper">

			<!-- Search Results -->
			<div id="search-results" class="results">
				<?php PLS_Featured_Listing_Option::get_datatable(); ?>
			</div>

			<!-- Featured Listings -->
			<div id="featured-lisitngs" class="results">
				<?php PLS_Featured_Listing_Option::get_datatable(); ?>
			</div>

		</div>

	</div>
</div>