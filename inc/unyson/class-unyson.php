<?php
namespace Album;

final class Unyson {
	
	use \Album\Singleton; 

	private function __construct() {
		add_action('fw_option_types_init', [$this, '_action_theme_include_custom_option_types']);

		add_action( 'after_setup_theme', [ $this, 'on_after_setup_theme' ] );
	}

	public function on_after_setup_theme() {
		if ($this->is_compatible()) {
			add_action('wp_loaded', [$this, 'remove_try_brizy_notice']);
			add_filter('fw_use_sessions', '__return_false');
		}
	}

	public function _action_theme_include_custom_option_types() {
		require_once THEME_DIR.'/framework-customizations/option-types/code-editor/class-fw-option-type-code-editor.php';
	}

	public function remove_try_brizy_notice() {
		remove_action('admin_notices', [fw()->theme, '_action_admin_notices']);
	}

	public function is_compatible() {

		// Check if Unyson installed and activated
		if ( ! unyson_exists() ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}

		return true;

	}

	public function admin_notice_missing_main_plugin() {

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'album68' ),
			'<strong>' . esc_html__( 'Unyson Test Extension', 'album68' ) . '</strong>',
			'<strong>' . esc_html__( 'Unyson', 'album68' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

}

Unyson::get_instance();