<?php
/**
 * Home Template
 *
 * This is the home template.
 *
 * @package PlacesterSpine
 * @subpackage Template
 */
?>
<div id="slideshow" class="clearfix theme-default left bottomborder">
	<h2>New Listing Slideshow</h2>
	<p>This will add any listing marked as "new" in the listings tab of the Placester Plugin to the slideshow below</p>
    <?php echo PLS_Slideshow::slideshow( array( 'anim_speed' => 1000, 'pause_time' => 15000, 'control_nav' => true, 'width' => 700, 'height' => 300, 'context' => 'home', 'listings' => 'limit=6&is_new=true&sort_by=price' ) ); ?>
</div>

<section id="listing" class="left">
	<h2>Featured Listing List</h2>
	<p> This will list out the details of up to 5 listings marked as "featured" in the listings tab of the Placester Plugin</p>
    <?php echo pls_get_listings( "limit=5&is_featured=true&context=home&width=144&height=93" ) ?>
</section>
