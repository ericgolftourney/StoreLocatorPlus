<?php

/**
 * We need the generic WPCSL plugin class, since that is the
 * foundation of much of our plugin.  So here we make sure that it has
 * not already been loaded by another plugin that may also be
 * installed, and if not then we load it.
 */
if (class_exists('wpCSL_plugin__slplus') === false) {
    require_once(SLPLUS_PLUGINDIR.'WPCSL-generic/classes/CSL-plugin.php');
}

//// SETTINGS ////////////////////////////////////////////////////////

/**
 * This section defines the settings for the admin menu.
 */

$prefix = 'csl-slplus';

$slplus_plugin = new wpCSL_plugin__slplus(
    array(
        'use_obj_defaults'      => true,        
        'prefix'                => $prefix,
        'name'                  => 'Store Locator Plus',
        'url'                   => 'http://www.cybersprocket.com/products/store-locator-plus/',
        'paypal_button_id'      => '2D864VACHMK5A',
        'basefile'              => SLPLUS_BASENAME,
        'plugin_path'           => SLPLUS_PLUGINDIR,
        'plugin_url'            => SLPLUS_PLUGINURL,
        'cache_path'            => SLPLUS_PLUGINDIR . 'cache',
        'driver_type'           => 'none',
        'driver_args'           => array(
                'api_key'   => get_option($prefix.'-api_key'),
                )
    )
);

