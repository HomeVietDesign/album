<?php
namespace Album;

class Header {

	use \Album\Singleton;

	private function __construct() {
		//add_action('wp_body_open', [$this, 'top_bar'], 50);
	}

	public static function top_bar() {
		?>
		<header id="site-header" class="sticky-top" style="background-color: #115896;">
			<div class="site-header-inner container-xl py-3">
				<div class="d-flex justify-content-between fw-bold">
					<a href="<?=esc_url(home_url('/'))?>" class="text-white"><span class="dashicons dashicons-admin-home"></span> <span><?php bloginfo('name'); ?></span></a>

					<div class="d-flex">
						<a href="https://sihome.vn/he-thong" class="text-white d-block" target="_blank">Hệ thống</a>
						<?php if(is_user_logged_in()) {
							$sub_url = admin_url('upload.php');
						?>
						<span class="d-block mx-3"></span>
						<a href="<?=esc_url($sub_url)?>" class="text-white d-block" target="_blank">Media</a>
						<?php } ?>
					</div>
				</div>
			</div>
		</header>
		<?php
	}

	public static function post_tax_header() {

		the_archive_title( '<h1 class="post-tax-header pt-4 text-center">', '</h1>' );
		the_archive_description( '<div class="post-tax-description">', '</div>' );
		
	}

	public static function primary_menu() {
		$object = get_queried_object();
		$display_menu = 'yes';
		$menu = false;
		$nav_menu = '';
		if(is_page() || is_single()) {
			//debug($object);
			$display_menu = fw_get_db_post_option($object->ID, 'display_menu', 'yes');
			$menu = fw_get_db_post_option($object->ID, 'apply_menu');
		} else if(is_category()) {
			$display_menu = fw_get_db_term_option($object->term_id, 'category', 'display_menu', 'yes');
			$menu = fw_get_db_term_option($object->term_id, 'category', 'apply_menu');
		} else if(is_tax()) {
			$display_menu = fw_get_db_term_option($object->term_id, $object->taxonomy, 'display_menu', 'yes');
			$menu = fw_get_db_term_option($object->term_id, $object->taxonomy, 'apply_menu');
		}

		if($display_menu=='yes') {
			$obj_menu = ($menu) ? wp_get_nav_menu_object( $menu[0] ): false;
			if($obj_menu) {
				$nav_menu = wp_nav_menu([
					'menu' => $obj_menu,
					'container' => false,
					'echo' => false,
					'fallback_cb' => '',
					'depth' => 1,
					'items_wrap' => '<ul class="%2$s">%3$s</ul>',
				]);

			} else if(has_nav_menu('primary')) {
				$nav_menu = wp_nav_menu([
					'theme_location' => 'primary',
					'container' => false,
					'echo' => false,
					'fallback_cb' => '',
					'depth' => 1,
					'items_wrap' => '<ul class="%2$s">%3$s</ul>',
				]);
			}

			if($nav_menu!='') {
				?>
				<a id="menu-toggle" href="javascript:void(0)" class="toggle-bar">
					<span class="toggle-text">Tìm kiếm nhanh</span>
					<span class="toggle-icon"><i></i><i></i><i></i></span>
				</a>
				<nav id="main-nav">
					<div class="main-nav-inner"><?php echo $nav_menu; ?></div>
				</nav>
				<?php
			}

		}
	
	}

}

Header::get_instance();
