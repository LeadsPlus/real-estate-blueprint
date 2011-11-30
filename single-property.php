<?php
/**
 * Single template
 *
 * This template is used when a single post or page is viewed.
 *
 * @package PlacesterSpine
 * @subpackage Template
 */

?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <?php pls_do_atomic( 'before_entry' ); ?>
    
    <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
        <?php pls_do_atomic( 'open_entry' ); ?>

        <h3><?php get_template_part( 'loop', 'meta' ) ?></h3>

        <?php pls_do_atomic( 'before_entry_content' ); ?>

        <?php the_content(); ?>

        <div class="entry-meta">
        </div>

        <?php pls_do_atomic( 'after_entry_content' ); ?>

        <footer></footer>

        <nav>
            <div><?php previous_post_link( '&laquo; %link' ) ?></div>
            <div><?php next_post_link( '%link &raquo;' ) ?></div>
        </nav>

        <?php pls_do_atomic( 'close_entry' ); ?>
        
    </article>

    <?php pls_do_atomic( 'after_entry' ); ?>
    
<?php endwhile; else: ?>
    
    <?php get_template_part( 'loop', 'error' ); ?>
    
<?php endif; ?>
