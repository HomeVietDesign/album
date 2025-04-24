<?php
namespace Album;

use \FileBird\Classes\Tree as FolderTree;
use \FileBird\Model\Folder as FolderModel;

class Filebird {
	use \Album\Singleton; 

	private function __construct() {
		add_action( 'init', [$this, 'register_folder_cat_taxonomy'], -1 );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );

		add_action( 'admin_menu', [$this, 'admin_menus'] );

		
		add_action( 'wp_ajax_sync_category_folder', [$this, 'ajax_sync_category_folder'] );

		
		add_action( 'sync_category_folder_process', [$this, 'sync_category_folder_process'] );
		add_action( 'sync_folder_cat_2_folder_process', [$this, 'sync_folder_cat_2_folder_process'] );
		add_action( 'sync_folder_2_folder_cat_process', [$this, 'sync_folder_2_folder_cat_process'] );

		
		add_action( 'saved_folder_cat', [$this, 'sync_saved_folder_cat'], 10, 1 );
		add_action( 'pre_delete_term', [$this, 'sync_delete_folder_cat'], 10, 2 );
		

	}

	public function sync_delete_folder_cat($term_id, $taxonomy) {
		if($taxonomy=='folder_cat') {
			$folder_id = absint(get_term_meta( $term_id, 'folder', true ));
			if($folder_id) {
				$folder = FolderModel::findById($folder_id, '*');
				if($folder) {
					$children = FolderModel::getChildrenOfFolder($folder_id, 1);
					//debug_log($children);
					if(!empty($children)) {
						foreach ($children as $child) {
							FolderModel::updateParent($child->id, $folder->parent);
						}
					}
					FolderModel::deleteFolderAndItsChildren($folder_id);
				}
			}
		}
	}

	public function sync_saved_folder_cat($cate_id) {
		self::sync_folder_cat_2_folder_single($cate_id);
	}

	public static function sync_folder_cat_2_folder_single($cate_id) {
		$cate = get_term_by('term_id', $cate_id, 'folder_cat');
		//debug_log($cate);
		$folder_id = absint(get_term_meta( $cate->term_id, 'folder', true ));
		$folder = FolderModel::findById($folder_id, '*');

		$folder_parent = 0;
		if($cate->parent>0) {
			$folder_parent = absint(get_term_meta( $cate->parent, 'folder', true ));
		}

		if($folder_id==0 || empty($folder)) {

			$new_folder = FolderModel::newOrGet($cate->name, $folder_parent);
			$new_folder_id = absint($new_folder);
			if(is_array($new_folder) && isset($new_folder['id'])) {
				$new_folder_id = $new_folder['id'];
			}
			update_term_meta($cate->term_id, 'folder', $new_folder_id);

		} elseif($folder) {
			if($cate->name!=$folder->name) { // đổi tên
				FolderModel::updateFolderName($cate->name, $folder_parent, $folder->id);
			}

			if($folder_parent!=$folder->parent) { // đổi cấp trên
				FolderModel::updateParent($folder->id, $folder_parent);
			}
		}
	}

	public function sync_folder_cat_2_folder_process($data) {
		$user = get_user_by( 'ID', $data['user'] );
	
		if($user && user_can( $user, 'upload_files' ) && !empty($data['cates'])) {
			//debug_log($data);
			foreach ($data['cates'] as $cate_id) {
				self::sync_folder_cat_2_folder_single($cate_id);
			}
		}
	}

	public static function sync_folder_2_folder_cat_single($id) {
		
		$folder = FolderModel::findById($id, '*');

		//debug_log($folder);
		
		if($folder) {
			$cates = get_terms([
				'taxonomy' => 'folder_cat',
				'meta_key' => 'folder',
				'meta_value' => $id,
				'hide_empty' => false,
				'number' => 1,
				//'fields' => 'ids'
			]);

			$folder_cat_parent = 0;

			if($folder->parent>0) {
				$parent_cates = get_terms([
					'taxonomy' => 'folder_cat',
					'meta_key' => 'folder',
					'meta_value' => $folder->parent,
					'hide_empty' => false,
					'number' => 1,
					'fields' => 'ids'
				]);
				if(is_array($parent_cates) && !empty($parent_cates)) {
					$folder_cat_parent = $parent_cates[0];
				}
			}

			if(is_array($cates) && !empty($cates)) { // đã tồn tại folder cat
				if($folder->name!=$cates[0]->name) {
					wp_update_term( $cates[0]->term_id, 'folder_cat', ['name'=>$folder->name,'slug'=>''] );
				}
				if($folder_cat_parent != $cates[0]->parent) {
					wp_update_term( $cates[0]->term_id, 'folder_cat', ['parent'=>$folder_cat_parent] );
				}
			} else { // chưa tồn tại folder_cat

				$new_folder_cat = wp_insert_term( $folder->name, 'folder_cat', ['parent'=>$folder_cat_parent] );
				if(is_array($new_folder_cat)) {
					update_term_meta($new_folder_cat['term_id'], 'folder', $folder->id);
				}
			}
		}
		
	}

	public function sync_folder_2_folder_cat_process($data) {
		$user = get_user_by( 'ID', $data['user'] );
		//debug_log($data);
		if($user && user_can( $user, 'upload_files' ) && !empty($data['fbvs'])) {
			//debug_log($data);
			foreach ($data['fbvs'] as $id) {
				self::sync_folder_2_folder_cat_single($id);
			}
		}
	}

	public function sync_category_folder_process($data) {
		$user = get_user_by( 'ID', $data['user'] );
		if($user && user_can( $user, 'upload_files' )) {
			if($data['direction']=='folder_cat_2_folder') {
				$categories = get_terms_hierarchical_flat('folder_cat');
				//debug_log($categories);
				if( !empty($categories) ) {
					$i = 0; $cates = [];
					foreach ($categories as $key => $value) {
						$i++;
						$cates[] = $value->term_id;
						if($i==10) {
							
							as_enqueue_async_action('sync_folder_cat_2_folder_process', [['user'=>$user->ID, 'cates'=>$cates]], 'scfp', false);
							
							$i=0;
							$cates = [];
						}
					}

					if($i>0 && $i<10) {
						as_enqueue_async_action('sync_folder_cat_2_folder_process', [['user'=>$user->ID, 'cates'=>$cates]], 'scfp', false);	
					}
				}
			} elseif($data['direction']=='folder_2_folder_cat') {
				$folders = FolderTree::getFolders(null,true,true);
				//debug_log($folders);
				
				$i = 0; $fbvs = [];
				foreach ($folders as $folder) {
					$i++;
					$fbvs[] = $folder['id'];
					if($i==10) {
						as_enqueue_async_action('sync_folder_2_folder_cat_process', [['user'=>$user->ID, 'fbvs'=>$fbvs]], 'scfp', false);
						$i=0;
						$fbvs = [];
					}
				}

				if($i>0 && $i<10) {
					as_enqueue_async_action('sync_folder_2_folder_cat_process', [['user'=>$user->ID, 'fbvs'=>$fbvs]], 'scfp', false);	
				}
			
			}
		}
	}

	public static function sync_category_folder($user, $direction) {
		as_enqueue_async_action('sync_category_folder_process', [['user'=>$user, 'direction'=>$direction]], 'scf', true);
	}

	public function ajax_sync_category_folder() {

		if(check_ajax_referer( 'sync-category-folder', 'nonce', false )) {
			$user = isset($_POST['user']) ? absint($_POST['user']) : 0;
			$direction = isset($_POST['direction']) ? sanitize_key($_POST['direction']) : '';
			
			self::sync_category_folder($user, $direction);
			
			wp_send_json(true);
		}

		wp_send_json(false);
	}

	public function sync_category_folder_admin_page() {

		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<p>Đồng bộ qua lại giữa folder_cat(phân loại tùy biến của ảnh) và folder(plugin filebird).</p>
			<div id="sync-category-folder">
				<input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce( 'sync-category-folder' )); ?>">
				<input type="hidden" name="user" value="<?php echo get_current_user_id(); ?>">
				<div style="display: flex; align-items: center;">
					<select name="sync_direction" style="margin-right: 15px;">
						<option value="folder_cat_2_folder">Folder Cat -> Folder</option>
						<option value="folder_2_folder_cat">Folder -> Folder Cat</option>
					</select>
					<button type="button" class="button button-primary" id="scf_sync">Đồng bộ</button>
				</div>
				<div id="sync-category-folder-result" style="margin-top: 20px;"></div>
			</div>
		</div>
		<?php
	}

	public function admin_menus() {
		add_submenu_page( 'upload.php', 'Đồng bộ thư mục', 'Đồng bộ thư mục', 'upload_files', 'sync_category_folder', [$this, 'sync_category_folder_admin_page'] );
	}

	public function enqueue_scripts($hook) {
		//debug_log($hook);

		if('media_page_sync_category_folder' == $hook) {
			wp_enqueue_script('sync_folder_cat', THEME_URI.'/assets/js/sync_category_folder.js', array('jquery'), '', true);
		}

		
	}

	public function register_folder_cat_taxonomy() {
		$labels = array(
			'name'              => 'Thư mục',
			'singular_name'     => 'Thư mục',
			'search_items'      => 'Tìm Thư mục',
			'all_items'         => 'Tất cả Thư mục',
			'edit_item'         => 'Sửa Thư mục',
			'update_item'       => 'Cập nhật Thư mục',
			'add_new_item'      => 'Thêm mới Thư mục',
			'new_item_name'     => 'Thư mục mới',
			'menu_name'         => 'Thư mục',
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => false,
			'rewrite'           => false,
			// 'capabilities' => array(
			// 	'manage_terms' => '',
			// 	'edit_terms' => '',
			// 	'delete_terms' => '',
			// 	'assign_terms' => 'edit_posts'
			// ),
			'public' => false,
			'show_in_nav_menus' => false,
			'show_tagcloud' => false,
			//'meta_box_cb' => 'post_categories_meta_box',
			// 'default_term' => [
			// 	'name' => 'Thư mục chung',
			// 	'slug' => 'chung',
			// 	'description' => 'Thư mục mặc định.',
			// ]
		);
		register_taxonomy( 'folder_cat', 'attachment', $args ); // our new 'format' taxonomy
	}

}
Filebird::get_instance();