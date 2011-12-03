<?php
/**
 * Template Name: Listings Search
 *
 * This is the template for "Listings" search results page.
 *
 * @package PlacesterSpine
 * @subpackage Template
 */
?>

<section class="complex-search">
    <h3><?php __( 'Search', pls_get_textdomain() ) ?></h3>
    <?php echo PLS_Partials::get_listings_search_form( 'context=listings&ajax=1&context=listing_search'); ?>
</section>

<div id="content" role="main">
    <?php echo PLS_Partials::get_listings_list_ajax('crop_description=1&context=listings_search'); ?>
</div>

