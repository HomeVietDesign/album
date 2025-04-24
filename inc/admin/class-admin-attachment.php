<?php
namespace Album\Admin;

class Admin_Attachment {
	use \Album\Singleton;

	public function __construct() {
		if(is_admin()) {
			add_filter('attachment_fields_to_edit', [$this, 'attachment_fields_to_edit'], 1, 2);

			add_action( 'attachment_updated', array($this, 'attachment_save_fields'), 10, 1 );
			add_action( 'add_attachment', array($this, 'attachment_save_fields'), 10, 1 );
			//add_action( 'add_attachment', array($this, 'attachment_save_default'), 10, 1 );
		}
	}

	public function attachment_save_default($attachment_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

		if ( wp_is_post_revision( $attachment_id ) ) {
        	return;
        }

		if(!current_user_can( 'edit_post', $attachment_id )) {
			return $attachment_id;
		}

		global $wpdb;

		$wpdb->update( $wpdb->posts, ['post_excerpt' => 'Thông tin về sản phẩm:','post_content'=>'Liên hệ:'], ['ID' => $attachment_id] );
	}

	public function attachment_save_fields($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

		if ( wp_is_post_revision( $post_id ) ) {
        	return;
        }

		if(!current_user_can( 'edit_post', $post_id )) {
			return $post_id;
		}

		$data = [
			'_rating' => 0
		];

		if(isset($_POST['fw_options'])) {
			$data = $_POST['fw_options'];
			
			if(isset($data['_rating'])) {
				
				$data['_rating'] = intval($data['_rating']);

			}
		}

		foreach ($data as $key => $value) {
			update_post_meta( $post_id, $key, $value );
		}

	}

	public function attachment_save_code($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

		if ( wp_is_post_revision( $post_id ) ) {
        	return;
        }

		if(!current_user_can( 'edit_post', $post_id )) {
			return $post_id;
		}

		$attachment_title = $post_id;

		update_post_meta($post_id, '_code', $attachment_title);
	}

	public function attachment_fields_to_edit($form_fields, $post) {

		//$_featured = get_post_meta($post->ID, '_featured', true);
		$_rating = get_post_meta($post->ID, '_rating', true);
		$_external_url = get_post_meta($post->ID, '_external_url', true);

		$form_fields['external_url'] = array(
			'show_in_edit' => false,
			'input' => 'html',
			'html' => '<input type="text" name="fw_options[_external_url]" value="'.esc_attr( $_external_url ).'" style="width:100%">',
			'label' => 'URL xem thêm'
		);

		$form_fields['rating'] = array(
			'show_in_edit' => false,
			'input' => 'html',
			'html' => '<input type="number" name="fw_options[_rating]" value="'.esc_attr( $_rating ).'" style="width:100px"><i>&nbsp;Sắp xếp từ cao đến thấp. Muốn lên đầu thì để số cao.</i>',
			'label' => 'Số thứ tự'
		);

		return $form_fields;
	}
}
Admin_Attachment::get_instance();