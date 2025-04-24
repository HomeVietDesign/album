<?php if (!defined('FW')) {
	die('Forbidden');
}
/*
$folders = [];

if(class_exists('FileBird\Classes\Tree')) {
	$_folders = FileBird\Classes\Tree::getFolders(null,true,0,true);
	foreach ($_folders as $folder) {
		$folders['f'.$folder['id']] = $folder['text'];
	}
}
*/
$options = array(
	'folder_cat' => [
		'type'  => 'multi-select',
	    //'value' => [],
	    'label' => 'Thư mục',
	    'population' => 'taxonomy',
	    'source' => 'folder_cat',
	    'limit' => 1,
	],
	
);
