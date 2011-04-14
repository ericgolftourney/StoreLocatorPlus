<?php
$possible_path = preg_replace('/\/wp-content\/.*/','',$_SERVER['SCRIPT_FILENAME']);

// Document Root Install
//
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-config.php')) {
    include($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
    
// Subdomain Install of WordPress
//
} else if (file_exists($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'].'/wp-config.php')) {
    include($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'].'/wp-config.php');

// A sub-directory
//
} else if (file_exists($possible_path.'/wp-config.php')) {
    include($possible_path.'/wp-config.php');

$possible_path = preg_replace('/\/wp-content\/.*/','',$_SERVER['SCRIPT_FILENAME']);

// Document Root Install
//
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-config.php')) {
    include($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
    
// Subdomain Install of WordPress
//
} else if (file_exists($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'].'/wp-config.php')) {
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

// Hopefully we are on the standard relative path
//
} else if (file_exists('../../../wp-config.php')) {
    include('../../../wp-config.php');    
    
} else if (file_exists('../../../../wp-config.php')) {
    include('../../../../wp-config.php');    
}
