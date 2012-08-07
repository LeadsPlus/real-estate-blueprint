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

			        <?php get_template_part( 'menu', 'subsidiary' ); ?>

			        <?php pls_do_atomic( 'footer' ); ?>

			    </div><!-- .wrapper -->

			    <?php pls_do_atomic( 'close_footer' ); ?>

			</footer>

			<?php pls_do_atomic( 'after_footer' ); ?>

		</div> <!-- #container -->

		<?php pls_do_atomic( 'close_body' ); ?>

		<?php wp_footer(); ?>
	</body>

</html>
