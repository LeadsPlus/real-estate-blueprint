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
			<script type="text/javascript">				
				var <?php echo $map_js_var; ?> = {};
				<?php echo $map_js_var; ?>.map;
				<?php echo $map_js_var; ?>.markers = [];
				<?php echo $map_js_var; ?>.infowindows = [];
				<?php echo $map_js_var; ?>.polygons = [];
				<?php echo $map_js_var; ?>.selected_polygon;
				<?php echo $map_js_var; ?>.search_class = '<?php echo $search_class ?>';
				var other_polygons = [];
				var other_text = [];
				var centers = [];
				
				jQuery(function($) { 
					google.maps.event.addDomListener(window, 'load', function() {

						var styles = [{stylers: [{ visibility: "simplified" }]}];
						var polygonMapType = new google.maps.StyledMapType(styles,{name: "polygon"});
						var latlng = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>);
						var myOptions = { zoom: <?php echo $zoom; ?>, center: latlng, mapTypeIds: ['polygon_map']};
						<?php echo $map_js_var ?>.map = new google.maps.Map(document.getElementById("<?php echo $canvas_id ?>"), myOptions);
						<?php echo $map_js_var ?>.map.mapTypes.set('polygon', polygonMapType);
						<?php echo $map_js_var ?>.map.setMapTypeId('polygon');
						<?php foreach (self::$markers as $marker): ?>
							<?php echo $marker; ?>
						<?php endforeach ?>	
						pls_center_map(<?php echo self::$map_js_var ?>);

						<?php if ($polygon): ?>
							var data = <?php echo json_encode(PLS_Plugin_API::get_taxonomies_by_slug($polygon)) ?>;
						<?php else: ?>
							var data = <?php echo json_encode(PLS_Plugin_API::get_taxonomies_by_type($polygon_search)) ?>;
						<?php endif ?>
						for (var j = other_polygons.length - 1; j >= 0; j--) {
							other_polygons[j].setMap(null);
						};

						var all_bounds = new google.maps.LatLngBounds();
						for (item in data) {
							var coords = [];
							for (var k = data[item].vertices.length - 1; k >= 0; k--) {
								coords.push(new google.maps.LatLng(data[item].vertices[k].lat, data[item].vertices[k].lng));
							};
							var polygon = new google.maps.Polygon({
							    paths: coords,
							    strokeColor: data[item].settings.border.color,
							    strokeOpacity: data[item].settings.border.opacity,
							    strokeWeight: data[item].settings.border.weight,
							    fillColor: data[item].settings.fill.color,
							    fillOpacity: data[item].settings.fill.opacity
							  });
							polygon.tax = data[item];
							polygon.setMap(<?php echo $map_js_var ?>.map);
							pls_create_polygon_listeners(polygon, <?php echo $map_js_var ?>, '<?php echo $polygon_click_action ?>');
							customTxt = data[item].name;
				            var bounds = new google.maps.LatLngBounds();
				            for (p = 0; p < polygon.getPath().length; p++) {
				              all_bounds.extend(polygon.getPath().getAt(p));
							  bounds.extend(polygon.getPath().getAt(p));
							}
							var center = bounds.getCenter();
							centers.push(center);
							<?php echo $map_js_var ?>.map.fitBounds(all_bounds);
				            other_text = new TxtOverlay(center,customTxt,"polygon_text_area",<?php echo $map_js_var ?>.map );
							other_polygons.push(polygon);
							other_polygons.push(other_text);
						};

						if (centers.length > 0) {
							var polygonbounds = new google.maps.LatLngBounds();
				            for (p = 0; p < centers.length; p++) {
							  polygonbounds.extend(centers[p]);
							}
							var mapCenter = polygonbounds.getCenter();	

							google.maps.event.addListenerOnce(<?php echo self::$map_js_var ?>.map, 'idle', function() {
								<?php echo self::$map_js_var ?>.map.setCenter(mapCenter);
								<?php echo self::$map_js_var ?>.map.setZoom(13);
							});
						};
						google.maps.event.addListenerOnce(<?php echo self::$map_js_var ?>.map, 'resize', function() {
							google.maps.event.addListenerOnce(<?php echo self::$map_js_var ?>.map, 'idle', function() {
								<?php echo self::$map_js_var ?>.map.setCenter(mapCenter);	
							});
						});

						if (<?php echo self::$map_js_var ?>.search_class != 'pls_listings_search_results') {

							google.maps.event.addListener(<?php echo self::$map_js_var ?>.map, 'dragend', function() {
								google.maps.event.addListenerOnce(<?php echo self::$map_js_var ?>.map, 'idle', function() {
									get_listings('<?php echo $search_class ?>', <?php echo self::$map_js_var ?>);
									$('.<?php echo $search_class ?>').trigger('change');
								});
							});


							// prevents default on search button
					        $('.<?php echo $search_class ?>, #sort_by, #sort_dir').live('change submit', function(event) {
					            event.preventDefault();
					            get_listings('<?php echo $search_class ?>', <?php echo self::$map_js_var ?>);
					        });
						};

					});
					
				});	  
			</script>
			<style type="text/css">
				.polygon_text_area {
				 	font-size: 14px;
				 	text-shadow: #FFF 1px 1px 2px;
				    position: absolute;
				    text-align: center;
				    font-weight: bold;
				}
			</style>
			<?php echo self::get_lifestyle_controls($map_args); ?>
		<?php
		$response = ob_get_clean();
		return $response;
	}

}