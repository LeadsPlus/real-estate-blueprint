<?php 

class PLS_Map_Polygon extends PLS_Map {

	function polygon($listings = array(), $map_args = array(), $marker_args = array()) {
		$map_args = self::process_defaults($map_args);
		self::make_markers($listings, $marker_args, $map_args);
		extract($map_args, EXTR_SKIP);
		wp_enqueue_script('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false');
		ob_start();
		?>
		  <script src="<?php echo trailingslashit( PLS_JS_URL ) . 'libs/google-maps/text-overlay.js' ?>"></script>
			<?php echo self::get_lifestyle_controls($map_args); ?>
		<?php
		$response = ob_get_clean();
		return $response;
	}

}