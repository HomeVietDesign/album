<?php if (!defined('FW')) die('Forbidden');

class FW_Shortcode_Folder_Images extends FW_Shortcode
{
	
	public function _init()
	{
		add_action('wp_ajax_folder_images_paginate', [$this, 'ajax_folder_images_paginate']);
		//add_action('wp_ajax_nopriv_folder_images_paginate', [$this, 'ajax_folder_images_paginate']);

		add_action('wp_ajax_folder_images_toggle_favorite', [$this, 'ajax_folder_images_toggle_favorite']);
		//add_action('wp_ajax_nopriv_folder_images_toggle_favorite', [$this, 'ajax_folder_images_toggle_favorite']);

		add_action('wp_ajax_folder_images_check_favorite', [$this, 'ajax_folder_images_check_favorite']);
		//add_action('wp_ajax_nopriv_folder_images_check_favorite', [$this, 'ajax_folder_images_check_favorite']);

		add_action('wp_ajax_folder_images_toggle_favorite_all', [$this, 'ajax_folder_images_toggle_favorite_all']);
        //add_action('wp_ajax_nopriv_folder_images_toggle_favorite_all', [$this, 'ajax_folder_images_toggle_favorite_all']);

		add_action('wp_ajax_folder_images_delete_medias_selected', [$this, 'ajax_folder_images_delete_medias_selected']);
		//add_action('wp_ajax_nopriv_folder_images_delete_medias_selected', [$this, 'ajax_folder_images_delete_medias_selected']);

		add_action('wp_ajax_folder_images_put_bottom_medias_selected', [$this, 'ajax_folder_images_put_bottom_medias_selected']);
		//add_action('wp_ajax_nopriv_folder_images_put_bottom_medias_selected', [$this, 'ajax_folder_images_put_bottom_medias_selected']);

		add_action('wp_ajax_folder_images_save_medias_selected', [$this, 'ajax_folder_images_save_medias_selected']);
		add_action('wp_ajax_folder_images_unsave_medias_selected', [$this, 'ajax_folder_images_unsave_medias_selected']);

	}

	public static function litespeed_delete_cache($uri='') {
		if(''==$uri) {
			$uri = $_REQUEST['uri']?$_REQUEST['uri']:'';
		}

		if($uri!='') {
	        wp_remote_request(home_url($uri), ['method'=>'PURGE']);
	    }
	}

	public function ajax_folder_images_unsave_medias_selected() {
		global $wpdb;

		$response = [
			'code' => 0,
			'msg' => ''
		];

		if(current_user_can('edit_posts')) {
			$items = isset($_POST['items']) ? array_map('absint', $_POST['items']) : [];
			$folder = isset($_POST['folder']) ? absint($_POST['folder']) : 0;
			$folder_cat = isset($_POST['folder_cat']) ? absint($_POST['folder_cat']) : 0;

			if($folder_cat && $folder==get_term_meta( $folder_cat, 'folder', true )) {
				if(!empty($items)) {
					foreach ($items as $attachment_id) {
						wp_remove_object_terms( $attachment_id, [$folder_cat], 'folder_cat' );
					}

					self::litespeed_delete_cache();
					self::litespeed_delete_cache(remove_query_arg( 'cat', $_POST['uri'] ));

					$response['code'] = 1;
					$response['msg'] = 'Đã thực hiện';
				} else {
					$response['msg'] = 'Không có phần tử nào được lựa chọn.';
				}
			} else {
				$response['msg'] = 'Thông tin không chính xác.';
			}
		} else {
			$response['msg'] = 'Không đủ quyền hạn để thực hiện tác vụ này.';
		}

		wp_send_json($response);
	}

