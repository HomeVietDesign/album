<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * @var array $atts
 */
$numbers = intval($atts['numbers']);
?>
<div class="fw-shortcode-media-favorite">
<?php
    echo wp_do_shortcode('media_images', ['is_favorite'=>true, 'numbers'=>$numbers]);
?>
</div>
<?php