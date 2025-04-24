<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * @var array $atts
 */
if(!empty($atts['image'])) {
	$medium_src = wp_get_attachment_image_src($atts['image']['attachment_id'], 'medium');
	$large_src = wp_get_attachment_image_src($atts['image']['attachment_id'], 'large');

	$images = [
		$large_src
	];

	if(!empty($atts['images'])) {
		foreach ($atts['images'] as $key => $value) {
			$images[] = wp_get_attachment_image_src($value['attachment_id'], 'large');
		}
	}
	?>
	<div class="photowipe-gallery-shortcode" data-images="<?=esc_attr(wp_json_encode( $images ))?>">
		<?php
		if($atts['order']=='yes') {
			?>
			<a href="javascript:;" class="order-product btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#order-product" data-attachment="<?=$atts['image']['attachment_id']?>" data-src="<?=esc_url($atts['image']['url'])?>" data-src-medium="<?=(($medium_src)?esc_url($medium_src[0]):esc_url($atts['image']['url']))?>">Đặt mẫu này</a>
			<?php
		}

		echo wp_get_attachment_image( $atts['image']['attachment_id'], $atts['size'], false, ['alt'=>esc_attr($atts['alt'])] );
		?>
	</div>
	<?php
}