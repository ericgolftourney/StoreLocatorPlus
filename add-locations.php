<?php
/****************************************************************************
 ** file: add-locations.php
 **
 ** handles the add locations form
 ***************************************************************************/

global $wpdb, $sl_upload_path, $sl_path;

print "<div class='wrap'>
            <div id='icon-add-locations' class='icon32'><br/></div>
            <h2>".
            __('Add Locations', $text_domain).
            "<a href='/wp-admin/admin.php?page=$sl_dir/view-locations.php' class='button add-new-h2'>".
            __('Manage Locations',$text_domain). 
            "</a></h2>";


initialize_variables();


//Inserting addresses by manual input
//
$notpca = isset($_GET['mode']) ? ($_GET['mode']!="pca") : true;
if ( isset($_POST['sl_store']) && $_POST['sl_store'] && $notpca ) {
    $fieldList = '';
    $valueList = '';
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
            $_POST['sl_store'] ." " .
            __("Added Succesfully",$text_domain) . '.</div>';
}

	
$base=get_option('siteurl');

// Show the manual location entry form
execute_and_output_template('add_locations.php');
