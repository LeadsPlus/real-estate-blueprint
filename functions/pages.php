<?php 

class PLS_Pages {

	public function get_page_by_name ($page_name, $page_template = '', $create = false) {
		$page_object = get_page_by_title($page_name);
		if (isset($page_object->ID)) {
			return $page_object->ID;
		} elseif ($create) {
			$page_list[] = array( 'title' => $page_name, 'template' => $page_template);
			PLS_Plugin_API::create_page($page_list);
		}
	}
}