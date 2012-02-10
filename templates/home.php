<?php
/**
 * Home Template
 *
 * This is the home template.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
?>

<div id="slideshow" class="clearfix theme-default left bottomborder grid_8 alpha">
    <?php 
			echo PLS_Slideshow::slideshow( 
					array( 
						'animation' => 'fade', 									// fade, horizontal-slide, vertical-slide, horizontal-push
						'animationSpeed' => 800, 								// how fast animtions are
						'timer' => true, 												// true or false to have the timer
						'advanceSpeed' => 4000,									// if timer is enabled, time between transitions 
						'startClockOnMouseOutAfter' => 1000,		// how long after MouseOut should the timer start again
						'directionalNav' => true, 							// manual advancing directional navs
						'captions' => true, 										// do you want captions?
						'captionAnimation' => 'fade', 					// fade, slideOpen, none
						'captionAnimationSpeed' => 800, 				// if so how quickly should they animate in
						// 'bullets' => false,											// true or false to activate the bullet navigation
						// 'bulletThumbs' => false,								// thumbnails for the bullets
						// 'bulletThumbLocation' => '',						// location from this file where thumbs will be

						'width' => 620, 
						'height' => 300, 
						'context' => 'home', 
						'listings' => 'limit=6&is_new=true&sort_by=price' 
					)
			); 
		?>
</div>
<div id="listing" class="grid_8 alpha">
    <?php echo pls_get_listings( "limit=5&is_featured=true&context=home" ) ?>
</div>
