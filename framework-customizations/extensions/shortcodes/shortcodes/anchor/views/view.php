<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * @var array $atts
 */

$title = !empty($atts['title']) ? sanitize_text_field($atts['title']) : '';
$name = !empty($atts['name']) ? sanitize_key($atts['name']) : wp_generate_password(6,false,false);

if(!empty($name) && !empty($title)) {
?>
<div id="<?=esc_attr($name)?>" name="<?=esc_attr($name)?>" data-title="<?=esc_attr($title)?>" class="fw-anchor-point"></div>
<?php } ?>