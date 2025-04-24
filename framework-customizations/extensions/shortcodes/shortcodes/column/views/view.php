<?php if (!defined('FW')) die('Forbidden');

$class = fw_ext_builder_get_item_width('page-builder', $atts['width'] . '/frontend_class');

$class .= (isset($atts['html_class'])) ? ' '.sanitize_html_class($atts['html_class']) : '';

$html_id = isset($atts['html_id']) ? sanitize_html_class( $atts['html_id']) : '';
$column_id = ($html_id!='') ? ' id="'.esc_attr( $html_id).'"' : '';

$bg_color = '';
if ( ! empty( $atts['background_color'] ) ) {
	$bg_color = 'background-color:' . $atts['background_color'] . ';';
}

$bg_image = '';
if ( ! empty( $atts['background_image'] ) && ! empty( $atts['background_image']['data']['icon'] ) ) {
	$bg_image = 'data-background="' . $atts['background_image']['data']['icon'] . '"';
}

$bg_size = '';
if ( ! empty( $atts['background_size'] ) && ! empty( $atts['background_size'] ) && ( $atts['background_size']!='default' ) ) {
	$bg_size = 'background-size:' . $atts['background_size'] . ';';
}

$bg_position = '';
if ( ! empty( $atts['background_position'] ) && ! empty( $atts['background_position'] ) && ( $atts['background_position']!='default' ) ) {
	$bg_position = 'background-position:' . $atts['background_position'] . ';';
}

$bg_repeat = '';
if ( ! empty( $atts['background_repeat'] ) && ! empty( $atts['background_repeat'] ) && ( $atts['background_repeat']!='default' ) ) {
	$bg_repeat = 'background-repeat:' . $atts['background_repeat'] . ';';
}

$bg_show = !empty($atts['background_show']) ? $atts['background_show']: 'full';

if(!empty($atts['no_padding'])) {
	$class .= ' no-padding';
}

$margin_top = !empty($atts['margin_top']) ? 'margin-top:'.$atts['margin_top'].';' : '';
$margin_right = !empty($atts['margin_right']) ? 'margin-right:'.$atts['margin_right'].';' : '';
$margin_bottom = !empty($atts['margin_bottom']) ? 'margin-bottom:'.$atts['margin_bottom'].';' : '';
$margin_left = !empty($atts['margin_left']) ? 'margin-left:'.$atts['margin_left'].';' : '';

$padding_top = !empty($atts['padding_top']) ? 'padding-top:'.$atts['padding_top'].';' : '';
$padding_right = !empty($atts['padding_right']) ? 'padding-right:'.$atts['padding_right'].';' : '';
$padding_bottom = !empty($atts['padding_bottom']) ? 'padding-bottom:'.$atts['padding_bottom'].';' : '';
$padding_left = !empty($atts['padding_left']) ? 'padding-left:'.$atts['padding_left'].';' : '';

$bg_style   = ( $bg_color || $bg_size || $bg_position || $bg_repeat ) ? esc_attr($bg_color . $bg_size . $bg_position . $bg_repeat): '';

$column_style = ( $margin_top || $margin_right || $margin_bottom || $margin_left ) ? esc_attr($margin_top . $margin_right . $margin_bottom . $margin_left): '';

$column_inner_style = ( $padding_top || $padding_right || $padding_bottom || $padding_left ) ? esc_attr($padding_top . $padding_right . $padding_bottom . $padding_left): '';
?>
<div<?php echo $column_id; ?> class="<?php echo esc_attr($class); ?>" style="<?php echo $column_style; if($bg_show=='full') echo $bg_style; ?>" <?php echo $bg_image; ?>>
	<div class="inner-column" style="<?php echo $column_inner_style; if($bg_show=='inner') echo $bg_style; ?>">
	<?php echo do_shortcode($content); ?>
	</div>
</div>
