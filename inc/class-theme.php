<?php
namespace Album;

class Theme {

	use \Album\Singleton;

	private function __construct() {
	
		include_once THEME_DIR.'/inc/global-functions.php';
		include_once THEME_DIR.'/inc/unyson/class-unyson.php';
		include_once THEME_DIR.'/inc/admin/class-admin.php';

		if(class_exists('FileBird\\Plugin')) {
			require_once THEME_DIR.'/inc/filebird/class-filebird.php';
		}

		include_once THEME_DIR.'/inc/class-authentication.php';

		if(unyson_exists()) {

			include_once THEME_DIR.'/inc/class-common.php';
			include_once THEME_DIR.'/inc/class-template-tags.php';
			include_once THEME_DIR.'/inc/class-setup.php';
			include_once THEME_DIR.'/inc/class-assets.php';
			include_once THEME_DIR.'/inc/class-ajax.php';
			include_once THEME_DIR.'/inc/class-head.php';
			include_once THEME_DIR.'/inc/class-body.php';
			include_once THEME_DIR.'/inc/class-header.php';
			include_once THEME_DIR.'/inc/class-footer.php';

			// widgets
			include_once THEME_DIR.'/inc/class-widgets.php';


		}
	}

}

