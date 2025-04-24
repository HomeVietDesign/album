<?php
$shortcodes_extension = fw_ext( 'shortcodes' );

wp_enqueue_style(
	'fw-shortcode-grid-images',
	$shortcodes_extension->locate_URI( '/shortcodes/grid-images/static/css/style.css' ),
	[],
	'1.0'
);
