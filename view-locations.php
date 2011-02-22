<?php
/****************************************************************************
 ** file: view-locations.php
 **
 ** Manage the view locations admin panel action.
 ***************************************************************************/
?>
<div class='wrap'>
<?php
$hidden="";
foreach($_GET as $key=>$val) {
	//hidden keys to keep same view after form submission
	if ($key!="q" && $key!="o" && $key!="d" && $key!="changeView" && $key!="start") {
		$hidden.="<input type='hidden' value='$val' name='$key'>\n"; 
	}
}
print "<form><table cellpadding='0px' cellspacing='0px' width='100%'><tr><td>
<h2>".__("Manage Locations", $text_domain)."</h2></td><td align='right'>".
"<h2 style='float:right; padding-right:0px; width:100%'>".
"<input value='".(isset($_GET['q'])?$_GET['q']:'')."' name='q'><input type='submit' value='".
__("Search Locations", $text_domain).
"' class='button-primary'></h2>$hidden</td></tr></table></form><br>";

initialize_variables();

$slak=$slplus_plugin->driver_args['api_key'];
if (!$slak) {
    print '<div class="wrap">';
	print 'Google API Key needs to be set to activate this feature.';
	print '</div>';
} else {

	// If delete link is clicked
	if (isset($_GET['delete']) && ($_GET['delete']!='')) {
		$wpdb->query("DELETE FROM ".$wpdb->prefix."store_locator ".
		    "WHERE sl_id='".$_GET['delete']."'");
	}

    // Edit, any form
    //
	if ($_POST                                                  && 
	    (isset($_GET['edit']) && $_GET['edit'])                 &&
	    (!isset($_POST['act']) || (isset($_POST['act']) && ($_POST['act']!="delete"))) 
	    ) {
		foreach ($_POST as $key=>$value) {
			if (ereg("\-$_GET[edit]", $key)) {
				$field_value_str.="sl_".ereg_replace("\-$_GET[edit]", "", $key)."='".
                    trim(comma($value))."', ";
                // strip off number at the end 
                $key=ereg_replace("\-$_GET[edit]", "", $key); 
				$_POST["$key"]=$value; 
			}
		}
		$field_value_str=substr($field_value_str, 0, strlen($field_value_str)-2);
		$edit=$_GET['edit']; extract($_POST);
		$the_address="$address $address2, $city, $state $zip";
		
		$old_address=$wpdb->get_results("SELECT * FROM ".
                        $wpdb->prefix."store_locator WHERE sl_id=$_GET[edit]", ARRAY_A);
		$wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET $field_value_str " .
                        "WHERE sl_id=$_GET[edit]");
		if ($the_address!=
            "$old_address[sl_address] $old_address[sl_address2], $old_address[sl_city], " .
            "$old_address[sl_state] $old_address[sl_zip]" || 
            ($old_address['sl_latitude']=="" || $old_address['sl_longitutde']=="")
        ) {
			do_geocoding($the_address,$_GET[edit]);
		}
		print "<script>location.replace('".ereg_replace("&edit=$_GET[edit]", "", 
                    $_SERVER['REQUEST_URI'])."');</script>";
	}
	
    //If post action is set
	if (isset($_POST['act'])) {

        // Delete Action	    
        if ($_POST['act']=="delete") {
            include("deleteLocations.php");
        }        
        
        // Tagging Action
        if (eregi("tag", $_POST['act'])) {
            //if bulk tagging is used
            include("tagLocations.php");
        }
        
        // Multi Action
        if (eregi("multi", $_POST['act'])) {
            //if bulk updating is used
            include($sl_upload_path."/addons/multiple-field-updater/multiLocationUpdate.php");
        }
        
        // Locations Per Page Action
        if ($_POST['act']=="locationsPerPage") {
            //If bulk delete is used
            update_option('sl_admin_locations_per_page', $_POST['sl_admin_locations_per_page']);
            extract($_POST);
        }
    }
    
	if (isset($_GET['changeView']) && ($_GET['changeView']==1)) {
		if (get_option('sl_location_table_view')=="Normal") {
			update_option('sl_location_table_view', 'Expanded');
			$tabViewText="Expanded";
		} else {
			update_option('sl_location_table_view', 'Normal');
			$tabViewText="Normal";
		}
		$_SERVER['REQUEST_URI']=ereg_replace("&changeView=1", "", $_SERVER['REQUEST_URI']);
		print "<script>location.replace('".$_SERVER['REQUEST_URI']."');</script>";
	}
	
	
	if (isset($_GET['changeUpdater']) && ($_GET['changeUpdater']==1)) {
		if (get_option('sl_location_updater_type')=="Tagging") {
			update_option('sl_location_updater_type', 'Multiple Fields');
			$updaterTypeText="Multiple Fields";
		} else {
			update_option('sl_location_updater_type', 'Tagging');
			$updaterTypeText="Tagging";
		}
		$_SERVER['REQUEST_URI']=ereg_replace("&changeUpdater=1", "", $_SERVER['REQUEST_URI']);
		print "<script>location.replace('".$_SERVER['REQUEST_URI']."');</script>";
	}
	
