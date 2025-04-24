<?php
namespace Album;

class Assets {

	use \Album\Singleton;

	private function __construct() {
		add_action('wp_enqueue_scripts', [$this, 'enqueue_styles'], 50);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 50);
	}

	public static function enqueue_styles() {

		wp_dequeue_style( 'font-awesome' );

		$font_families[] = 'Roboto:400,500,700';

		$query_args = array(
			'family'  => urlencode( implode( '|', $font_families ) ),
			'display' => urlencode( 'swap' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

		wp_register_style( 'google-fonts', $fonts_url );
		
		wp_register_style( 'bootstrap', self::libs_url('/bootstrap/css/bootstrap.min.css'), [], '5.1.3' );

		wp_register_style( 'photoswipe', self::libs_url('/PhotoSwipe/photoswipe.css'), [], '5.4.3' );

		wp_enqueue_style( 'album', self::assets_url('/css/style.css'), ['bootstrap','dashicons'], date('YmdHis', filemtime(THEME_DIR . '/assets/css/style.css')) );
	}

	public static function enqueue_scripts() {
		wp_dequeue_style( 'wp-block-library' );
    	wp_dequeue_style( 'wp-block-library-theme' );
    	wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
    
		$recaptcha_keys = Common::get_recaptcha_keys();

		if(!$recaptcha_keys['ctf7'] && $recaptcha_keys['sitekey']!='' && !wp_script_is('google-recaptcha', 'registered')) {
			wp_enqueue_script( 'google-recaptcha',
				add_query_arg(
					[ 'render' => $recaptcha_keys['sitekey'] ],
					'https://www.google.com/recaptcha/api.js'
				),
				[],
				'3.0',
				true
			);
		}

		wp_register_script( 'bootstrap', self::libs_url('/bootstrap/js/bootstrap.bundle.min.js'), [], '5.1.3', true);

		//wp_register_script( 'jquery-lazy', self::libs_url('/jquery.lazy/jquery.lazy.min.js'), [], '1.7.10', true);
		//wp_register_script( 'jquery-lazy-plugin', self::libs_url('/jquery.lazy/jquery.lazy.plugins.min.js'), ['jquery-lazy'], '1.4', true);

		wp_register_script( 'photoswipe', self::libs_url('/PhotoSwipe/photoswipe.umd.min.js'), ['jquery'], '5.4.3', true);
		wp_register_script( 'photoswipe-lightbox', self::libs_url('/PhotoSwipe/photoswipe-lightbox.umd.min.js'), ['photoswipe'], '5.4.3', true);

		wp_enqueue_script( 'album', self::assets_url('/js/scripts.js'), ['jquery','bootstrap','masonry','imagesloaded'], date('YmdHis', filemtime(THEME_DIR . '/assets/js/scripts.js')), true);

		wp_localize_script( 'jquery', 'theme', array('home_url'=>esc_url(home_url()), 'ajax_url'=>esc_url(admin_url('admin-ajax.php')), 'sitekey'=>$recaptcha_keys['sitekey'], 'is_user_logged_in' => is_user_logged_in() ) );

	}

	public static function libs_url($file) {
		return THEME_URI.'/libs'.$file;
	}

	public static function assets_url($file) {
		return THEME_URI.'/assets'.$file;
	}

}

Assets::get_instance();