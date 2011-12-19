<?php 

class PLS_Map {
	
	static function dynamic($listing, $args)
	{

		        /** Define the default argument array. */
        $defaults = array(
        	'lat' => '42.37',
        	'lng' => '71.03',
        	'zoom' => '8',
        	'width' => 300,
        	'height' => 300,
        	'id' => 'map_canvas',
        	'class' => 'custom_google_map'
        );

        /** Merge the arguments with the defaults. */
        $args = wp_parse_args( $args, $defaults );

        /** Extract the arguments after they merged with the defaults. */
        extract( $args, EXTR_SKIP );

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
		    var pls_google_map = new google.maps.Map(document.getElementById("<?php echo $id ?>"),
		        myOptions);
			
			var marker = new google.maps.Marker({
				position: latlng,
				map: pls_google_map,
			});

		});
		  
		</script>
		
		<div class="<?php echo $class ?>" id="<?php echo $id ?>" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"></div>
		<?php
		
		return ob_get_clean();
	}
}