	public function ajax_folder_images_save_medias_selected() {
		global $wpdb;

		$response = [
			'code' => 0,
			'msg' => ''
		];

		if(current_user_can('edit_posts')) {
			$items = isset($_POST['items']) ? array_map('absint', $_POST['items']) : [];
			$folder = isset($_POST['folder']) ? absint($_POST['folder']) : 0;
			$folder_cat = isset($_POST['folder_cat']) ? absint($_POST['folder_cat']) : 0;

			if($folder_cat && $folder==get_term_meta( $folder_cat, 'folder', true )) {
				if(!empty($items)) {
					foreach ($items as $attachment_id) {
						wp_set_post_terms( $attachment_id, [$folder_cat], 'folder_cat', false );
					}

					self::litespeed_delete_cache();
					self::litespeed_delete_cache(add_query_arg( 'cat', $folder_cat, $_POST['uri'] ));

					$response['code'] = 1;
					$response['msg'] = 'Đã thực hiện';
				} else {
					$response['msg'] = 'Không có phần tử nào được lựa chọn.';
				}
			} else {
				$response['msg'] = 'Thông tin không chính xác.';
			}
		} else {
			$response['msg'] = 'Không đủ quyền hạn để thực hiện tác vụ này.';
		}

		wp_send_json($response);
	}

	public function ajax_folder_images_put_bottom_medias_selected() {
		global $wpdb;

		$response = [
			'code' => 0,
			'msg' => ''
		];

		if(current_user_can('edit_posts')) {
			$items = isset($_POST['items']) ? array_map('absint', $_POST['items']) : [];
			$folder = isset($_POST['folder']) ? absint($_POST['folder']) : 0;
			if($folder===0) $folder = -1;

			if(!empty($items)) {
				$args = [
					'post_type' => 'attachment',
					'posts_per_page' => 1,
					'post_status' => 'inherit',
					'orderby' => 'date',
					'order' => 'ASC',
					'fbv' => $folder,
				];

				$attachments = get_posts($args);

				$min = ($attachments)?$attachments[0]->post_date:'1970-01-01 00:00:00';
				$min_dec = date('Y-m-d H:i:s', strtotime($min.' -1 minute'));
				$sql = "UPDATE {$wpdb->posts} SET `post_date`='{$min_dec}' WHERE `ID` IN (".implode(',', $items).")";

				// debug_log($min_dec);
				// debug_log($sql);

				$update = $wpdb->query( $sql );

				if($update) {
					foreach ($items as $attachment_id) {
						$_rating = intval(get_post_meta( $attachment_id, '_rating', true ));
						update_post_meta( $attachment_id, '_rating', -1 );
						update_post_meta( $attachment_id, '_old_rating', $_rating );
					}

					self::litespeed_delete_cache();

					$response['code'] = 1;
				}
			}
		} else {
			$response['msg'] = 'Không đủ quyền hạn để thực hiện tác vụ này.';
		}

		wp_send_json($response);
	}

	public function ajax_folder_images_delete_medias_selected() {

		$response = [
			'code' => 0,
			'msg' => ''
		];

		if(current_user_can('edit_posts')) {
			if(current_user_can('upload_files')) {
				if(check_ajax_referer('media_front_end_ajax', 'nonce', false)) {
					$items = isset($_POST['items']) ? array_map('absint', $_POST['items']) : [];
					if(!empty($items)) {
						foreach ($items as $attachment_id) {
							wp_delete_attachment( $attachment_id, false );
							//$response['msg'] .= ', '.$attachment_id;
						}

						self::fix_favorites();
					}

					self::litespeed_delete_cache();

					$response['code'] = 1;
					$response['msg'] = 'Đã xóa!';

				} else {
					$response['code'] = -3;
					$response['msg'] = 'Quá hạn xử lý! Tải lại trang rồi thử lại!';
				}
			} else {
				$response['code'] = -2;
				$response['msg'] = 'Không có đủ quyền hạn để thực hiện hành động này!';
			}
		} else {
			$response['code'] = -1;
			$response['msg'] = 'Không đủ quyền hạn để thực hiện hành động này!';
		}

		wp_send_json($response);
	}

	public function ajax_folder_images_toggle_favorite_all() {
		$response = [
			'code' => 0,
			'msg' => ''
		];

		$attachments = isset($_POST['attachments']) ? array_map('absint', $_POST['attachments']) : [];
		$act = isset($_POST['act']) ? sanitize_key( $_POST['act'] ) : '';

		if(!empty($attachments) && in_array($act, ['on', 'off'])) {
			if($act=='on') {
				foreach ($attachments as $attachment_id) {
					self::add_favorite($attachment_id);
				}
			} else {
				foreach ($attachments as $attachment_id) {
					self::remove_favorite($attachment_id);
				}
			}

			self::litespeed_delete_cache();

			$response['code'] = 1;
			$response['msg'] = 'OK!';
		} else {
			$response['code'] = -1;
			$response['msg'] = 'Danh sách trống!';
		}

		wp_send_json($response);
	}

