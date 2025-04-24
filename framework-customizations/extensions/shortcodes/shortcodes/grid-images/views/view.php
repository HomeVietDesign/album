<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * @var array $atts
 */
if(!empty($atts['images'])) {

	$class = ['image'];
	$class[] = 'text-center';
	$class[] = $atts['show_mobile'];
	$class[] = $atts['show_tablet'];
	$class[] = $atts['show_desktop'];
	
	?>
	<div class="fw-shortcode-grid-images">
		<div class="row shortcode-grid-images">
		<?php
		foreach ($atts['images'] as $key => $image) {
			?>
			<div class="<?=esc_attr(implode(' ', $class))?>">
				<?php echo wp_get_attachment_image( $image['attachment_id'], $atts['size'], false, ['style'=>'width:100%;'] ); ?>
			</div>
			<?php
		}
		?>
		</div>
	</div>
	<?php
}
