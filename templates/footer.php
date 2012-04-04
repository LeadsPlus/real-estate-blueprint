<?php
/**
 * Footer Template
 *
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
 ?>

<?php pls_do_atomic( 'before_footer' ); ?>

<footer>

    <?php pls_do_atomic( 'open_footer' ); ?>

    <div class="wrapper">

        <?php PLS_Route::get_template_part( 'menu', 'subsidiary' ); ?>

        <?php pls_do_atomic( 'footer' ); ?>

    </div><!-- .wrapper -->

    <?php pls_do_atomic( 'close_footer' ); ?>

    <?php if ( is_home() ) { ?>
      <?php PLS_Listing_Helper::get_compliance(array('context' => 'listings', 'agent_name' => false, 'office_name' => false)); ?>
    <?php } ?>

    <?php if ( is_page( 'Listings' ) || is_page( 'listings' ) || is_page( 'Open Houses' ) ) { ?>
      <?php PLS_Listing_Helper::get_compliance(array('context' => 'search', 'agent_name' => false, 'office_name' => false)); ?>
    <?php } ?>

</footer>

<?php pls_do_atomic( 'after_footer' ); ?>

<?php wp_footer(); ?>

</div> <!-- #container -->

<?php pls_do_atomic( 'close_body' ); ?>

<?php if (pls_get_option('pls-google-analytics')): ?>
	<script type="text/javascript">
		<?php echo pls_get_option('pls-google-analytics'); ?>
	</script>
<?php endif; ?>
</body>

</html>
