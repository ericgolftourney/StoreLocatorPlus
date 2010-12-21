<?php
/*
Plugin Name: Store Locator Plus
Plugin URI: http://www.cybersprocket.com/producs/store-locator-plus/
Description: Store Locator Plus is based on the popular Google Maps Store Locator with a few customizations we needed for our clients. Unfortunately the original author is on haitus, so we've had to create our ownupdate. Hopefully other WordPress users will find our additions useful. 
Version: 0.1
Author: Cyber Sprocket Labs
Author URI: http://www.cybersprocket.com
*/

$sl_version="1.2.39.3";
$sl_db_version=1.2;
include_once("variables.sl.php");
include_once("copyfolder.lib.php");
include_once("functions.sl.php");
include_once("via-latest.php");

register_activation_hook( __FILE__, 'install_table');

add_action('wp_head', 'head_scripts');
add_action('admin_menu', 'sl_add_options_page');
add_action('admin_print_scripts', 'add_admin_javascript');
add_action('admin_print_styles','add_admin_stylesheet');

add_filter('the_content', 'ajax_map', 7);

load_plugin_textdomain($text_domain, "/wp-content/uploads/sl-uploads/languages/");

add_filter('option_update_plugins', 'plugin_prevent_upgrade');
add_filter('transient_update_plugins', 'plugin_prevent_upgrade');

function plugin_prevent_upgrade($opt) {
	global $update_class;
	$plugin = plugin_basename(__FILE__);
	if ( $opt && isset($opt->response[$plugin]) ) {
		$update_class="update-message";
	}
	return $opt;
}

