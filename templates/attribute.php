<?php 
global $query_string;
$args = wp_parse_args($query_string, array('','state' => false, 'city' => false, 'neighborhood' => false, 'zip' => false, 'street' => false));
$taxonomy = PLS_Taxonomy::get($args);
//pls_dump($taxonomy);

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
		<?php echo PLS_Map::dynamic(null, array('width' => 590, 'height' => 250, 'zoom' => 16)); ?>
	</div>
	<div class="all-listings">
		<?php echo pls_get_listings( "limit=5&featured_option_id=custom-featured-listings&context=home&request_params=location[" . $taxonomy['api_field'] . "]=" . $taxonomy['name'] ) ?>
	</div>
	<div class="automate-photos">
		
	</div>
	<div class="attached-photos">
		
	</div>
	
	<div class="tagged-posts">
		
	</div>
	<div class="schools">
		
	</div>
</div>
<div>
	close 
</div>