<?php
/****************************************************************************
 ** file: add-locations.php
 **
 ** handles the add locations form
 ***************************************************************************/

global $wpdb;

print "<div class='wrap'><h2>".__("Add Locations", $text_domain)."</h2><br>";
initialize_variables();


//Inserting addresses by manual input
//
if ($_POST[sl_store] && $_GET[mode]!="pca") {
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
	$address="$_POST[sl_address], $_POST[sl_city], $_POST[sl_state] $_POST[sl_zip]";
	do_geocoding($address);
	print "<div class='updated fade'>".
            __("Successful Addition",$text_domain).
            ". $view_link</div> <!--meta http-equiv='refresh' content='0'-->"; 
}


//Importing addresses from an local or remote database
//
if ($_POST[remote] && trim($_POST[query])!="" || $_POST[finish_import]) {
	if (ereg(".*\..{2,}", $_POST[server])) {
		include($sl_upload_path."/addons/db-importer/remoteConnect.php");
	}
	else {
        include($sl_path."/localImport.php");
    }
	if ($_POST[finish_import]!="1") {exit();}
}

//Importing CSV file of addresses
//
$newfile="temp-file.csv"; 
$target_path="$root/";
$root=ABSPATH."wp-content/plugins/".dirname(plugin_basename(__FILE__));
if (move_uploaded_file($_FILES['csv_import']['tmp_name'], "$root/$newfile") && 
    file_exists($sl_upload_path."/addons/csv-xml-importer-exporter/csvImport.php")
    ) {
	include($sl_upload_path."/addons/csv-xml-importer-exporter/csvImport.php");
} 

//If adding via the Point, Click, Add map (accepting AJAX)
if ($_GET[mode]=="pca") {
	include($sl_upload_path."/addons/point-click-add/pcaImport.php");
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
//
add_locations_form();


/** function: add_locations_form
 ** 
 ** show the add locations form
 **
 **/
function add_locations_form() {
    ob_start();
    include(SLPLUS_PLUGINDIR.'/templates/add_locations.php');
    $content = ob_get_contents();
    ob_end_clean();
    print $content;
}


