<?php
/******************************************************************************
 * File: store-locator-js.php 
 * 
 * Wires the WordPress options from the database into javascript variables that
 * we will use to change how the javascript elements work, such as the Google
 * Maps parameters.
 *
 ******************************************************************************/

error_reporting(0);
header("Content-type: text/javascript");

// Make the connection to the WordPress environment
//
if (!file_exists('../load_wp_config.php')) {
    echo "alert('SLPLUS: Cannot load WordPress configuration file.');";
    return;
}
include('../load_wp_config.php');

if (!function_exists('get_option')) {
    echo "alert('Unable to load WordPress configuration. [Store Locator Plus]');";
    return;
}

// Setup our base variables needed to make the plugin work
//
include("../variables.sl.php");

