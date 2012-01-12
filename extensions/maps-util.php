<?php 

class PLS_Map {
	
	// we'll build our response in here so we can
	// build html as the data requires it rather 
	// then the needed order for google maps. 
	static $response;

	static $markers = array();


	static function dynamic($listings = array(), $map_args = array(), $marker_args = array())
	{
		self::make_markers($listings, $marker_args);
		
		return self::assemble_map($map_args);
	}

	private static function make_markers($listings, $args) {
		
		if (is_array($listings) && isset($listings[0])) {
			foreach ($listings as $listing) {
				self::make_marker($listing);
			}
		} else {
			if (!empty($listings)) {
				self::make_marker($listings);
			}
		}
	}

	private static function make_marker($listing = '', $args ='') {
		
		extract(self::process_marker_defaults($listing, $args), EXTR_SKIP);
		
		ob_start();
		?>
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),
			map: pls_google_map,
		});
		<?php
		self::$markers[] = trim(ob_get_clean());
	}

	private static function assemble_map($args) {
		
		extract(self::process_defaults($args), EXTR_SKIP);
		
		ob_start();
		?>
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript">
		  
		  $(function() {

		  	var latlng = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>);
		    var myOptions = {
		      zoom: <?php echo $zoom; ?>,
		      center: latlng,
		      mapTypeId: google.maps.MapTypeId.ROADMAP
		    };
		    var <?php echo $map_js_var ?> = new google.maps.Map(document.getElementById("<?php echo $id ?>"),
		        myOptions);
		
			<?php foreach (self::$markers as $marker): ?>
				<?php echo $marker; ?>
			<?php endforeach ?>	

		});	  
		</script>
		
		<div class="<?php echo $class ?>" id="<?php echo $id ?>" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"></div>
		<?php
		
		return ob_get_clean();

	}

	private static function process_defaults ($args) {
		
		/** Define the default argument array. */
		$defaults = array(
        	'lat' => '42.37',
        	'lng' => '-71.03',
        	'zoom' => '14',
        	'width' => 300,
        	'height' => 300,
        	'id' => 'map_canvas',
        	'class' => 'custom_google_map',
        	'map_js_var' => 'pls_google_map'
        );

		/** Merge the arguments with the defaults. */
        $args = wp_parse_args( $args, $defaults );

        return $args;

	}

	private static function process_marker_defaults ($listing, $args) {

		// pls_dump($listing);
		if (isset($listing) && is_array($listing) && isset($listing['location'])) {
			$coords = $listing['location']['coords'];
			$args['lat'] = $coords['latitude'];
			$args['lng'] = $coords['longitude'];
		}

		/** Define the default argument array. */
		$defaults = array(
        	'lat' => '42.37',
        	'lng' => '71.03',
        );

		/** Merge the arguments with the defaults. */
        $args = wp_parse_args( $args, $defaults );

        return $args;		

	}
}