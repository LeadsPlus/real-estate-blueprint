<?php
/**
 * Template Name: Blog Posts
 *
 * This is the template for the blog page
 *
 * @package PlacesterSpine
 * @subpackage Template
 */
?>
<?php get_template_part( 'loop', 'meta' ); // Loads the loop-meta.php template. ?>

<?php query_posts( 'post_type=post' ); // Get the blog posts ?>

<?php get_template_part( 'loop', 'entries' ) ?>
