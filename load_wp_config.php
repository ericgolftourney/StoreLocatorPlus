<?php


// Find the Wordpress Config File...
//

// Possible path is the root of this script name, 
// strip everything from wp-content down
$possible_path = preg_replace('/\/wp-content\/.*/','',$_SERVER['SCRIPT_FILENAME']);

$secure_path = '';
if (isset($_SERVER['DOCUMENT_ROOT'])) {
    $secure_path = dirname($_SERVER['DOCUMENT_ROOT']);
}


/*-----------------DEBUG */
if (basename($_SERVER['SCRIPT_FILENAME']) == 'load_wp_config.php') {
    error_reporting(E_ALL);
    header("Content-type: text/html");
    print "Possible Path: $possible_path<br/>
    Secure Path: $secure_path<br/>
    DOCROOT: " . $_SERVER['DOCUMENT_ROOT'] . "<br/>
    SCRIPT: " . $_SERVER['SCRIPT_FILENAME'] . "<br/>
    ";
    if (file_exists($secure_path.'/wp-config.php')) { print "Found $secure_path/wp-config.php<br/>"; }
    if (file_exists($secure_path.'/wp-settings.php')) { print "Found $secure_path/wp-settings.php<br/>"; }
}    
/*-----------------DEBUG */



// Document Root Install
//
if (isset($_SERVER['DOCUMENT_ROOT']) && file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-config.php')) {
    include($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');

// One Level Up and not part of another install
//
} else if (
    ($secure_path != '') 
    && file_exists($secure_path.'/wp-config.php')
    && !file_exists($secure_path.'/wp-settings.php')
    ) {
    define(ABSPATH,$_SERVER['DOCUMENT_ROOT'].'/');
    include($secure_path.'/wp-config.php');
    
// Subdomain Install of WordPress
//
} else if (isset($_SERVER['SUBDOMAIN_DOCUMENT_ROOT']) && file_exists($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'].'/wp-config.php')) {
    include($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'].'/wp-config.php');

// A sub-directory
//
} else if (file_exists($possible_path.'/wp-config.php')) {
    include($possible_path.'/wp-config.php');
    
// Hopefully we are on the standard relative path
//
} else if (file_exists('../../../wp-config.php')) {
    include('../../../wp-config.php');    
    
} else if (file_exists('../../../../wp-config.php')) {
    include('../../../../wp-config.php');    
}


