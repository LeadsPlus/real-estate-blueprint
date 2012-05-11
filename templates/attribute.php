<?php 
global $query_string;
$args = wp_parse_args($query_string, array('','state' => false, 'city' => false, 'neighborhood' => false, 'zip' => false, 'street' => false, 'image_limit' => 25));
$taxonomy = PLS_Taxonomy::get($args);
?>


<div class="wrapper">
	<div class="title-information">
		<h1>Neighborhood Information for <?php echo $taxonomy['name'] ?></h1>
		<?php if (isset($taxonomy['one-sentance'])): ?>
			<h3><?php echo $taxonomy['one-sentance'] ?></h3>		
		<?php endif ?>

		<h3><?php echo $taxonomy['another'] ?></h3>
	</div>
	<div class="map polygon-too">
		<?php echo PLS_Map::neighborhood($taxonomy['listings_raw'], array('width' => 590, 'height' => 250, 'zoom' => 16), array(), $taxonomy['polygon']) ?>
	</div>
	<div class="all-listings">
		<?php echo $taxonomy['listings'] ?>
	</div>
	<div class="automate-photos">
		<?php foreach ($taxonomy['listing_photos'] as $image): ?>
			<section class="image-photo" style="float: left; margin: 10px">
				<a href="<?php echo $image['listing_url'] ?>" title="<?php echo $image['full_address'] ?>"><img src="<?php echo $image['image_url'] ?> " alt="" width=100 height=100></a>		
			</section>
		<?php endforeach ?>
	</div>
	<div class="attached-photos">
		
	</div>
	
	<div class="tagged-posts">
		
	</div>
	<div class="schools">
		
	</div>
</div>
<div>
</div>