<?php
/****************************************************************************
 ** file: downloadcsv.php
 **
 ** Export CSV report data.
 ***************************************************************************/
 
//===========================================================================
// Supporting Functions
//=========================================================================== 

/**************************************
 ** function: array_to_CSV()
 **
 ** Return a CSV string from an array.
 **
 **/
function array_to_CSV($data)
{
    $outstream = fopen("php://temp", 'r+');
    fputcsv($outstream, $data, ',', '"');
    rewind($outstream);
    $csv = fgets($outstream);
    fclose($outstream);
    return $csv;
}
    
    
//===========================================================================
// Main Processing
//===========================================================================

// Database Connection
include("./core/database-info.php");

// CSV Header
header( 'Content-Description: File Transfer' );
header( 'Content-Disposition: attachment; filename=slplus_' . $_POST['filename'] . '.csv' );
header( 'Content-Type: application/csv;');
header( 'Pragma: no-cache');
header( 'Expires: 0');

// Run the query & output the data in a CSV
$thisDataset = $wpdb->get_results(stripslashes(htmlspecialchars_decode($_POST['query'],ENT_QUOTES)),ARRAY_N);
foreach ($thisDataset as $thisDatapoint) {    
    print array_to_CSV($thisDatapoint);
}
