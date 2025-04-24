<?php
namespace Album;

class Ajax {

	use \Album\Singleton;

	private function __construct() {
		add_action('wp_ajax_remove_favorites', [$this, 'ajax_remove_favorites']);
        add_action('wp_ajax_nopriv_remove_favorites', [$this, 'ajax_remove_favorites']);
	}

	public function ajax_remove_favorites() {
        \FW_Shortcode_Media_Images::remove_favorites();
        wp_send_json(true);
    }

}

Ajax::get_instance();