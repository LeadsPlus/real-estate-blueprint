<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
 ?>

<section class="left-content">
<?php PLS_Route::get_template_part( 'loop', 'meta' ); // Loads the loop-meta.php template. ?>    

<?php if ( have_posts() ) : ?>
    
        <?php while ( have_posts() ) : the_post(); ?>

            <?php pls_do_atomic( 'before_entry' ); ?>

            <article <?php post_class() ?> id="post-<?php the_ID(); ?>">

                <?php pls_do_atomic( 'open_entry' ); ?>

                <header>
                    <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( 'Permalink to %1$s', the_title_attribute( 'echo=false' ) ) ?>"><?php the_title(); ?></a></h2>
                </header> 

                <?php pls_do_atomic( 'before_entry_content' ); ?>

                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div>
                
                <?php ; ?>

                <div class="entry-meta">
                    <time datetime="<?php the_time('Y-m-d')?>"><?php the_time('F jS, Y') ?></time> &mdash; 
                    <a href="<?php the_permalink() ?>"><?php the_permalink() ?></a>
                    <?php edit_post_link(' | ' . 'Edit', '', ''); ?>
                </div>

                <?php pls_do_atomic( 'close_entry' ); ?>

            </article><!-- Article end -->

            <?php pls_do_atomic( 'after_entry' ); ?>

        <?php endwhile; ?>

<?php else : ?>
    
	<article id="post-0" <?php post_class() ?>>

		<section class="entry-content">

			<p>
                <?php echo 'You tried going to the '. single_cat_title('', false).' category, and it doesn\'t have any posts. All is not lost! You try searching for what you\'re looking for.'; ?>
            </p>

            <?php get_search_form(); // Loads the searchform.php template. ?>

		</section><!-- .entry-content -->

	</article>

<?php endif; ?>
    
</section>