print "<form name='locationForm' method='post'>";
print "<table width=100%><tr><td width='33%'><b>".__("Current View", $text_domain).":</b>&nbsp;".get_option('sl_location_table_view')."&nbsp;(<a href='".ereg_replace("&changeView=1", "", $_SERVER['REQUEST_URI'])."&changeView=1'>".__("Change View", $text_domain)."</a>)</td>
<td width='33%' align='center'>
<nobr><b>".__("Locations Per Page", $text_domain).":</b> <select name='sl_admin_locations_per_page'>
<option value=''>".__("Choose", $text_domain)."</option>";

$opt_arr=array(10,25,50,100,200,300,400,500,1000,2000,4000,5000,10000);
foreach ($opt_arr as $value) {
	$selected=($sl_admin_locations_per_page==$value)? " selected " : "";
	print "<option value='$value' $selected>$value</option>";
}
print "</select><input type='button' value='".__("Change", $text_domain)."' class='button-primary' onclick=\"LF=document.forms['locationForm'];LF.act.value='locationsPerPage';LF.submit();\">
</nobr>
</td>
<td align=right width='33%'>";

if (file_exists($sl_upload_path."/addons/multiple-field-updater/multiLocationUpdate.php")) {
print "<b>".__("Updater", $text_domain).":</b>&nbsp;".get_option('sl_location_updater_type')."&nbsp;(<a href='".ereg_replace("&changeUpdater=1", "", $_SERVER['REQUEST_URI'])."&changeUpdater=1'>".__("Change Updater", $text_domain)."</a>)";
}

print "</td></tr></table><br>";
include("mgmt-buttons-links.php");

set_query_defaults();

//for search links
		$numMembers=$wpdb->get_results(
            "SELECT sl_id FROM " . $wpdb->prefix . "store_locator $where");
		$numMembers2=count($numMembers); 
		$start=(isset($_GET['start'])&&(trim($_GET['start'])!=''))?$_GET['start']:0;
        //edit this to determine how many locations to view per page of 'Manage Locations' page
		$num_per_page=$sl_admin_locations_per_page; 
		if ($numMembers2!=0) {include(SLPLUS_PLUGINDIR.'/search-links.php');}
//end of for search links

