<?php
/****************************************************************************
 ** file: downloadcsv.php
 **
 ** Export CSV report data.
 ***************************************************************************/
 
global $slplus_plugin, $wpdb;

header( 'Content-Description: File Transfer' );
header( 'Content-Disposition: attachment; filename=slplus_' . $_POST['filename'] . '.csv' );
header( 'Content-Type: application/csv;');
header( 'Pragma: no-cache');
header( 'Expires: 0');



