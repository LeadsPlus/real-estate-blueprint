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
						'timer' => 'false',											// true or false to have the timer
						'pauseOnHover' => true,									// if you hover pauses the slider
						'advanceSpeed' => 4000,									// if timer is enabled, time between transitions 
						'startClockOnMouseOut' => true,					// if clock should start on MouseOut
						'startClockOnMouseOutAfter' => 1000,		// how long after MouseOut should the timer start again
						'directionalNav' => true, 							// manual advancing directional navs
						'captions' => true, 										// do you want captions?
						'captionAnimation' => 'fade', 					// fade, slideOpen, none
						'captionAnimationSpeed' => 800, 				// if so how quickly should they animate in
						'afterSlideChange' => 'function(){}',		// empty function
						'width' => 620, 
						'height' => 300, 
						'context' => 'home', 
						'listings' => 'limit=5&is_featured=true&sort_by=price'
					)
			); 
		?>
</div>
<div id="listing" class="grid_8 alpha">
    <?php //echo pls_get_listings( "limit=5&is_featured=true&context=home" ) ?>
    <?php 
		$listings = PL_Config::PL_API_LISTINGS('get');
		PL_Form::generate($listings['args']);
 	?>
</div>
