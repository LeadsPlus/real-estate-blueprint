<?php
/**
 * Template Name: Listings Search
 *
 * This is the template for "Listings" search results page.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
?>
<style type="text/css">
	#custom {
		display:none;
	}
</style>
<section class="complex-search grid_8 alpha">
	<?php PLS_Partials::get_listings_search_form('asdf'); ?>
	<?php //PL_Form::generate_form(PL_Config::PL_API_LISTINGS('get','args'), array('context' => 'listing_search', 'ajax' => 1)); ?>
</section>

<div class="grid_8 alpha" id="content" role="main">
    <?php echo PLS_Partials::get_listings_list_ajax('context=listings_search'); ?>
</div>

