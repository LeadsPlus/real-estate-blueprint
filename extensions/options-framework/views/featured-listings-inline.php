<div class="featured-listings-wrapper">
	<div class="head">
		<button class="featured-listings button" id="<?php echo $params['value']['id'] ?>">Pick featured listings</button>
	</div>
	
	<div class="featured-listings" id="<?php echo $params['option_name'] ?>" ref="<?php echo $params['value']['id'] ?>">
		<?php if ( is_array($params['val']) ): ?>
			<ul>
				<?php foreach ($params['val'] as $id => $address): ?>
				<li>
					<div id="pls-featured-text" ref="<?php echo $id ?>"><?php echo $address ?></div>
					<input type="hidden" name="<?php echo $params['option_name'] . '[' . $params['value']['id'] . '][' . $id . ']' ?>=" value="<?php echo $address ?>">
				</li>
				<?php endforeach ?>
			</ul>	
		<?php else: ?>
			<p>You haven't set any featured listings yet. Currently, a random selection of listings are being displayed until you pick some. If you previously picked listings, and now they are missing, it's because you (or your MLS), has marked them inactive, sold, rented, or they've been deleted.</p>
		<?php endif ?>
	</div>	
</div>
			