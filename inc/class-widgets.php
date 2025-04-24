<?php
namespace Album;

class Widgets {
	use \Album\Singleton;

	private function __construct() {
		//add_action('widgets_init', [$this, 'widgets_init']);
	}

	public function widgets_init() {
		register_sidebar(
			array(
				'name'          => 'Footer',
				'id'            => 'footer',
				'description'   => 'Add widgets here to appear in your footer.',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

	}

}
Widgets::get_instance();