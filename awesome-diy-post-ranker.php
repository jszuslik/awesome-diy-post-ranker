<?php
/*
	Plugin Name: Awesome DIY Post Ranker
	Plugin URI: https://joshuaszuslik.us
	Description:
	Version: 0.1
	Author: Joshua Szuslik
	Author URI: https://joshuaszuslik.us
	License: GPL2
*/
define('ADPR_TEXT_DOMAIN', 'awesome-diy-post-ranker');

function adpr_require_file( $path ) {
	if ( file_exists($path) ) {
		require $path;
	}
}
function pr($var){
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}
if ( function_exists('adpr_require_file') ) {
	$plugin_dir = plugin_dir_path(__FILE__);
	adpr_require_file( $plugin_dir . 'classes/AdprMetaBoxes.php' );
	adpr_require_file( $plugin_dir . 'helpers/AdprMetaBoxHelper.php' );
}