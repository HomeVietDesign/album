<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}


$shortcodes_extension = fw_ext( 'shortcodes' );


wp_enqueue_style(
	'fw-shortcode-folder-images',
	$shortcodes_extension->locate_URI( '/shortcodes/folder-images/static/css/styles.css' ),
	['dashicons', 'bootstrap', 'bootstrap', 'photoswipe'],
	date('YmdHis', filemtime($shortcodes_extension->locate_path('/shortcodes/folder-images/static/css/styles.css'))),
);

wp_enqueue_script(
	'fw-shortcode-folder-images',
	$shortcodes_extension->locate_URI( '/shortcodes/folder-images/static/js/scripts.js' ),
	['jquery', 'bootstrap', 'imagesloaded','photoswipe-lightbox', 'masonry'],
	date('YmdHis', filemtime($shortcodes_extension->locate_path('/shortcodes/folder-images/static/js/scripts.js'))),
	true
);