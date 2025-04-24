<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * @var array $atts
 */

$folder_cat_id = 0;
if ( ! empty( $atts['folder_cat'] ) ) {
	$folder_cat_id = absint($atts['folder_cat'][0]);
}

$folder_cat = get_term_by('term_id',$folder_cat_id,'folder_cat');

//debug($folder_cat);

if($folder_cat) {
	$shortcode_html_id = uniqid('fw-media-folders-');
	$media_page = get_permalink(get_page_by_template('media.php'));

	$folder_cats = get_terms_tree('folder_cat', $folder_cat->term_id);

	//$fbv = absint(get_term_meta( $folder_cat->term_id, 'folder', true ));

	$folder_url = esc_url( add_query_arg('fc', $folder_cat->term_id, $media_page) );
	?>

	<div<?=esc_attr($shortcode_html_id)?> class="fw-shortcode-media-folders">

		<div class="tab-heading py-3 text-center">
			<div class="heading"><a class="text-dark" href="<?=$folder_url?>"><?=esc_html($folder_cat->name)?></a></div>
			<?php
			if(!empty($folder_cats)) {
			?>
			<div class="sub-categories">
				<?php echo get_the_list_terms($folder_cats, $media_page); ?>
			</div>
			<?php
			}
			?>
		</div>
	</div>

	<?php
}