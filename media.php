<?php
/*
Template Name: Media
*/
$current_user = wp_get_current_user();

$fc = isset($_GET['fc']) ? absint($_GET['fc']) : 0;
$allow_users = get_term_meta( $fc, '_allow_users', true );

if(has_role('administrator', $current_user) || has_role('editor', $current_user) || (!empty($allow_users) && in_array($current_user->ID, $allow_users)) ) {

	get_header();

	?>
	<div class="fw-container">
	<?php
	$folder = absint(get_term_meta( $fc, 'folder', true ));
	echo wp_do_shortcode('media_images', ['folder'=>[$folder], 'numbers'=>get_option( 'posts_per_page', '300' )]);
	?>
	</div>
	<?php

	get_footer();

} else {
	get_template_part( 'forbidden' );
}