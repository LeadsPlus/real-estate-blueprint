<?php 

add_filter( 'pls_listings_home', 'pls_listings_home_custom', 10, 2 );
function pls_listings_home_custom( $html, $listings_raw ) {

    /** Start output buffering. The buffered html will be returned to the filter. */
    ob_start();

    /** Save the last property key. Will be used to not display sepparator. */
    $last_listing_key = end( array_keys( $listings_raw->properties ) );

    ?> 
<section class="list">

    <?php foreach ( $listings_raw->properties as $listing_key => $listing ) : ?>

    <section class="list-item">                                                  

		<h3><a href="<?php echo $listing->url ?>"><?php echo $listing->location->full_address ?></a></h3>
        <section class="list-pic">
            <div class="thumbs">
				<?php echo PLS_Image::load($listing->images[0]->url, array('resize' => array('w' => 150, 'h' => 150), 'fancybox' => true, 'as_html' => true)); ?>
			</div>
        </section>                            

        <section class="list-txt">                               
			<ul>
				<?php echo pls_quick_list($listing, false); ?>
			</ul>
        </section> <!-- .list-txt -->

    </section> <!-- .list-item -->

    <?php /** Don't print the separator if this is the last listing in the list. */ ?>
    <?php if ( $last_listing_key != $listing_key ): ?>
    <div class="separator-1-sma"></div>
    <?php endif; ?>

    <?php endforeach; ?>

</section> <!-- .list -->
    <?php

    $html = ob_get_clean();

    return $html;
}
