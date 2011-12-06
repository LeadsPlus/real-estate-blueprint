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
<div id="slideshow" class="clearfix theme-default left bottomborder grid_8 alpha">
    <?php echo PLS_Slideshow::slideshow( array( 'anim_speed' => 1000, 'pause_time' => 15000, 'control_nav' => true, 'width' => 620, 'height' => 300, 'context' => 'home', 'listings' => 'limit=6&is_new=true&sort_by=price' ) ); ?>
</div>
<div id="listing" class="grid_8 alpha">
    <?php echo pls_get_listings( "limit=5&is_featured=true&context=home" ) ?>
</div>
