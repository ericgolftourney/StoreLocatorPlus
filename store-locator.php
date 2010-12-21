<?php
/*
Plugin Name: Store Locator Plus
Plugin URI: http://www.cybersprocket.com/producs/store-locator-plus/
Description: Store Locator Plus is based on the popular Google Maps Store Locator with a few customizations we needed for our clients. Unfortunately the original author is on haitus, so we've had to create our ownupdate. Hopefully other WordPress users will find our additions useful. 
Version: 0.1
Author: Cyber Sprocket Labs
Author URI: http://www.cybersprocket.com
License: GPL3

=====================

	Copyright 2010  Cyber Sprocket Labs (info@cybersprocket.com)

        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 3 of the License, or
        (at your option) any later version.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

**/

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