$opt = isset($_GET['o']) ? $_GET['o'] : '';
$dir = isset($_GET['d']) ? $_GET['d'] : '';
print "<br>
<table class='widefat' cellspacing=0>
<thead><tr >
<th colspan='1'><input type='checkbox' onclick='checkAll(this,document.forms[\"locationForm\"])' class='button'></th>
<th colspan='1'>".__("Actions", $text_domain)."</th>
<th><a href='".ereg_replace("&o=$opt&d=$dir", "", $_SERVER['REQUEST_URI'])."&o=sl_id&d=$d'>".__("ID", $text_domain)."</a></th>
<th><a href='".ereg_replace("&o=$opt&d=$dir", "", $_SERVER['REQUEST_URI'])."&o=sl_store&d=$d'>".__("Name", $text_domain)."</a></th>
<th><a href='".ereg_replace("&o=$opt&d=$dir", "", $_SERVER['REQUEST_URI'])."&o=sl_address&d=$d'>".__("Street", $text_domain)."</a></th>
<th><a href='".ereg_replace("&o=$opt&d=$dir", "", $_SERVER['REQUEST_URI'])."&o=sl_address2&d=$d'>".__("Street2", $text_domain)."</a></th>
<th><a href='".ereg_replace("&o=$opt&d=$dir", "", $_SERVER['REQUEST_URI'])."&o=sl_city&d=$d'>".__("City", $text_domain)."</a></th>
<th><a href='".ereg_replace("&o=$opt&d=$dir", "", $_SERVER['REQUEST_URI'])."&o=sl_state&d=$d'>".__("State", $text_domain)."</a></th>
<th><a href='".ereg_replace("&o=$opt&d=$dir", "", $_SERVER['REQUEST_URI'])."&o=sl_zip&d=$d'>".__("Zip", $text_domain)."</a></th>
<th><a href='".ereg_replace("&o=$opt&d=$dir", "", $_SERVER['REQUEST_URI'])."&o=sl_tags&d=$d'>".__("Tags", $text_domain)."</a></th>";

if (get_option('sl_location_table_view')!="Normal") {
    print "<th><a href='".ereg_replace("&o=$_GET[o]&d=$_GET[d]", "", $_SERVER['REQUEST_URI']).
            "&o=sl_description&d=$d'>".__("Description", $text_domain)."</a></th>".
        "<th><a href='".ereg_replace("&o=$_GET[o]&d=$_GET[d]", "", $_SERVER['REQUEST_URI']).
            "&o=sl_url&d=$d'>".__("URL", $text_domain)."</a></th>".
        "<th><a href='".ereg_replace("&o=$_GET[o]&d=$_GET[d]", "", $_SERVER['REQUEST_URI']).
            "&o=sl_hours&d=$d'>".__("Hours", $text_domain)."</th>".
        "<th><a href='".ereg_replace("&o=$_GET[o]&d=$_GET[d]", "", $_SERVER['REQUEST_URI']).
            "&o=sl_phone&d=$d'>".__("Phone", $text_domain)."</a></th>".
        "<th><a href='".ereg_replace("&o=$_GET[o]&d=$_GET[d]", "", $_SERVER['REQUEST_URI']).
            "&o=sl_image&d=$d'>".__("Image", $text_domain)."</a></th>";
}

print "<th>(Lat, Lon)</th></tr></thead>";