	public function ajax_folder_images_check_favorite() {
		$favorites = self::get_favorites();
		if(!empty($favorites)) {
			wp_send_json(true);
		}
		wp_send_json(false);
	}

	public function ajax_folder_images_toggle_favorite() {
        $attachment = isset($_POST['attachment']) ? absint($_POST['attachment']) : 0;
        $favorite = isset($_POST['favorite']) ? absint($_POST['favorite']) : 0;

        if($favorite) {
            self::remove_favorite($attachment);
        } else {
            self::add_favorite($attachment);
        }

        wp_send_json(!$favorite);
    }

	public function ajax_folder_images_paginate() {

		$args = isset($_REQUEST['query']) ? $_REQUEST['query'] : [];
		$paged = isset($_REQUEST['paged']) ? absint($_REQUEST['paged']) : 1;

		$args['paged'] = $paged;

		$query = new \WP_Query($args); 

		$response = [
			'items' => '',
			'images' => '',
			'paginate_links' => ''
		];


		if($query->have_posts()) {

			$images = [];

			foreach($query->posts as $attachment) {
				$image = wp_get_attachment_image_src( $attachment->ID, 'full', false );
				$images[] = $image;
			}

			$response['images'] = wp_json_encode( $images );

			ob_start();
			self::loop_medias($query);
			$response['items'] = ob_get_clean();

			$response['paginate_links'] = self::pagination($query);

		} else {
			$response['items'] = '<div class="text-center text-danger py-3">Không tìm thấy kết quả phù hợp!</div>';
		}

		wp_reset_postdata();

		wp_send_json($response);

	}

	public static function media_images($query) {

		//debug($query);

		if($query->have_posts()) {
			$images = [];

			foreach($query->posts as $attachment) {
				$image = wp_get_attachment_image_src( $attachment->ID, 'full', false );
				$images[] = $image;
			}
			?>

			<div class="list-media row">
			<?php self::loop_medias($query); ?>
			</div>

			<?php
			wp_reset_postdata();

		} else {
			echo '<div class="text-center py-3">Chưa có dữ liệu.</div>';
		}
	}

	public static function loop_medias($query) {
		$editing = false;

		$user = wp_get_current_user();

		if ( $user->exists() ) {
			if(in_array('administrator',$user->roles)) {
				$editing = true;
			}
		}

		while ($query->have_posts()) {
			$query->the_post();

			self::display_media_image($editing);

		}

	}

