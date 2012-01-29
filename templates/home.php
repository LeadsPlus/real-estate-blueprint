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


<?php 
	$listings = PL_Config::PL_API_LISTINGS('get');
	PL_Form::generate($listings['args']);
 ?>

<div id="listing" class="grid_8 alpha">
    <?php echo pls_get_listings( "limit=5&is_featured=true&context=home" ) ?>
</div>
