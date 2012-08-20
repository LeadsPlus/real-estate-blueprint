<?php 

PLS_Map::init();
class PLS_Map {

	static $response;

	static $map_js_var;

	static $markers = array();

	function init() {
		add_action('wp_footer', array(__CLASS__, 'utilities'));
	}

	function listings($listings = array(), $map_args = array(), $marker_args = array()) {
		$map_args = self::process_defaults($map_args);
		self::make_markers($listings, $marker_args, $map_args);
		extract($map_args, EXTR_SKIP);
		
    // wp_enqueue_script('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places');
		wp_register_script('text-overlay', trailingslashit( PLS_JS_URL ) . 'libs/google-maps/text-overlay.js' );
		wp_enqueue_script('text-overlay');


		ob_start();
		?>
		<script src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places" type="text/javascript"></script>
			<script type="text/javascript">				
				var <?php echo $map_js_var; ?> = {};
				<?php echo $map_js_var; ?>.map;
				<?php echo $map_js_var; ?>.markers = [];
				<?php echo $map_js_var; ?>.infowindows = [];
				var other_polygons = [];
				var other_text = [];
				var centers = [];
				
				jQuery(function($) { 
					google.maps.event.addDomListener(window, 'load', function() {
						var latlng = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>);
						var myOptions = { zoom: <?php echo $zoom; ?>, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
						<?php echo $map_js_var ?>.map = new google.maps.Map(document.getElementById("<?php echo $canvas_id ?>"), myOptions);
						<?php foreach (self::$markers as $marker): ?>
							<?php echo $marker; ?>
						<?php endforeach ?>	
						pls_center_map(<?php echo self::$map_js_var ?>);
					});

					<?php if ($ajax_form_class): ?>
					$('.<?php echo $ajax_form_class ?>').live('change', function () {
						console.log('asdf');
						update_listings();
					});

					function update_listings (){
						var request = {};
				      	$.each($('.<?php echo $ajax_form_class ?>').serializeArray(), function(i, field) {
							request[field.name] = field.value;
						});
						request.action = 'pls_listings_ajax';
						request.iDisplayLength = 50;
						request.iDisplayStart = 0;
						request.context = '<?php echo $ajax_form_class ?>';
						request.sEcho = 1;
						
						$.post(info.ajaxurl, request, function(ajax_response, textStatus, xhr) {
							console.log(ajax_response);
						  if (ajax_response && ajax_response['aaData'] && typeof pls_google_map !== 'undefined') {
		                        pls_clear_markers(pls_google_map);
		                        if (typeof window['google'] != 'undefined') {
		                          for (var listing in ajax_response['aaData']) {
		                              var listing_json = ajax_response['aaData'][listing][1];
		                              pls_create_listing_marker(listing_json, pls_google_map);
		                          }
		                        }
		                    };
						}, 'json');
						
						console.log(request);
					}
					<?php endif; ?>
				});	  
			</script>
			<div class="<?php echo $class ?>" id="<?php echo $canvas_id ?>" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"></div>
		<?php
		return ob_get_clean();
	}

