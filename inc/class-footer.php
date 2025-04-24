<?php
namespace Album;

class Footer {
	use \Album\Singleton;

	private function __construct() {
		//add_action('wp_footer', [$this, 'display_widgets']);
	
	
	}


	public static function display() {
		?>
		<footer id="site-footer" class="py-5">
			<div class="site-footer-inner container-xl">
				<div class="row">
					<div class="site-footer-col col">
						<div class="col-inner"><?php echo do_shortcode( fw_get_db_settings_option('footer_text','') ); ?></div>
					</div>
				</div>
			</div>
		</footer>
		<?php
	}

}

Footer::get_instance();