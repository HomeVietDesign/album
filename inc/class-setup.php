<?php
namespace Album;

class Setup {
	use \Album\Singleton;

	private function __construct() {
		add_action('after_setup_theme', [$this, 'after_setup_theme']);
		add_filter('use_widgets_block_editor', '__return_false');
		add_filter('use_block_editor_for_post_type', '__return_false', 10);

		add_filter( 'image_size_names_choose', [$this, 'image_sizes_choose'] );

		add_filter( 'wp_editor_set_quality', [$this, 'set_image_quality'] );
		add_filter( 'jpeg_quality', [$this, 'set_image_quality'] );

		if ( is_admin() ) {
			add_action( 'admin_menu', [$this, '_admin_action_menu'], 99 );
		}
	}

	public function _admin_action_menu() {
		global $menu, $submenu;

		// remove edit post menu
		if ( isset( $menu[5] ) ) {
			unset($menu[5]);
		}

		// if ( isset( $submenu['edit.php'] ) ) {
		// 	unset($submenu['edit.php']);
		// }
	}

	public function set_image_quality($quality) {

		return 60;
	}

	public function image_sizes_choose( $size_names ) {
		$new_sizes = array(
			'medium_large' => 'Medium Large',
		);
		return array_merge( $size_names, $new_sizes );
	}
	
	public static function after_setup_theme() {

		$upload_dir = (wp_get_upload_dir())['basedir'];

		if(!file_exists($upload_dir.DIRECTORY_SEPARATOR.'favorites')) {
			wp_mkdir_p( $upload_dir.DIRECTORY_SEPARATOR.'favorites' );
		}

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * This theme does not use a hard-coded <title> tag in the document head,
		 * WordPress will provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Add post-formats support.
		 */
		add_theme_support(
			'post-formats',
			array(
				'link',
				'aside',
				'gallery',
				'image',
				'quote',
				'status',
				'video',
				'audio',
				'chat',
			)
		);

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		//add_theme_support( 'menus' );

		
		register_nav_menus(
			array(
				'primary' => esc_html__('Primary menu'),
			)
		);

		add_theme_support('custom-background');

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		//add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );
		
		add_filter('get_the_archive_title_prefix', '__return_empty_string');
	}

}
Setup::get_instance();