	function neighborhood($listings = array(), $map_args = array(), $marker_args = array(), $polygon) {
		$map_args = self::process_defaults($map_args);
		self::make_markers($listings, $marker_args, $map_args);
		extract($map_args, EXTR_SKIP);
		
		wp_enqueue_script('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places');
		wp_register_script('text-overlay', trailingslashit( PLS_JS_URL ) . 'libs/google-maps/text-overlay.js' );
		wp_enqueue_script('text-overlay');


		ob_start();
		?>
			<script type="text/javascript">				
				var <?php echo $map_js_var; ?> = {};
				<?php echo $map_js_var; ?>.map;
				<?php echo $map_js_var; ?>.markers = [];
				<?php echo $map_js_var; ?>.infowindows = [];
				var other_polygons = [];
				var other_text = [];
				var centers = [];
				
				jQuery(function($) { 
					google.maps.event.addDomListener(window, 'load', function() {
						var latlng = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>);
						var myOptions = { zoom: <?php echo $zoom; ?>, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
						<?php echo $map_js_var ?>.map = new google.maps.Map(document.getElementById("<?php echo $canvas_id ?>"), myOptions);
						<?php foreach (self::$markers as $marker): ?>
							<?php echo $marker; ?>
						<?php endforeach ?>	
						pls_center_map(<?php echo self::$map_js_var ?>);
					});

					<?php if ($ajax_form_class): ?>
					$('.<?php echo $ajax_form_class ?>').live('change', function () {
						console.log('asdf');
						update_listings();
					});

					function update_listings (){
						var request = {};
				      	$.each($('.<?php echo $ajax_form_class ?>').serializeArray(), function(i, field) {
							request[field.name] = field.value;
						});
						request.action = 'pls_listings_ajax';
						request.iDisplayLength = 50;
						request.iDisplayStart = 0;
						request.context = '<?php echo $ajax_form_class ?>';
						request.sEcho = 1;
						
						$.post(info.ajaxurl, request, function(ajax_response, textStatus, xhr) {
							console.log(ajax_response);
						  if (ajax_response && ajax_response['aaData'] && typeof pls_google_map !== 'undefined') {
		                        pls_clear_markers(pls_google_map);
		                        if (typeof window['google'] != 'undefined') {
		                          for (var listing in ajax_response['aaData']) {
		                              var listing_json = ajax_response['aaData'][listing][1];
		                              pls_create_listing_marker(listing_json, pls_google_map);
		                          }
		                        }
		                    };
						}, 'json');
						
						console.log(request);
					}
					<?php endif; ?>
				});	  
			</script>
			<div class="<?php echo $class ?>" id="<?php echo $canvas_id ?>" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"></div>
		<?php
		return ob_get_clean();
	}

