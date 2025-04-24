<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$image_sizes = get_intermediate_image_sizes();
//debug_log($image_sizes);
$sizes = [];
foreach ($image_sizes as $key => $value) {
	$sizes[$value] = $value;
}
$sizes['full'] = 'Ảnh gốc';

$options = array(
	'gimage'            => array(
		'type' => 'group',
		'options' => array(
			'image' => array(
				'type'  => 'upload',
				'label' => __( 'Choose Image', 'fw' ),
				'desc'  => __( 'Either upload a new, or choose an existing image from your media library', 'fw' )
			),
			'alt'  => array(
				'type'  => 'text',
				'label' => __( 'Alt', 'fw' ),
				'value' => ''
			),
			'size' => array(
				'type' => 'select',
				'value' => 'large',
				'label' => 'Kích thước',
				'choices' => $sizes
			),
		)
	),
	/*
	'size'             => array(
		'type'    => 'group',
		'options' => array(
			'width'  => array(
				'type'  => 'text',
				'label' => __( 'Width', 'fw' ),
				'desc'  => __( 'Set image width', 'fw' ),
				'value' => 300
			),
			'height' => array(
				'type'  => 'text',
				'label' => __( 'Height', 'fw' ),
				'desc'  => __( 'Set image height', 'fw' ),
				'value' => 200
			)
		)
		
	),
	*/
	'image-link-group' => array(
		'type'    => 'group',
		'options' => array(
			'link'   => array(
				'type'  => 'text',
				'label' => __( 'Image Link', 'fw' ),
				'desc'  => __( 'Where should your image link to?', 'fw' )
			),
			'target' => array(
				'type'         => 'switch',
				'label'        => __( 'Open Link in New Window', 'fw' ),
				'desc'         => __( 'Select here if you want to open the linked page in a new window', 'fw' ),
				'right-choice' => array(
					'value' => '_blank',
					'label' => __( 'Yes', 'fw' ),
				),
				'left-choice'  => array(
					'value' => '_self',
					'label' => __( 'No', 'fw' ),
				),
			),
			// 'order' => array(
			// 	'type'         => 'switch',
			// 	'label'        => 'Cho phép đặt mẫu',
			// 	'right-choice' => array(
			// 		'value' => 'yes',
			// 		'label' => __( 'Yes', 'fw' ),
			// 	),
			// 	'left-choice'  => array(
			// 		'value' => 'no',
			// 		'label' => __( 'No', 'fw' ),
			// 	),
			// ),
		)
	)
);

