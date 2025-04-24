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
		'title'   => 'Quyền truy cập',
		'type'    => 'box',
        'options' => array(
        	'_allow_users' => array(
				'label' => 'Chọn tài khoản',
				'desc'  => '',
				'type'  => 'multi-select',
				'population' => 'users',
				'source' => array( 'contributor', 'subscriber', 'author' ),
				'limit' => 100,
				'fw-storage' => array(
			        'type' => 'term-meta',
			        'term-meta' => '_allow_users',
			    ),
			),
				
		),
    ),
   
);
