<?php
if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}


$options = array(
	'footer' => array(
		'type' => 'tab',
		'title' => 'Footer',
		'options' => array(
			
			'footer_text' => array(
				'label' => 'Footer text',
				'type'  => 'wp-editor',
				'size' => 'large', // small, large
				'editor_height' => 400,
				'wpautop' => true,
			),
			'footer_bg_color' => [
				'type'  => 'color-picker',
				'value' => '#000000',
				'label' => 'Footer background',
			],
			'footer_color' => [
				'type'  => 'color-picker',
				'value' => '#ffffff',
				'label' => 'Footer text color',
			]
		),
	),
);