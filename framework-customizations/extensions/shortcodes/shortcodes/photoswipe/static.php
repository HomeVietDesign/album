<?php
$shortcodes_extension = fw_ext( 'shortcodes' );

wp_enqueue_style(
	'fw-shortcode-photoswipe',
	$shortcodes_extension->locate_URI( '/shortcodes/photoswipe/static/css/style.css' ),
	['photoswipe-skin']
);


wp_enqueue_script(
	'fw-shortcode-photoswipe',
	$shortcodes_extension->locate_URI( '/shortcodes/photoswipe/static/js/script.js' ),
	['imagesloaded', 'jquery', 'photoswipe-ui'],
	false,
	true
);
