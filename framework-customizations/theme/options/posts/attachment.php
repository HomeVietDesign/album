<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
/**
 * Framework options
 *
 * @var array $options Fill this array with options to generate framework settings form in backend
 */

$options = array(
	array(
		'context' => 'advanced',
		'title'   => 'Cài đặt nâng cao',
		'type'    => 'box',
        'options' => array(
        	'_external_url' => array(
				'type'  => 'text',
				'value' => '',
				'label' => 'URL xem thêm',
				'fw-storage' => array(
			        'type' => 'post-meta',
			        'post-meta' => '_external_url',
			    ),
			),
        	'_rating' => array(
				'type'  => 'number',
				'value' => 0,
				'label' => 'Số thứ tự',
				'fw-storage' => array(
			        'type' => 'post-meta',
			        'post-meta' => '_rating',
			    ),
			),
			
		),
    ),

);
