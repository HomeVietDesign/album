<?php
namespace Album\Admin;

class Admin_Folder_Cat {
	use \Album\Singleton;

	public function __construct() {
		if(is_admin()) {
			add_action( 'saved_folder_cat', [$this, 'auto_slug'] );

			add_filter( 'manage_edit-folder_cat_columns',  [$this, 'folder_cat_admin_columns'], 15 );
			add_action( 'manage_folder_cat_custom_column', [$this, 'folder_cat_admin_columns_value'], 15, 3 );
		}
	}

	public function folder_cat_admin_columns_value( $row, $column_name, $term_id ) {
		switch($column_name) {
			case 'users':
				$allow_users = get_term_meta($term_id, '_allow_users', true);
				if(!empty($allow_users)) {
					foreach ($allow_users as $uid) {
						$user = get_user_by( 'ID', $uid );
						echo '<span>'.esc_html($user->display_name).'</span>';
					}
				}
				break;
			case 'folder':
				$folder_id = absint(get_term_meta($term_id, 'folder', true));
				if(class_exists('\FileBird\Model\Folder')) {
					$folder = \FileBird\Model\Folder::findById($folder_id, '*');
					if($folder) {
						?>
						<a href="<?=esc_url(admin_url('upload.php?folder='.$folder->id))?>"><?=esc_html($folder->name)?></a>
						<?php
					}
				}

				break;
		}
	}

	public function folder_cat_admin_columns($columns) {
		//debug_log($columns);
		$new_columns = [];
		if(isset($columns['cb'])) {
			$new_columns['cb'] = $columns['cb'];
			unset($columns['cb']);
		}
		if(isset($columns['name'])) {
			$new_columns['name'] = $columns['name'];
			unset($columns['name']);
		}

		$columns['folder'] = 'Thư viện';
		
		if(isset($columns['posts'])) {
			$columns['posts'] = 'Đếm';
		}

		$columns['users'] = 'Người dùng';

		$columns = array_merge($new_columns, $columns);

		return $columns;
	}

	public function auto_slug($term_id) {
		$term = get_term_by('term_id', $term_id, 'folder_cat');
		//debug_log($term);
		if($term && !preg_match('/\d{8}-\d{6}/', $term->slug)) {
			global $wpdb;
			$slug = date('Ymd-His', current_time( 'U' ));
			$wpdb->update( $wpdb->terms, ['slug' => $slug], ['term_id' => $term_id] );
			wp_cache_delete( $term_id, 'terms' );
		}
	}
}
Admin_Folder_Cat::get_instance();