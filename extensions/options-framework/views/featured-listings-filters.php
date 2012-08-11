<form id="options-filters">
	<div class="featured_listings_options">
		<div class="featured-listing-form-city">
			<label>City</label>
			<select name="location[locality]">
				<?php $cities = PLS_Plugin_API::get_location_list('locality');
					foreach ($cities as $key => $v) {
						echo '<option value="' . $key . '">' . $v . '</option>';
					} 
				?>
			</select>
		</div>

		<div class="featured-listing-form-zip">
			<label>Zip Code</label>
			<select name="location[postal]">
				<?php $zip = PLS_Plugin_API::get_location_list('postal');
					foreach ($zip as $key => $v) {
						echo '<option value="' . $key . '">' . $v . '</option>';
					} 
				?>
			</select>
		</div>

		<div class="featured-listing-form-beds">
			<label>Beds</label>
			<select name="metadata[beds]">
				<?php $beds = range(0, 16);
					echo '<option value="false">Any</option>';
					foreach ($beds as $key => $v) {
						echo '<option value="' . $key . '">' . $v . '</option>';
					} 
				?>
			</select>
		</div>

		<div class="featured-listing-form-beds">
			<label>Rent/Sale</label>
			<select name="purchase_types[]">
				<?php
					echo '<option value="false">Any</option>';
					echo '<option value="rental">Rent</option>';
					echo '<option value="sale">Buy</option>';
				?>
			</select>
		</div>

		<div class="featured-listing-form-min-price">
			<label>Min Price</label>
			<select name="metadata[min_price]">
				<?php $min_price = array(
							'false' => 'Any',
							'200' => '200',
							'500' => '500',
							'1000' => '1,000',
							'1200' => '1,200',
							'1400' => '1,400',
							'1600' => '1,600',
							'1800' => '1,800',
							'2000' => '2,000',
							'2200' => '2,200',
							'2400' => '2,400',
							'2600' => '2,600',
							'2800' => '2,800',
							'3000' => '3,000',
							'3500' => '3,500',
							'4000' => '4,000',
							'4500' => '4,500',
							'5000' => '5,000',
							'50000' => '5,0000',
							'10000' => '100,000',
							'20000' => '200,000',
							'30000' => '300,000',
							'50000' => '500,000',
							'70000' => '700,000',
							'1000000' => '1,000,000'
							);
					foreach ($min_price as $key => $v) {
						echo '<option value="' . $key . '">' . $v . '</option>';
					} 
				?>
			</select>
		</div>

		<div class="featured-listing-form-max-price">
			<label>Max Price</label>
			<select name="metadata[max_price]">
				<?php $max_price = array(
							'false' => 'Any',
							'500' => '500',
							'1000' => '1,000',
							'2000' => '2,000',
							'3000' => '3,000',
							'4000' => '4,000',
							'5000' => '5,000',
							'50000' => '5,0000',
							'10000' => '100,000',
							'20000' => '200,000',
							'30000' => '300,000',
							'50000' => '500,000',
							'70000' => '700,000',
							'1000000' => '1,000,000'
							);
					foreach ($max_price as $key => $v) {
						echo '<option value="' . $key . '">' . $v . '</option>';
					} 
				?>
			</select>
		</div>
	</div>
</form>
