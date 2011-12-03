<?php
/**
 * Header Template
 *
 * The header template is generally used on every page of your site. Nearly all other templates call it 
 * somewhere near the top of the file. It is used mostly as an opening wrapper, which is closed with the 
 * footer.php file. It also executes key functions needed by the theme, child themes, and plugins. 
 *
 * @package PlacesterSpine
 * @subpackage Template
 */
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]> <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]> <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">

    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
    Remove this if you use the .htaccess -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <!-- Mobile viewport optimized: j.mp/bplateviewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php pls_document_title(); ?></title>

    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="all" />



    <?php wp_head(); ?>
    <?php pls_do_atomic( 'custom_styles' ); ?>         
</head>

<body <?php body_class(); ?>>
    
	<?php pls_do_atomic( 'open_body' ); ?>
 
    <div id="container" class="clearfix">

	<?php pls_do_atomic( 'before_header' ); ?>

    <header role="banner">

        <?php pls_do_atomic( 'open_header' ); ?>

        <div class="wrapper">
            <div id="branding">
				<?php pls_site_title(); ?>
                <?php pls_site_description(); ?>
            </div>

            <?php pls_do_atomic( 'header' ); ?>

        </div><!-- .wrapper -->

        <?php pls_do_atomic( 'close_header' ); ?>

    </header>

    <?php pls_do_atomic( 'after_header' ); ?>

    <?php PLS_Route::get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>
