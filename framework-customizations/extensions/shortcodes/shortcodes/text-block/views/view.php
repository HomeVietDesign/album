<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * @var array $atts
 */

$text_color = '';
if ( ! empty( $atts['text_color'] ) ) {
	$text_color = 'color:' . $atts['text_color'] . ';';
}

$text_shadow = (!empty($atts['text_shadow_h']) && !empty($atts['text_shadow_v']) && !empty($atts['text_shadow_br']) && !empty($atts['text_shadow_color'])) ? 'text-shadow:'.esc_attr($atts['text_shadow_h']).'px '.esc_attr($atts['text_shadow_v']).'px '.esc_attr($atts['text_shadow_br']).'px '.esc_attr($atts['text_shadow_color']).';' : '';

$html_id = '';
if ( ! empty( $atts['html_id'] ) ) {
	$html_id = ' id="' . sanitize_html_class($atts['html_id']) . '"';
}

$text_block_style = ($text_color || $text_shadow) ? 'style="'.esc_attr($text_color.$text_shadow).'"' : '';
?>
<div<?=$html_id?> class="fw-text-block-wrap" <?=$text_block_style?>>
<?php echo wp_format_content( $atts['text'] ); ?>
</div>