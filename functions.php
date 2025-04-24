<?php
define('THEME_DIR', get_stylesheet_directory());
define('THEME_URI', get_stylesheet_directory_uri());

require_once THEME_DIR.'/vendor/autoload.php';
if(!function_exists('as_enqueue_async_action')) {
	require_once THEME_DIR.'/vendor/woocommerce/action-scheduler/action-scheduler.php';
}

require_once THEME_DIR.'/inc/trait-singleton.php';
require_once THEME_DIR.'/inc/class-theme.php';

\Album\Theme::get_instance();
