<?php
/**
 * Primary Menu Template
 *
 * Displays the Primary Menu if it has active menu items.
 *
 * @package PlacesterSpine
 * @subpackage Template
 */

if ( has_nav_menu( 'primary' ) ) : ?>

    <?php pls_do_atomic( 'before_menu_primary' ); ?>

	<nav class="main-nav">

        <?php pls_do_atomic( 'open_menu_primary' ); ?>

        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => '', 'menu_class' => '', 'link_after' => '<span></span>' ) ); ?>

        <?php pls_do_atomic( 'close_menu_primary' ); ?>

	</nav><!-- #menu-primary .menu-container -->

    <?php pls_do_atomic( 'after_menu_primary' ); ?>

<?php endif; ?>

