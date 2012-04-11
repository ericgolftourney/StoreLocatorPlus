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



 
//-----------------------------------------------
// Setup the javascript variable we'll need later
//
print "
var sl_google_map_country='".SetMapCenter()."';
var slp_disablescrollwheel="        .((get_option(SLPLUS_PREFIX.'_disable_scrollwheel'      )==1)?'true':'false').";
var slp_disableinitialdirectory="   .((get_option(SLPLUS_PREFIX.'_disable_initialdirectory' )==1)?'true':'false').";
var slp_show_tags="                 .((get_option(SLPLUS_PREFIX.'_show_tags'                )==1)?'true':'false').";

// These controls have inverse logic
var slp_largemapcontrol3d=" .((get_option(SLPLUS_PREFIX.'_disable_largemapcontrol3d')==1)?'false':'true').";
var slp_scalecontrol="      .((get_option(SLPLUS_PREFIX.'_disable_scalecontrol'     )==1)?'false':'true').";
var slp_maptypecontrol="    .((get_option(SLPLUS_PREFIX.'_disable_maptypecontrol'   )==1)?'false':'true').";
";

//-----------------------------------------------------------
// FUNCTIONS
//-----------------------------------------------------------

/*-------------------------
 * SetMapCenter()
 *
 * Set the starting point for the center of the map.
 * Uses country by default.
 * Plus Pack v2.4+ allows for a custom address.
 */
function SetMapCenter() {
    global $slplus_plugin;
    $customAddress = get_option(SLPLUS_PREFIX.'_map_center');
    if (
        (preg_replace('/\W/','',$customAddress) != '') &&
        $slplus_plugin->license->packages['Plus Pack']->isenabled_after_forcing_recheck() &&
        ($slplus_plugin->license->packages['Plus Pack']->active_version >= 2004000) 
        ) {
        return str_replace(array("\r\n","\n","\r"),', ',esc_attr($customAddress));
    }
    return esc_attr(get_option('sl_google_map_country'));    
}
