<?php if (!defined('FW')) {
	die('Forbidden');
}

$options = array(
	'folder_cat' => [
		'type'  => 'multi-select',
		'population' => 'taxonomy',
		'source' => 'folder_cat',
		'limit' => 1,
		'label' => 'Thư mục ảnh',
	],
	'numbers' => [
		'type'  => 'number',
	    'value' => 20,
	    'label' => 'Số ảnh/trang',
	],
);
