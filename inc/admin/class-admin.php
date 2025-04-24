<?php
namespace Album;

class Admin {

	use \Album\Singleton; 

	private function __construct() {
		include_once THEME_DIR.'/inc/admin/class-admin-folder_cat.php';
		include_once THEME_DIR.'/inc/admin/class-admin-page.php';
		include_once THEME_DIR.'/inc/admin/class-admin-attachment.php';

		if(is_admin()) {

			add_action( 'admin_print_footer_scripts-post.php', array($this,'admin_footer_scripts') );
			add_action( 'admin_print_footer_scripts-post-new.php', array($this,'admin_footer_scripts') );

			
			add_action( 'save_post', array($this, 'ajax_save_post'), 999999, 2 );

			add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
	
		}

	}


	public function admin_enqueue_scripts($hook) {
		global $pagenow, $mode, $wp_scripts;

		if($hook=='edit.php' || $hook=='edit-tags.php') {
			wp_enqueue_style( 'admin-edit', \Album\Assets::assets_url('/css/admin-edit.css'), [], '' );
		}

	}

	public function ajax_save_post($post_id, $post) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

		if ( wp_is_post_revision( $post_id ) ) {
        	return;
        }

		if($post->post_type === 'post' || $post->post_type === 'page') {
			
			if(isset($_POST['ajax_save_post']) && intval($_POST['ajax_save_post']) === 1){
				wp_send_json_success( null, 200 );
			}

		}
	}

	public function admin_footer_scripts() {
		global $post;
		
		if('post' === $post->post_type || 'page' === $post->post_type){
		?>
		<script type="text/javascript">
			(function ($, fwe) {
				fwe.on('fw:option-type:builder:init', function (data) {
					var fw_builder_header_tools = $('.fw-builder-header-tools');
					if(fw_builder_header_tools.length>0) {
						fw_builder_header_tools.append('<input type="submit" name="ajax_save" id="ajax-save" class="button button-primary" value="'+($('#publish').attr('name')=='publish'?'Đăng':'Cập nhật')+'" style="float:right;margin-top:-5px;margin-right:10px;">');
					}
				});
				
				$(document).on('click', '#ajax-save', function(e){
					var post_new_status = $('#post_status').val();
					var post_status = $('#original_post_status').val();
					
					var button1 = $('#publish');

					if(post_status!=post_new_status) {
						return true;
					}

					var button2 = $('#ajax-save');
					var postURL = '<?=admin_url('post.php')?>';

                    //Collate all post form data
                    var data = $('form#post').serializeArray();

                    //Set a trigger for our save_post action
                    data.push({name:'ajax_save_post', value: 1});
                    //console.log(data);
                    //The XHR Goodness
                    var btntext1 = button1.val();
                    
                    var btntext2 = button2.val();
                    button1.val('Đang lưu..').prop( "disabled", true );
                    button2.val('Đang lưu..').prop( "disabled", true );
                    $.post(postURL, data, function(response){
                    	button1.val(btntext1).prop( "disabled", false );
                    	button2.val(btntext2).prop( "disabled", false );
                   
                    });

					return false;
				});
			})(jQuery, fwEvents);
		</script>
		<?php
		}
	}

}

Admin::get_instance();