if ($locales=$wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 
        "store_locator  $where ORDER BY $o $d LIMIT $start,$num_per_page", ARRAY_A)) {
		
        $bgcol = '#eee';
		foreach ($locales as $value) {
		    $locID = $value['sl_id'];
			$bgcol=($bgcol=="#eee")?"#fff":"#eee";			
			$bgcol=($value['sl_latitude']=="" || $value['sl_longitude']=="")? "salmon" : $bgcol;			
			$value=array_map("trim",$value);
			
			if (isset($_GET['edit']) && ($locID==$_GET['edit'])) {
				print "<tr style='background-color:$bgcol'>";
	            $colspan=(get_option('sl_location_table_view')!="Normal")? 	16 : 11;	
				
                print "<td colspan='$colspan'><form name='manualAddForm' method=post>
                <a name='a".$locID."'></a>
                <table cellpadding='0' class='manual_update_table'>
                <!--thead><tr><td>".__("Type&nbsp;Address", $text_domain)."</td></tr></thead-->
                <tr>
                    <td valign='top'>";
                
                execute_and_output_template('edit_location_address.php');
                
                print "<br>
                        <nobr><input type='submit' value='".__("Update", $text_domain)."' class='button-primary'><input type='button' class='button' value='".__("Cancel", $text_domain)."' onclick='location.href=\"".ereg_replace("&edit=$_GET[edit]", "",$_SERVER['REQUEST_URI'])."\"'></nobr>
                    </td><td>
                        <b>".__("Additional Information", $text_domain)."</b><br>
                        <textarea name='description-$locID' rows='5' cols='17'>$value[sl_description]</textarea>&nbsp;<small>".__("Description", $text_domain)."</small><br>
                        <input name='tags-$locID' value='$value[sl_tags]'>&nbsp;<small>".__("Tags (seperate with commas)", $text_domain)."</small><br>		
                        <input name='url-$locID' value='$value[sl_url]'>&nbsp;<small>".__("URL", $text_domain)."</small><br>
                        <input name='hours-$locID' value='$value[sl_hours]'>&nbsp;<small>".__("Hours", $text_domain)."</small><br>
                        <input name='phone-$locID' value='$value[sl_phone]'>&nbsp;<small>".__("Phone", $text_domain)."</small><br>
                        <input name='image-$locID' value='$value[sl_image]'>&nbsp;<small>".__("Image URL (shown with location)", $text_domain)."</small><br><br>
                    </td>
                        </tr>
                    </table>
                </form></td>
                </tr>";
			} else {
                $value['sl_url']=(!url_test($value['sl_url']) && trim($value['sl_url'])!="")? 
                    "http://".$value['sl_url'] : 
                    $value['sl_url'] ;
                $value['sl_url']=($value['sl_url']!="")? 
                    "<a href='$value[sl_url]' target='blank'>".__("View", $text_domain)."</a>" : 
                    "" ;
                $value['sl_image']=($value['sl_image']!="")? 
                    "<a href='$value[sl_image]' target='blank'>".__("View", $text_domain)."</a>" : 
                    "" ;
                $value['sl_description']=($value['sl_description']!="")? 
                    "<a onclick='alert(\"".comma($value['sl_description'])."\")' href='#'>".
                    __("View", $text_domain)."</a>" : 
                    "" ;
                
                print "<tr style='background-color:$bgcol'>
                <th><input type='checkbox' name='sl_id[]' value='$locID'></th>
                <th><a href='".ereg_replace("&edit=".(isset($_GET['edit'])?$_GET['edit']:''), "",$_SERVER['REQUEST_URI']).
                "&edit=" . $locID ."#a$locID'>".__("Edit", $text_domain).
                "</a>&nbsp;|&nbsp;<a href='".$_SERVER['REQUEST_URI']."&delete=$locID' " .
                "onclick=\"confirmClick('Sure?', this.href); return false;\">".
                __("Delete", $text_domain)."</a></th>
                <th> $locID </th>
                <td> $value[sl_store] </td>
                <td>$value[sl_address]</td>
                <td>$value[sl_address2]</td>
                <td>$value[sl_city]</td>
                <td>$value[sl_state]</td>
                <td>$value[sl_zip]</td>
                <td>$value[sl_tags]</td>";
                
                if (get_option('sl_location_table_view')!="Normal") {
                    print "<td>$value[sl_description]</td>
                    <td>$value[sl_url]</td>
                    <td>$value[sl_hours]</td>
                    <td>$value[sl_phone]</td>
                    <td>$value[sl_image]</td>";
                }
                
                print "<td>(".$value['sl_latitude'].",&nbsp;".$value['sl_longitude'].")</td> </tr>";
			}
		}
} else {
		$notice=($_GET[q]!="")? 
                __("No Locations Showing for this Search of ", $text_domain).
                    "<b>\"$_GET[q]\"</b>. $view_link" : 
                __("No Locations Currently in Database", $text_domain);
		print "<tr><td colspan='5'>$notice | <a href='admin.php?page=$sl_dir/add-locations.php'>".
            __("Add Locations", $text_domain)."</a></td></tr>";
	}
	print "</table>
	<input name='act' type='hidden'><br>";
if ($numMembers2!=0) {include(SLPLUS_PLUGINDIR.'/search-links.php');}

print "</form>";
	
}
	
?>
</div>
