<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$image_sizes = get_intermediate_image_sizes();
$sizes = [];
foreach ($image_sizes as $key => $value) {
	$sizes[$value] = $value;
}

$options = array(
	'main-image' => array(
		'type' => 'group',
		'options' => array(
			'image' => array(
				'type'  => 'upload',
				'label' => __( 'Ảnh đại diện', 'fw' ),
				'images_only' => true,
				'files_ext' => array( 'png', 'jpg', 'jpeg' ),
			),
			'alt'  => array(
				'type'  => 'text',
				'label' => __( 'Alt', 'fw' ),
				'value' => ''
			),
			'size' => array(
				'type' => 'select',
				'label' => 'Kích thước',
				'choices' => $sizes
			),
		)
	),
	'images' => array(
		'type' => 'multi-upload',
		'label' => __('Thêm ảnh slide', 'fw'),
		'images_only' => true,
		'files_ext' => array( 'png', 'jpg', 'jpeg' ),
	),
	'order' => array(
		'type'         => 'switch',
		'label'        => 'Cho phép đặt mẫu',
		'right-choice' => array(
			'value' => 'yes',
			'label' => __( 'Yes', 'fw' ),
		),
		'left-choice'  => array(
			'value' => 'no',
			'label' => __( 'No', 'fw' ),
		),
	),
);
