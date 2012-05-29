<?php 

class PLS_Meta_Helper {

	function author () {
		$author_tag = '';
		$api_response = PLS_Plugin_API::get_user_details();
		if (!empty($api_response) && isset($api_response['first_name']) && isset($api_response['last_name'])) {
			$author_tag = '<meta name="author" content="' . $api_response['first_name'] .  ' ' . $api_response['last_name'] . '">';
		}
		echo $author_tag;
	}

}