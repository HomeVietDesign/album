<?php
namespace Album\Admin;

class Admin_Page {
	use \Album\Singleton;

	public function __construct() {
		if(is_admin()) {
			add_action( 'save_post', array($this, 'save_post_15'), 15, 3 );

			add_filter( 'manage_page_posts_columns', [$this, 'page_add_custom_admin_columns'], 15 );
			add_action( 'manage_page_posts_custom_column', [$this, 'page_custom_admin_columns'], 15, 2 );
		}
	}

	public function page_custom_admin_columns( $column_name, $post_id ) {
		switch ($column_name)
		{
			case 'users':
				$allow_users = get_post_meta($post_id, '_allow_users', true);
				if(!empty($allow_users)) {
					foreach ($allow_users as $uid) {
						$user = get_user_by( 'ID', $uid );
						echo '<span>'.esc_html($user->display_name).'</span>';
					}
				}
				echo '<input type="hidden" class="allow_users" value="'.esc_attr(json_encode($allow_users)).'">';
				break;
			
		}
	}

	public function page_add_custom_admin_columns( $columns ) {
		$columns['users'] = 'Người dùng';

		return $columns;
	}

	public function save_post_15($post_id, $post, $update) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		
		if ( wp_is_post_revision( $post_id ) ) return;

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if(!$update && $post->post_type=='page') {
			global $wpdb;
			$wpdb->update( $wpdb->posts, ['post_name' => date('Ymd-His', strtotime($post->post_date))], ['ID' => $post_id] );
			wp_cache_delete( $post_id, 'posts' );
		}
	}
}
Admin_Page::get_instance();