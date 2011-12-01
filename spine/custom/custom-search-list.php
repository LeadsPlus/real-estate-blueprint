<?php 

add_filter( 'pls_listings_list_ajax_item_html_listings_search', 'listings_search_pls_listings_list_ajax_item_html', 10, 3 );
function listings_search_pls_listings_list_ajax_item_html($listing_item_html, $listing, $context_var  ) {

    // return $listing_item_html;

    /** Start output buffering. The buffered html will be returned to the filter. */
    ob_start();
    ?>


    <section class="list-item">                                                  

        <h3 style="padding: 10px 0px; margin: 0px;"><a href="<?php echo $listing['url'] ?>"><?php echo $listing['address']; ?></a></h3>
        <section class="list-pic">
            <div class="thumbs">
                <?php echo PLS_Image::load($listing['image_url'], array('resize' => array('w' => 150, 'h' => 150), 'as_html' => true)); ?>
            </div>
        </section>                            

        <section class="list-txt">                               
            <ul class="" >
                <?php echo pls_quick_list($listing, false); ?>
            </ul>
        </section> <!-- .list-txt -->

    </section> <!-- .list-item -->

    <?php

    $html = ob_get_clean();

    // current js build throws a fit when newlines are present
    // will need to strip them. 
    // added EMCA tag will solve in the future.
    $html = preg_replace('/[\n\r\t]/', ' ', $html);
    
    return $html;

}