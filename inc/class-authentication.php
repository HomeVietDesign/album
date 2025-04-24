<?php
namespace Album;

class Authentication {

	use \Album\Singleton;

	private function __construct() {

		// trước khi query tài nguyên để hiển thị thì kiểm tra yêu cầu người dùng đăng nhập trước
		add_action( 'parse_request', [$this, 'require_login_use'] );

		add_filter( 'rest_authentication_errors', [$this, 'rest_authentication_require'] );

		add_filter( 'rewrite_rules_array', [$this, 'remove_rewrite_rules'] );

		add_filter( 'template_include', [$this, 'authentication_template_include'], 10 );

	}

	public function authentication_template_include($template) {
		$current_user = wp_get_current_user();

		if(has_role('administrator', $current_user) || has_role('editor', $current_user)) {
			return $template;
		}

		if( is_page() || is_single() || is_front_page() || is_home() || is_category() || is_tag() || is_tax() || is_search() ) {
			$allow_users = [];

			if(is_category() || is_tag() || is_tax()) {
				$cat_id = get_queried_object()->term_id;
				$allow_users = get_term_meta( $cat_id, '_allow_users', true );
			} else {
				$id = 0;
				if(is_page() || is_single()) {
					$id = get_the_ID();
					//debug_log($template);
				} elseif (is_front_page())  {
					$id = absint(get_option( 'page_on_front' ));
				} elseif(is_home())  {
					$id = absint(get_option( 'page_for_posts' ));
				}
				$allow_users = get_post_meta( $id, '_allow_users', true );
			}

			if( empty($allow_users) || ( !(empty($allow_users)) && !in_array($current_user->ID, $allow_users) ) ) {
				$template = THEME_DIR.'/forbidden.php';
			}
		}

		return $template;
	}

	public function remove_rewrite_rules( $rules ) {

		foreach ( $rules as $rule => $rewrite ) {
			if ( preg_match( '/(feed|attachment|trackback|comment|author|year|embed|register|wp-sitemap|type|\(\[0\-9\]\{4\}\)|\(\[\^\/\]\+\)\(\?\:\/\(\[0\-9\]\+\)\)\?\/)/', $rule ) ) {
				unset( $rules[$rule] );
			}
		}

		return $rules;
	}

	// vô hiệu hóa rest api nếu chưa đăng nhập
	public function rest_authentication_require( $result ) {
		if ( ! empty( $result ) ) {
			return $result;
		}
		if ( ! is_user_logged_in() ) {
			return new \WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
		}
		return $result;
	}

	public function require_login_use($wp) {
		if(!is_user_logged_in()) { // bắt buộc đăng nhập để truy cập hệ thống
			// chuyển hướng sang trang đăng nhập
			wp_redirect(wp_login_url(fw_current_url()));
			exit;
		}
	}

}
Authentication::get_instance();