	function polygon($listings = array(), $map_args = array(), $marker_args = array()) {
		$map_args = self::process_defaults($map_args);
		self::make_markers($listings, $marker_args, $map_args);
		extract($map_args, EXTR_SKIP);
		
		wp_enqueue_script('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places');
		wp_register_script('text-overlay', trailingslashit( PLS_JS_URL ) . 'libs/google-maps/text-overlay.js' );
		wp_enqueue_script('text-overlay');

		$polygon_html = '';
		if(WP_DEBUG !== true) {
			$cache = new PL_Cache('Map Polygon');
			// Doesn't seem to always be an array
			if(!is_array($listings)) {
				$listings_arr = array($listings);
			}
			else {
				$listings_arr = $listings;
			}
			if($polygon_html_cached = $cache->get(array_merge($listings_arr, $map_args, $marker_args))) {
				$polygon_html = $polygon_html_cached;
			}
		}
		if($polygon_html === '') {
			
			ob_start();
			?>
			<script type="text/javascript">				
				var <?php echo $map_js_var; ?> = {};
				<?php echo $map_js_var; ?>.map;
				<?php echo $map_js_var; ?>.markers = [];
				<?php echo $map_js_var; ?>.infowindows = [];
				<?php echo $map_js_var; ?>.polygons = [];
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

						var data = <?php echo json_encode(PLS_Plugin_API::get_taxonomies_by_type($polygon_search)) ?>;
						for (var j = other_polygons.length - 1; j >= 0; j--) {
							other_polygons[j].setMap(null);
						};
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
							pls_create_polygon_listeners(polygon, <?php echo $map_js_var ?>);
							customTxt = data[item].name;
				            var bounds = new google.maps.LatLngBounds();
				            for (p = 0; p < polygon.getPath().length; p++) {
							  bounds.extend(polygon.getPath().getAt(p));
							}
							var center = bounds.getCenter();
							centers.push(center);
				            other_text = new TxtOverlay(center,customTxt,"polygon_text_area",<?php echo $map_js_var ?>.map );
							other_polygons.push(polygon);
							other_polygons.push(other_text);
						};

						var polygonbounds = new google.maps.LatLngBounds();
			            for (p = 0; p < centers.length; p++) {
						  polygonbounds.extend(centers[p]);
						}
						var mapCenter = polygonbounds.getCenter();
						google.maps.event.addListenerOnce(<?php echo self::$map_js_var ?>.map, 'idle', function() {
							<?php echo self::$map_js_var ?>.map.setCenter(mapCenter);
							<?php echo self::$map_js_var ?>.map.setZoom(14);
						});
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
			<div class="<?php echo $class ?>" id="<?php echo $canvas_id ?>" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"></div>
		<?php
			$polygon_html = ob_get_clean();
			if(WP_DEBUG !== true) {
				$cache->save($polygon_html);
			}
		}
		return $polygon_html;
	}

	function lifestyle($listings = array(), $map_args = array(), $marker_args = array()) {
		$map_args = self::process_defaults($map_args);
		self::make_markers($listings, $marker_args, $map_args);
		extract($map_args, EXTR_SKIP);
		
		ob_start();
		?>
      		<script src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
			<script type="text/javascript">				
				var <?php echo $map_js_var; ?> = {};
				<?php echo $map_js_var; ?>.map;
				<?php echo $map_js_var; ?>.markers = [];
				<?php echo $map_js_var; ?>.infowindows = [];
				var other_polygons = [];
				var other_text = [];
				var centers = [];
				jQuery(function($) { 
					google.maps.event.addDomListener(window, 'load', function() {
						var latlng = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>);
						var myOptions = { zoom: <?php echo $zoom; ?>, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
						<?php echo $map_js_var ?>.map = new google.maps.Map(document.getElementById("<?php echo $canvas_id ?>"), myOptions);
						<?php foreach (self::$markers as $marker): ?>
							<?php echo $marker; ?>
						<?php endforeach ?>	
						pls_center_map(<?php echo self::$map_js_var ?>);

						var coords = [];
						var request = {};
						// search_places();
						
						function search_places () {
							get_lifestyle_form(function (new_point, request) {
								request.location = new_point;
								var service = new google.maps.places.PlacesService(<?php echo self::$map_js_var ?>.map);
						        service.search(request, service_callback);	
							}, function (new_point, request) {
								var service = new google.maps.places.PlacesService(<?php echo self::$map_js_var ?>.map);
						        service.search(request, service_callback);	
							});
						}
				        
				        function service_callback(results, status) {
				        	var points = [];
					        if (status == google.maps.places.PlacesServiceStatus.OK) {
					        	for (var i = 0; i < results.length; i++) {						           
									points.push({lat: results[i].geometry.location.lat(), lng: results[i].geometry.location.lng()});
									pls_create_marker({latlng:results[i].geometry.location, content:results[i].name, icon: 'https://chart.googleapis.com/chart?chst=d_map_spin&chld=0.3|0|FF8429|13|b' }, <?php echo self::$map_js_var ?>)
					          	}
					        }
					      }

					      function get_lifestyle_form (success_callback, failed_callback) {
					      	var response = {location: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>) , radius: 5000, types: ['atm']};
					      	var form_values = [];
					      	$.each($('#lifestyle_form_wrapper form').serializeArray(), function(i, field) {
								form_values.push(field.name);
							});
							if (form_values.length > 0) {
								response.types = [];
								for (key in form_values) {
									response.types.push(form_values[key]);
								};
							};
							failed_callback(null,response);
					      	return response;
					      }
					      
					      $('#lifestyle_form_wrapper form, .location_select_wrapper').live('change', function(event) {
					      	event.preventDefault();
					      	pls_clear_markers(<?php echo self::$map_js_var ?>);
					      	<?php foreach (self::$markers as $marker): ?>
								<?php echo $marker; ?>
							<?php endforeach ?>	
					      	search_places();
					      });
					});
				});	  
			</script>
			<?php echo self::get_lifestyle_controls($map_args); ?>
		<?php
		return ob_get_clean();
	}


	function lifestyle_polygon($listings = array(), $map_args = array(), $marker_args = array()) {
		$map_args = self::process_defaults($map_args);
		self::make_markers($listings, $marker_args, $map_args);
		extract($map_args, EXTR_SKIP);
		
		wp_enqueue_script('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places');
		wp_register_script('text-overlay', trailingslashit( PLS_JS_URL ) . 'libs/google-maps/text-overlay.js' );
		wp_enqueue_script('text-overlay');

		ob_start();
		?>
			<script type="text/javascript">				
				var <?php echo $map_js_var; ?> = {};
				<?php echo $map_js_var; ?>.map;
				<?php echo $map_js_var; ?>.markers = [];
				<?php echo $map_js_var; ?>.infowindows = [];
				<?php echo $map_js_var; ?>.polygons = [];
				var other_polygons = [];
				var other_text = [];
				var centers = [];
				
				jQuery(function($) { 
					google.maps.event.addDomListener(window, 'load', function() {
						var latlng = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>);
						var myOptions = { zoom: <?php echo $zoom; ?>, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
						<?php echo $map_js_var ?>.map = new google.maps.Map(document.getElementById("<?php echo $canvas_id ?>"), myOptions);
						<?php foreach (self::$markers as $marker): ?>
							<?php echo $marker; ?>
						<?php endforeach ?>	
						pls_center_map(<?php echo self::$map_js_var ?>);
						
						var coords = [];
						var request = {};
						
						<?php if ($search_on_load): ?>
							search_places();
						<?php endif; ?>
						
						function search_places () {
							pls_show_loading_overlay();
							var location = get_location();
							console.log(location);
							if (location && location.address) {
								pls_geocode(location.address, <?php echo self::$map_js_var ?>, search_callback, function () {
									search_callback(new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>));
								});		
							} else {
								search_callback(new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>));
							};
						}
						
						function search_callback (new_point) {
							request = {};
							request.action = 'lifestyle_polygon';
							request.location = {};
							request.location = '' + new_point.lat() + ',' + new_point.lng();
							request.radius = $('.location_select select#radius').val();
							request.types = get_form_items();
							$.post(info.ajaxurl, request, function(data, textStatus, xhr) {
								var results = data.places;
								if (results) {
									for (var i = 0; i < results.length; i++) {						           
										pls_create_marker({latlng: new google.maps.LatLng(results[i].geometry.location.lat, results[i].geometry.location.lng), content:results[i].name, icon: 'https://chart.googleapis.com/chart?chst=d_map_spin&chld=0.3|0|FF8429|13|b' }, <?php echo self::$map_js_var ?>)
					          		}
								};
					          	if (data.polygon) {
				        			pls_create_polygon(data.polygon,{strokeColor: '#55b429',strokeOpacity: 1.0,strokeWeight: 3, fillColor: 'c0ecac'}, <?php echo self::$map_js_var ?>);
				        		};
				        		if (data.listings) {
				        			for (var i = data.listings.length - 1; i >= 0; i--) {
				        				pls_create_listing_marker(data.listings[i], <?php echo self::$map_js_var ?>);
				        			};
				        		};
				        		pls_hide_loading_overlay();
							}, 'json');
						}

						function get_form_items() {
							var response = [];
							var form_values = [];
					      	$.each($('#lifestyle_form_wrapper form').serializeArray(), function(i, field) {
								form_values.push(field.name);
							});
							if (form_values.length > 0) {
								response = [];
								for (key in form_values) {
									response.push(form_values[key]);
								};
							};
							return response.join('|');
						}
				        
						function get_location () {
							var response = false;
							var location_type = $('#lifestyle_form_wrapper select[name="location"]').val();
							var location_value = $('.location_select_wrapper select#' + location_type).val();
							if (location_value != 'Any') {
								response = {};
								response.address = location_value;
							} 
							return response
						}
					      
					      $('#lifestyle_form_wrapper form, .location_select_wrapper, .location_select').live('change', function(event) {
					      	event.preventDefault();
					      	pls_clear_markers(<?php echo self::$map_js_var ?>);
					      	pls_clear_polygons(<?php echo self::$map_js_var ?>);
					      	search_places();
					      });

					      $('#lifestyle_form_wrapper select#location').live('change', function(event) {
					      	event.preventDefault();
					      	update_lifestyle_location_selects();
					      });
							
						  update_lifestyle_location_selects();
					      function update_lifestyle_location_selects () {
					      	var location_type = $('#lifestyle_form_wrapper select[name="location"]').val();
					      	$('.location_select_wrapper').hide();
					      	$('.location_select_wrapper select#' + location_type).parent().show().find('.chzn-container').css('width', '150px');
					      }
					});
				});	  
			</script>
			<?php echo self::get_lifestyle_controls($map_args); ?>
		<?php
		return ob_get_clean();
	}

	function get_lifestyle_controls ($map_args) {
		extract($map_args);
		ob_start();
		?>
		<div class="map_wrapper" style="position: relative">
				<div id="loading_overlay" class="loading_overlay" style="z-index: 50; display: none; position: absolute; width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"><?php echo $loading_overlay ?></div>
				<div id="empty_overlay" class="empty_overlay" style="z-index: 50; display: none; position: absolute; width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"><?php echo $empty_overlay ?></div>
				<div class="<?php echo $class ?>" id="<?php echo $canvas_id ?>" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px"></div>
				<section class="lifestyle_form_wrapper" id="lifestyle_form_wrapper">
					<?php if ($show_lifestyle_controls): ?>
						<div class="location_wrapper" >
							<?php echo implode(self::get_area_selectors($map_args), '') ?>
						</div>
						<div class="clear"></div>
					<?php endif ?>			
					<div class="checkbox_wrapper">
						<form>
						<?php if ($show_lifestyle_checkboxes): ?>
							<?php echo self::get_lifestyle_checkboxs($map_args); ?>
						<?php endif ?>
						</form>
					</div>
				</section>	
			</div>
		<?php
		return ob_get_clean();
	}

	private static function get_area_selectors () {
			$response = array();
			$form_options = array();
			$form_options['locality'] = array_merge(array('false' => '---'), PLS_Plugin_API::get_location_list('locality'));
	        $form_options['region'] = array_merge(array('false' => '---'), PLS_Plugin_API::get_location_list('region'));
	        $form_options['postal'] = array_merge(array('false' => '---'),PLS_Plugin_API::get_location_list('postal')); 
	        $form_options['neighborhood'] = array_merge(array('false' => '---'),PLS_Plugin_API::get_location_list('neighborhood')); 
	        
	        $response['location'] = '<div class="location_select"><select name="location" id="location" style="width: 140px">
				<option value="locality">City</option>
				<option value="region">State</option>
				<option value="postal">Zip</option>
				<option value="neighborhood">Neighborhood</option>
			</select></div>';
	        $response['locality'] = '<div class="location_select_wrapper" style="display: none">' . pls_h( 'select', array( 'name' => 'location[locality]', 'id' => 'locality' ), pls_h_options( $form_options['locality'], wp_kses_post(@$_POST['location']['locality'] ), true )) . '</div>';
	        $response['region'] = '<div class="location_select_wrapper" style="display: none">' . pls_h( 'select', array( 'name' => 'location[region]', 'id' => 'region' ), pls_h_options( $form_options['region'], wp_kses_post(@$_POST['location']['region'] ), true )) . '</div>';
	        $response['postal'] = '<div class="location_select_wrapper" style="display: none">' . pls_h( 'select', array( 'name' => 'location[postal]', 'id' => 'postal' ), pls_h_options( $form_options['postal'], wp_kses_post(@$_POST['location']['postal'] ), true )) . '</div>';
	        $response['neighborhood'] = '<div class="location_select_wrapper" style="display: none">' . pls_h( 'select', array( 'name' => 'location[neighborhood]', 'id' => 'neighborhood' ), pls_h_options( $form_options['neighborhood'], wp_kses_post(@$_POST['location']['neighborhood'] ), true )) . '</div>';
	        $response['radius'] = '<div class="location_select"><select name="radius" id="radius" style="width: 140px">
							<option value="200">200 meters</option>
							<option value="500">500 meters</option>
							<option value="1000">1000 meters</option>
							<option value="2000">2000 meters</option>
							<option value="5000" selected>5000 meters</option>
							<option value="10000">10000 meters</option>
							<option value="20000">20000 meters</option>
						</select></div>';
	        return $response;
	}

	private static function get_lifestyle_checkboxs () {
		$lifestyle_checkboxes = array('park', 'campground', 'food', 'restaurant', 'bar', 'bowling_alley', 'amusement_park', 'aquarium', 'movie_theater', 'stadium', 'school', 'university', 'pet_store', 'bus_station', 'subway_station', 'train_station', 'clothing_store', 'department_store', 'electronics_store', 'shopping_mall', 'grocery_or_supermarket');
		ob_start();
		?>
			<?php foreach ($lifestyle_checkboxes as $checkbox): ?>
				<section class="lifestyle_checkbox_item" id="lifestyle_checkbox_item">
					<input type="checkbox" name="<?php echo $checkbox ?>" id="<?php echo $checkbox ?>">
					<label for="<?php echo $checkbox ?>"><?php echo ucwords(str_replace('_', ' ', $checkbox)) ?></label>
				</section>
			<?php endforeach ?>	
		<?php
		return ob_get_clean();
	}

	private static function make_markers($listings, $marker_args, $map_args) {
    self::$markers = array();
		if ( isset($listings[0]) ) {
			foreach ($listings as $listing) {
				self::make_marker($listing, $marker_args);
			}
		} elseif (!empty($listings)) {
			self::make_marker($listings, $marker_args);
		} elseif ($map_args['featured_id']) {
			$api_response = PLS_Listing_Helper::get_featured($featured_option_id);
			foreach ($api_response['listings'] as $listing) {
				self::make_marker($listing, $marker_args);
			}
		} elseif ($map_args['auto_load_listings']) {
			$api_response = PLS_Plugin_API::get_property_list($map_args['request_params']);
			foreach ($api_response['listings'] as $listing) {
				self::make_marker($listing, $marker_args);
			}
		}
	}

	private static function make_marker($listing = array(), $args = array()) {
		extract(self::process_marker_defaults($listing, $args), EXTR_SKIP);
		ob_start();
			?>
				pls_create_listing_marker(<?php echo json_encode($listing); ?>, <?php echo self::$map_js_var ?>);
			<?php
		self::$markers[] = trim(ob_get_clean());
	}

	function utilities () {
		ob_start();
		?>
			<script type="text/javascript">
				function pls_create_polygon_listeners (polygon, map_js_var) {
					google.maps.event.addListener(polygon,"mouseover",function(){
						polygon.setOptions({fillOpacity: "0.9"});
					}); 


					google.maps.event.addListener(polygon,"mouseout",function(){
						polygon.setOptions({fillOpacity: "0.4"});
					}); 

					google.maps.event.addListener(polygon,"click",function(){
						console.log(this);
						var that = this;
						request = {};
						request.action = 'polygon_listings';
						request.vertices = this.tax.vertices;
						jQuery.post(info.ajaxurl, request, function(data, textStatus, xhr) {
							if (data) {
								pls_clear_markers(map_js_var);
								for (var i = data.length - 1; i >= 0; i--) {
									pls_create_listing_marker(data[i], map_js_var);
								};
								pls_create_polygon(that.tax.vertices,{strokeColor: '#55b429',strokeOpacity: 1.0,strokeWeight: 3, fillOpacity: 0.0}, map_js_var);

							};
						},'json');
						
						// window.location.href = this.tax.permalink;
					}); 
				}

				function pls_clear_markers (map_js_var) {
					if (map_js_var && map_js_var.markers) {
						for (var current_marker in map_js_var.markers) {
	                    	map_js_var.markers[current_marker].setMap(null);
	                    }	
	                    map_js_var.markers = [];
					};
				}

				function pls_clear_polygons (map_js_var) {
					if (map_js_var && map_js_var.markers) {
						for (var current_marker in map_js_var.polygons) {
	                    	map_js_var.polygons[current_marker].setMap(null);
	                    }	
	                    map_js_var.polygons = [];
					};
				}

				function pls_create_listing_marker (listing, map_js_var) {
					var marker_details = {};
					marker_details.latlng = new google.maps.LatLng(listing['location']['coords'][0], listing['location']['coords'][1]);

					if (listing['images'] && listing['images'][0] && listing['images'][0]['url']) {
				    	var image_url = listing['images'][0]['url'];
				    };
				    marker_details.content = '<div id="content">'+
                        '<div id="siteNotice">'+'</div>'+
                          '<h2 id="firstHeading" class="firstHeading">'+ listing['location']['full_address'] +'</h2>'+
                          '<div id="bodyContent">'+
                            '<img width="80px" height="80px" style="float: left" src="'+image_url+'" />' +
                            '<ul style="float: right; width: 130px">' +
                              '<li> Beds: '+ listing['cur_data']['beds'] +'</li>' +
                              '<li> Baths: '+ listing['cur_data']['baths'] +'</li>' +
                              '<li> Available: '+ listing['cur_data']['avail_on'] +'</li>' +
                              '<li> Price: '+ listing['cur_data']['price'] +'</li>' +
                            '</ul>' +
                          '</div>' +
                          '<div style="margin: 15px 70px; float: left; font-size: 16px; font-weight: bold;"><a href="'+listing['cur_data']['url']+'">View Details</a></div>' +
                          '<div class="clear"></div>' +
                        '</div>'+
                      '</div>';
                      pls_create_marker(marker_details, map_js_var);
				}

				function pls_create_marker (marker_details, map_js_var) {
					if (!marker_details.icon) {
						var marker_options = {position: marker_details.latlng};
					} else {
						var marker_options = {position: marker_details.latlng, icon: marker_details.icon };
					};
					var marker = new google.maps.Marker(marker_options);
					var infowindow = new google.maps.InfoWindow({content: marker_details.content});
					map_js_var.infowindows.push(infowindow);
					google.maps.event.addListener(marker, 'click', function() {
						for (i in map_js_var.infowindows) {
							map_js_var.infowindows[i].setMap(null);
						}
						infowindow.open(map_js_var.map,marker);
					});
					marker.setMap(map_js_var.map);
					map_js_var.markers.push(marker);
					pls_center_map(map_js_var);
				}

				function pls_create_polygon (points, polygon_options, map_js_var) {
					var coords = [];
	        		for (var i = points.length - 1; i >= 0; i--) {
    					coords.push(new google.maps.LatLng( points[i][0], points[i][1]));
	        		};	
	        		if (polygon_options) {
	        			var polyOptions = polygon_options;
	        			polyOptions.paths = coords;
	        		} else {
	        			var polyOptions = {strokeColor: '#000000',strokeOpacity: 1.0,strokeWeight: 3, paths: coords};
	        		}
					var neighborhood = new google.maps.Polygon(polyOptions);
					neighborhood.setMap(map_js_var.map);
					map_js_var.polygons.push(neighborhood);
				}

				function pls_center_map (map_js_var) {
					var bounds = new google.maps.LatLngBounds();
					if (map_js_var.markers) {
						for (var i = map_js_var.markers.length - 1; i >= 0; i--) {
							map_js_var.markers[i].setMap(map_js_var.map);
							bounds.extend(map_js_var.markers[i].getPosition());
						};
						map_js_var.map.fitBounds(bounds);
							google.maps.event.addListenerOnce(map_js_var.map, 'bounds_changed', function(event) {
							    if (this.getZoom() > 15) 
							        this.setZoom(15);
							});
					};
				}	

				function pls_geocode (address, map_js_var, success_callback, failed_callback, response) {
					var geocoder = new google.maps.Geocoder();
					var bounds = map_js_var.map.getBounds();
				    geocoder.geocode( { 'address': address, bounds: bounds}, function(results, status) {
				      if (status == google.maps.GeocoderStatus.OK) {
				      	success_callback(results[0].geometry.location, response);
				      } else {
				      	failed_callback(status, response);
				      }
				    });
				}

				function pls_show_loading_overlay() {
					jQuery('.map_wrapper #loading_overlay').show();
				}

				function pls_hide_loading_overlay() {
					jQuery('.map_wrapper #loading_overlay').hide();	
				}

				function pls_show_empty_overlay() {
					jQuery('.map_wrapper #empty_overlay').show();
				}

				function pls_hide_empty_overlay() {
					jQuery('.map_wrapper #empty_overlay').hide();	
				}
				
				
			</script>
		<?php
		echo ob_get_clean();
	}

	private static function process_defaults ($args) {
		$defaults = array(
        	'lat' => '42.37',
        	'lng' => '-71.03',
        	'zoom' => '14',
        	'width' => 300,
        	'height' => 300,
        	'canvas_id' => 'map_canvas',
        	'class' => 'custom_google_map',
        	'map_js_var' => 'pls_google_map',
        	'featured_id' => false,
        	'request_params' => '',
        	'auto_load_listings' => false,
        	'polygon_search' => false,
        	'life_style_search' => false,
        	'show_lifestyle_controls' => false,
        	'show_lifestyle_checkboxes' => false,
        	'loading_overlay' => '<div>Loading...</div>',
        	'empty_overlay' => '<div>No Results</div>',
        	'search_on_load' => false,
        	'polygon_options' => array(),
        	'ajax_form_class' => false
        );
        $args = wp_parse_args( $args, $defaults );
        self::$map_js_var = $args['map_js_var'];	
        return $args;
	}

	private static function process_marker_defaults ($listing, $args) {
		if (isset($listing) && is_array($listing) && isset($listing['location'])) {
			if (isset($listing['location']['coords']['latitude'])) {
				$coords = $listing['location']['coords'];
				$args['lat'] = $coords['latitude'];
				$args['lng'] = $coords['longitude'];	
			} elseif (is_array($listing['location']['coords'])) {
				$coords = $listing['location']['coords'];
				$args['lat'] = $coords[0];
				$args['lng'] = $coords[1];	
			}
		}
		$defaults = array(
        	'lat' => '42.37',
        	'lng' => '71.03',
        );
        $args = wp_parse_args( $args, $defaults );
        return $args;		
	}

	//for compatibility
	function dynamic($listings = array(), $map_args = array(), $marker_args = array()) {
		return self::listings($listings, $map_args, $marker_args);
	}
}