	public static function display_media_image($editing) {
		$ID = get_the_ID();
		global $post;
		//debug_log($post);
		?>
		<div class="item col-md-6 col-lg-4">
			<div class="inner">
				<div class="image text-center bg-light">
					<?php
					//$library_link = get_the_excerpt();

					if($editing) {
						?>
						<a href="<?php echo admin_url('upload.php?item='.$ID); ?>" target="_blank" class="edit z-3"><span class="dashicons dashicons-edit"></span> Chỉnh sửa</a>
						<?php
					}

					$rating = intval(get_post_meta($ID, '_rating', true));
					if($rating<0) {
						?>
						<div class="position-absolute top-0 end-0 bg-warning lh-1 d-flex align-items-center p-1 z-3"><span class="dashicons dashicons-download"></span></div>
						<?php
					}


					if (str_starts_with($post->post_mime_type, 'video/')) {
						// code for video
						$metadata = wp_get_attachment_metadata( $ID );
						//debug($metadata);

						?>
						<div class="video-wrap position-relative z-1" data-ratio="<?=($metadata['height']/$metadata['width'])?>">
						<video preload="lazy" width="<?=absint($metadata['width'])?>" height="<?=absint($metadata['height'])?>" controls buffered playsinline onloadeddata="videoLoaded(this)" muted loop>
						<source src="<?=esc_url(wp_get_attachment_url($ID))?>" type="<?=esc_attr($post->post_mime_type)?>">
						</video>
						<button type="button" class="btn hide reload">Tải lại</button>
						</div>
						<?php
					} elseif (str_starts_with($post->post_mime_type, 'image/')) {
						
						?>
						<div class="pswp-gallery position-relative z-1">
							<?php
								//echo wp_get_attachment_image( $ID, 'medium_large', false );
								
								$src_full = wp_get_attachment_image_src( $ID, 'full' );
								$src = wp_get_attachment_image_src( $ID, 'medium_large', false );
								$srcset = wp_get_attachment_image_srcset( $ID, 'medium_large', false );
								$sizes = wp_get_attachment_image_sizes( $ID, 'medium_large' );
								$image_meta = wp_get_attachment_metadata($ID);

								$src_full_w = $src_full[1];
								$src_full_h = $src_full[2];
								$src_w = $src[1];
								$src_h = $src[2];

								if(intval($image_meta['image_meta']['orientation'])==6) {
									$src_full_w = $src_full[2];
									$src_full_h = $src_full[1];
									$src_w = $src[2];
									$src_h = $src[1];
								}
								
								$img_placeholder = 'data:image/svg+xml;base64,'.base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="'.$src_w.'" height="'.$src_h.'" viewBox="0 0 '.$src_w.' '.$src_h.'"><rect width="100%" height="100%" style="fill:#cfd4db;fill-opacity: 0.1;"/></svg>');
							?>
							<a href="<?=esc_url($src_full[0])?>" data-pswp-width="<?=$src_full_w?>" data-pswp-height="<?=$src_full_h?>" target="_blank">
								<img class="lazy" data-src="<?=esc_url($src[0])?>" data-srcset="<?=esc_attr($srcset)?>" data-sizes="<?=esc_attr($sizes)?>" width="<?=$src_w?>" height="<?=$src_h?>" src="<?php echo esc_attr($img_placeholder); ?>">
							</a>

							<!-- <div class="position-absolute bottom-0 start-50 translate-middle-x m-1 px-1 bg-light-subtle"><?php echo esc_html('orientation: '.intval($image_meta['image_meta']['orientation'])); ?></div> -->
						</div>
					<?php } ?>
				</div>

				<?php
				$is_favorite = self::is_favorite($ID);
				$rating = intval(get_post_meta( $ID, '_rating', true ));
				$external_url = get_post_meta( $ID, '_external_url', true );
				?>
				<div class="info position-relative">
					<label class="title w-100 d-flex align-items-center" for="media-item-<?=$ID?>">
						<input id="media-item-<?=$ID?>" class="medias_select media-item-<?=$ID?>" type="checkbox" value="<?=$ID?>">
						<span class="flex-grow-1 ms-2"><?php the_title(); ?></span>
					</label>
					<div class="actions position-absolute d-flex">
						<!-- <button type="button" data-attachment="<?=$ID?>" data-rating="<?=$rating?>" class="toggle-arrange btn btn-sm btn-outline-primary" title="<?php echo ($rating==-1)?'Đẩy lên vị trí trước đây':'Đưa xuống cuối danh sách'; ?>">
						<span class="dashicons <?php echo ($rating==-1)?'dashicons-arrow-up-alt':'dashicons-arrow-down-alt'; ?>"></span>
						</button> -->
						<?php
						if($external_url) {
						?>
						<a class="btn btn-sm btn-primary" href="<?php echo esc_attr($external_url); ?>" target="_blank"><span class="dashicons dashicons-visibility"></span></a>
						<?php
						}
						?>
						<button type="button" data-attachment="<?=$ID?>" data-favorite="<?php echo ($is_favorite)?'1':'0'; ?>" class="toggle-favorite btn btn-sm btn-outline-danger ms-2 toggle-favorite-<?=$ID?>" title="<?php echo ($is_favorite)?'Bỏ chọn':'Lựa chọn'; ?>">
						<span class="dashicons <?php echo ($is_favorite)?'dashicons-star-filled':'dashicons-star-empty'; ?>"></span>
						</button>
						<!-- <button type="button" class="put-bottom-button btn btn-primary p-0 rounded-circle position-fixed hidden" title="Chuyển đã chọn xuống cuối"><span class="dashicons dashicons-arrow-down-alt"></span></button> -->
					</div>
				</div>

				<?php if(''!=$post->post_excerpt) { ?>
					<div class="excerpt mb-2"><?php echo $post->post_excerpt; ?></div>
				<?php } ?>
				<?php if(''!=$post->post_content) { ?>
					<div class="content mb-2"><?php echo $post->post_content; ?></div>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	public static function pagination($wp_query, $end_size=3, $mid_size=2) {
		// Get max pages and current page out of the current query, if available.
		$total   = (int)$wp_query->max_num_pages;
		$current = ($wp_query->query_vars['paged']==0)?1:(int)$wp_query->query_vars['paged'];

		// Who knows what else people pass in $args.
		if ( $total < 2 ) {
			return;
		}

		if ( $end_size < 1 ) {
			$end_size = 1;
		}

		if ( $mid_size < 0 ) {
			$mid_size = 2;
		}

		$r          = '';
		$page_links = array();
		$dots       = false;

		if ( $current && 1 < $current ): 
			$page_links[] = '<button class="prev page-numbers ms-1 btn btn-sm btn-outline-dark" data-paged="'.($current - 1).'" type="button"><span class="dashicons dashicons-arrow-left"></span></button>';
		endif;

		for ( $n = 1; $n <= $total; $n++ ) :
			if ( $n == $current ) :
				$page_links[] = '<span class="page-numbers ms-1 current  btn btn-sm btn-danger" data-paged="'.$n.'">'.$n.'</span>';

				$dots = true;
			else :
				if ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) :
					$page_links[] = '<button class="page-numbers ms-1 btn btn-sm btn-outline-dark" data-paged="'.$n.'" type="button">'.$n.'</button>';

					$dots = true;
				elseif ( $dots ) :
					if($total>2*$end_size+1) {
						$page_links[] = '<span class="page-numbers ms-1 dots btn btn-sm">&hellip;</span>';
					} else {
						$page_links[] = '<button class="page-numbers ms-1 btn btn-sm btn-outline-dark" data-paged="'.$n.'" type="button">'.$n.'</button>';
					}
					$dots = false;
				endif;
			endif;
		endfor;

		if ( $current && $current < $total ) :
			$page_links[] = '<button class="next page-numbers ms-1 btn btn-sm btn-outline-dark" data-paged="'.($current + 1).'" type="button"><span class="dashicons dashicons-arrow-right"></span></button>';
		endif;

		$r = implode( "\n", $page_links );

		return $r;
	}

	public static function get_folder_parents($folder, $parents=[]) {
		if($folder->parent>0) {
			$parent = \FileBird\Model\Folder::findById($folder->parent, '*');
			$parents[] = $parent;
			$parents = self::get_folder_parents($parent, $parents);
		}

		return array_reverse($parents);
	}

	public static function fix_favorites() {
		$favorites = self::get_favorites();
		if(!empty($favorites)) {
			$fix_favorites = [];
			foreach ($favorites as $key => $value) {
				$check = get_post( $value );
				//debug_log($check);
				if($check && $check->post_status!='trash') {
					$fix_favorites[] = $value;
				}
			}
			self::set_favorites($fix_favorites);
		}
	}

	public static function set_favorites($favorites) {
		update_option('favorites', $favorites);
	}

	public static function remove_favorites() {
		update_option('favorites', []);
	}

	public static function add_favorite($attachment_id) {
		$favorites = self::get_favorites();
		if(!in_array($attachment_id, $favorites)) {
			$favorites[] = $attachment_id;
		}
		update_option('favorites', $favorites);
	}

	public static function remove_favorite($attachment_id) {
		$favorites = self::get_favorites();
		if(in_array($attachment_id, $favorites)) {
			unset($favorites[array_search($attachment_id, $favorites)]);
		}
		update_option('favorites', $favorites);
	}

	public static function get_favorites() {
		$favorites = get_option('favorites', '');
		if(empty($favorites)) $favorites = [];

		return $favorites;
	}

	public static function is_favorite($attachment_id) {
		$favorites = self::get_favorites();

		return in_array($attachment_id, $favorites);
	}
}