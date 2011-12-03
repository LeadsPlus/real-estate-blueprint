<?php
/**
 * Index Template
 *
 * This is the default template. It is used when a more specific template can't be found to display
 * posts. It is unlikely that this template will ever be used, but there may be rare cases.
 *
 * @package PlacesterSpine
 * @subpackage Template
 */

echo "Index hit!";
PLS_Route::get_template_part( 'page-template-blog' );
