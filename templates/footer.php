<?php
/**
 * Footer Template
 *
 * The footer template is generally used on every page of your site. Nearly all other templates call it 
 * somewhere near the bottom of the file. It is used mostly as a closing wrapper, which is opened with the 
 * header.php file. It also executes some key functions needed by the theme, child themes, and plugins.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
 ?>

<?php pls_do_atomic( 'before_footer' ); ?>

    <footer class="grid_12">

      <?php pls_do_atomic( 'open_footer' ); ?>

      <div class="wrapper">

        <?php get_template_part( 'menu', 'subsidiary' ); ?>

        <?php pls_do_atomic( 'footer' ); ?>

      </div><!-- .wrapper -->

      <?php pls_do_atomic( 'close_footer' ); ?>

    </footer>

    <?php pls_do_atomic( 'after_footer' ); ?>

  </div> <!-- #container -->

<?php pls_do_atomic( 'close_container' ); ?>

<?php if (pls_get_option('pls-google-analytics')): ?>
  <!-- Google Analytics -->
  <script type="text/javascript">
    <?php echo pls_get_option('pls-google-analytics'); ?>
  </script>
<?php endif; ?>

<?php wp_footer(); ?>

<?php pls_do_atomic( 'close_body' ); ?>

</body>

</html>
