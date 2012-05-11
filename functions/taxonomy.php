<?php 

PLS_Taxonomy::init();
class PLS_Taxonomy {

	static $custom_meta = array();

	function init () {
		add_action('init', array(__CLASS__, 'metadata_customizations')); 
	}

	function get ($args = array()) {
		extract(self::process_args($args), EXTR_SKIP);
		// pls_dump($args);
		$subject = array();
		if ($street) {
			$subject += array('taxonomy' => 'street', 'term' => $street, 'api_field' => 'address');
		} elseif ($neighborhood) {
			$subject += array('taxonomy' => 'neighborhood', 'term' => $neighborhood, 'api_field' => 'neighborhood');
		} elseif ($zip) {
			$subject += array('taxonomy' => 'zip', 'term' => $zip, 'api_field' => 'postal');
		} elseif ($city) {
			$subject += array('taxonomy' => 'city', 'term' => $city, 'api_field' => 'locality');
		} elseif ($state) {
			$subject += array('taxonomy' => 'state', 'term' => $state, 'api_field' => 'region');
		}
		$term = get_term_by('slug', $subject['term'], $subject['taxonomy'], ARRAY_A );
		$custom_data = array();
		foreach (self::$custom_meta as $meta) {
			$custom_data[$meta['id']] = get_tax_meta($term['term_id'],$meta['id']);
		}
		$term = wp_parse_args($term, $custom_data);
		$term['api_field'] = $subject['api_field'];

		return $term;
	}

	function add_meta ($type, $id, $label) {
		if (in_array($type, array('text', 'textarea', 'checkbox', 'image', 'file', 'wysiwyg'))) {
			self::$custom_meta[] = array('type' => $type, 'id' => $id, 'label' => $label);
		} else {
			return false;
		}
		
	}

	function metadata_customizations () {
        include_once(PLS_Route::locate_blueprint_option('meta.php'));        
		
		//throws random errors if you aren't an admin, can't be loaded with admin_init...
        if (!is_admin()) {
        	return;	
        }
        
		$config = array('id' => 'demo_meta_box', 'title' => 'Demo Meta Box', 'pages' => array('state', 'city', 'zip', 'street', 'neighborhood'), 'context' => 'normal', 'fields' => array(), 'local_images' => false, 'use_with_theme' => false );
		$my_meta = new Tax_Meta_Class($config);
		foreach (self::$custom_meta as $meta) {
			switch ($meta['type']) {
				case 'text':
					$my_meta->addText($meta['id'],array('name'=> $meta['label']));
					break;
				case 'textarea':
					$my_meta->addTextarea($meta['id'],array('name'=> $meta['label']));
					break;
				case 'wysiwyg':
					$my_meta->addCheckbox($meta['id'],array('name'=> $meta['label']));
					break;
				case 'image':
					$my_meta->addImage($meta['id'],array('name'=> $meta['label']));
					break;
				case 'file':
					$my_meta->addFile($meta['id'],array('name'=> $meta['label']));
					break;				
				case 'checkbox':
					$my_meta->addCheckbox($meta['id'],array('name'=> $meta['label']));
					break;				
			}
		}
		$my_meta->Finish();
	}

	function process_args ($args) {
		$defaults = array(
        	
        );
        $args = wp_parse_args( $args, $defaults );
        return $args;
	}

//end of class
}