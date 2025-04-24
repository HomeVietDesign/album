<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

//$image_sizes = get_intermediate_image_sizes();
$all_sizes = wp_get_registered_image_subsizes();
$medium_large = $all_sizes['medium_large'];
unset($all_sizes['medium_large']);
//debug_log($all_sizes);
$sizes = [];
foreach ($all_sizes as $key => $value) {
	//$size_name = ucfirst(preg_replace('/[-|_]+/', ' ', $key));
	$size_name = ucfirst(preg_replace('/[-|_]+/', ' ', $key));

	$sizes[$key] = $size_name.' - '.$value['width'].'x'.$value['height'];
	if($key=='large') {
		$size_name = 'Medium large';
		$sizes['medium_large'] = $size_name.' - '.$medium_large['width'].'x'.$medium_large['height'];
	}
}
$sizes['full'] = 'Ảnh gốc';

$options = array(
	'images' => array(
		'type' => 'multi-upload',
		'label' => 'Danh sách ảnh',
		'images_only' => true,
		'files_ext' => array( 'png', 'jpg', 'jpeg' ),
	),
	'size' => array(
		'type' => 'select',
		'label' => 'Kích thước hiển thị',
		'choices' => $sizes
	),
	'show_desktop' => array(
		'label' => 'Số cột trên desktop',
		'desc' => '',
		'value' => 'col-md-6',
		'choices' => [
			'col-md-12' => '1',
			'col-md-6' => '2',
			'col-md-4' => '3',
			'col-md-3' => '4',
			'col-md-2' => '6'
		],
		'type' => 'select',
	),
	'show_tablet' => array(
		'label' => 'Số cột trên tablet',
		'desc' => '',
		'value' => 'col-sm-6',
		'choices' => [
			'col-sm-12' => '1',
			'col-sm-6' => '2',
			'col-sm-4' => '3',
			'col-sm-3' => '4',
			'col-sm-2' => '6'
		],
		'type' => 'select',
	),
	'show_mobile' => array(
		'label' => 'Số cột trên mobile',
		'desc' => '',
		'value' => 'col-xs-12',
		'choices' => [
			'col-xs-12' => '1',
			'col-xs-6' => '2',
			'col-xs-4' => '3',
			'col-xs-3' => '4',
			'col-xs-2' => '6'
		],
		'type' => 'select',
	),
);
