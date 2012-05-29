<?php
/**
 * Loop Error Template
 *
 * Displays an error message when no posts are found.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
?>
	<article id="post-0" <?php post_class() ?>>

		<section class="entry-content">

			<p>
                <?php printf( 'You tried going to %1$s, and it doesn\'t exist. All is not lost! You can search for what you\'re looking for.', '<code>' . home_url( esc_url( $_SERVER['REQUEST_URI'] ) ) . '</code>' ); ?>
            </p>

            <?php get_search_form(); // Loads the searchform.php template. ?>

		</section><!-- .entry-content -->

	</article>

