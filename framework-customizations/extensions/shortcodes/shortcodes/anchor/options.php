<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'title' => array(
		'label' => __('Title', 'fw'),
		'desc'  => __('Anchor Title', 'fw'),
		'type'  => 'text'
	),
	'name' => array(
		'label' => __('Name', 'fw'),
		'desc'  => __('Anchor Name', 'fw'),
		'type'  => 'text',
		'value' => wp_generate_password( 6, false, false )
	),
);