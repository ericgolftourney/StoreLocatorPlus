<?php
/****************************************************************************
 ** file: add-locations.php
 **
 ** handles the add locations form
 ***************************************************************************/

global $wpdb, $sl_upload_path, $sl_path;

print "<div class='wrap'><h2>".__("Add Locations", $text_domain)."</h2><br>";
initialize_variables();


//Inserting addresses by manual input
//
if ( isset($_POST['sl_store']) && $_POST['sl_store'] && ($_GET['mode']!="pca") ) {
	foreach ($_POST as $key=>$value) {
		if (ereg("sl_", $key)) {
			$fieldList.="$key,";
			$value=comma($value);
			$valueList.="\"".stripslashes($value)."\",";
		}
	}
	$fieldList=substr($fieldList, 0, strlen($fieldList)-1);
	$valueList=substr($valueList, 0, strlen($valueList)-1);
	$wpdb->query("INSERT into ". $wpdb->prefix . "store_locator ($fieldList) VALUES ($valueList)");
	$address    = $_POST['sl_address'].', '.
	              $_POST['sl_city'].', '.$_POST['sl_state'].' '.$_POST['sl_zip'];
	do_geocoding($address);
	print "<div class='updated fade'>".
            __("Successful Addition",$text_domain).
            ". $view_link</div> <!--meta http-equiv='refresh' content='0'-->"; 
}

	
$base=get_option('siteurl');

print <<<EOQ
<!--h2>Copy and Paste Addresses into Text Area:</h2>
<form  method=post>
<textarea rows='20' cols='100'></textarea><br>
<input type='submit'>
</form-->
EOQ;

// Show the manual location entry form
execute_and_output_template('add_locations.php');
