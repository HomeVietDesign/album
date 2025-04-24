<?php
namespace Album;

class Body {
	use \Album\Singleton;

	private function __construct() {
		//add_action('wp_body_open', [$this, 'body_open'], 10);
	}

	public function body_open() {
		$image_sizes = get_intermediate_image_sizes();

		debug($image_sizes);
	}

}
Body::get_instance();