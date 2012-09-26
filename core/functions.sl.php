<?php
/****************************************************************************
 ** file: functions.sl.php
 **
 ** The collection of main core functions for Store Locator Plus
 ***************************************************************************/

global $sl_dir, $sl_base, $sl_path, $sl_upload_path, $sl_upload_base;

$text_domain=SLPLUS_PREFIX;
$prefix = SLPLUS_PREFIX;

$sl_dir =SLPLUS_PLUGINDIR;  //plugin absolute server directory name
$sl_base=SLPLUS_PLUGINURL;  //URL to plugin directory
$sl_path=ABSPATH.'wp-content/plugins/'.$sl_dir; //absolute server path to plugin directory
$sl_upload_path=ABSPATH.'wp-content/uploads/sl-uploads'; //absolute server path to store locator uploads directory

$map_character_encoding=(get_option('sl_map_character_encoding')!="")? 
    "&amp;oe=".get_option('sl_map_character_encoding') : 
    "";
$sl_upload_base=get_option('siteurl')."/wp-content/uploads/sl-uploads"; //URL to store locator uploads directory
 
 

/* -----------------*/
function move_upload_directories() {
	global $sl_upload_path, $sl_path;
	
	if (!is_dir(ABSPATH . "wp-content/uploads")) {
		mkdir(ABSPATH . "wp-content/uploads", 0755);
	}
	if (!is_dir($sl_upload_path)) {
		mkdir($sl_upload_path, 0755);
	}
	if (!is_dir($sl_upload_path . "/custom-icons")) {
		mkdir($sl_upload_path . "/custom-icons", 0755);
	}	
	if (is_dir($sl_path . "/languages") && !is_dir($sl_upload_path . "/languages")) {
		csl_copyr($sl_path . "/languages", $sl_upload_path . "/languages");
	}
	if (is_dir($sl_path . "/images") && !is_dir($sl_upload_path . "/images")) {
		csl_copyr($sl_path . "/images", $sl_upload_path . "/images");
	}
}

/*-----------------*/

function initialize_variables() {
    global $sl_height, $sl_width, $sl_width_units, $sl_height_units;
    global $cl_icon, $cl_icon2, $sl_google_map_domain, $sl_google_map_country, $sl_theme, $sl_base, $sl_upload_base, $sl_location_table_view;
    global $sl_search_label, $sl_zoom_level, $sl_zoom_tweak, $sl_use_city_search, $sl_use_name_search, $sl_default_map;
    global $sl_radius_label, $sl_website_label, $sl_num_initial_displayed, $sl_load_locations_default;
    global $sl_distance_unit, $sl_map_overview_control, $sl_admin_locations_per_page, $sl_instruction_message;
    global $sl_map_character_encoding, $sl_use_country_search, $slplus_show_state_pd, $slplus_name_label;
    
    $sl_map_character_encoding=get_option('sl_map_character_encoding');
    if (empty($sl_map_character_encoding)) {
        $sl_map_character_encoding="";
        add_option('sl_map_character_encoding', $sl_map_character_encoding);
        }
    $sl_instruction_message=get_option('sl_instruction_message');
    if (empty($sl_instruction_message)) {
        $sl_instruction_message="Enter Your Address or Zip Code Above.";
        add_option('sl_instruction_message', $sl_instruction_message);
        }
    $sl_admin_locations_per_page=get_option('sl_admin_locations_per_page');
    if (empty($sl_admin_locations_per_page)) {
        $sl_admin_locations_per_page="100";
        add_option('sl_admin_locations_per_page', $sl_admin_locations_per_page);
        }
    $sl_map_overview_control=get_option('sl_map_overview_control');
    if (empty($sl_map_overview_control)) {
        $sl_map_overview_control="0";
        add_option('sl_map_overview_control', $sl_map_overview_control);
        }
    $sl_distance_unit=get_option('sl_distance_unit');
    if (empty($sl_distance_unit)) {
        $sl_distance_unit="miles";
        add_option('sl_distance_unit', $sl_distance_unit);
        }
    $sl_load_locations_default=get_option('sl_load_locations_default');
    if (empty($sl_load_locations_default)) {
        $sl_load_locations_default="1";
        add_option('sl_load_locations_default', $sl_load_locations_default);
        }
    $sl_num_initial_displayed=get_option('sl_num_initial_displayed');
    if (empty($sl_num_initial_displayed)) {
        $sl_num_initial_displayed="25";
        add_option('sl_num_initial_displayed', $sl_num_initial_displayed);
        }
    $sl_website_label=get_option('sl_website_label');
    if (empty($sl_website_label)) {
        $sl_website_label="Website";
        add_option('sl_website_label', $sl_website_label);
        }
    $sl_radius_label=get_option('sl_radius_label');
    if (empty($sl_radius_label)) {
        $sl_radius_label="Radius";
        add_option('sl_radius_label', $sl_radius_label);
        }
    $sl_map_type=get_option('sl_map_type');
    if (isset($sl_map_type)) {
        $sl_map_type='roadmap';
        add_option('sl_map_type', $sl_map_type);
        }
    $sl_remove_credits=get_option('sl_remove_credits');
    if (empty($sl_remove_credits)) {
        $sl_remove_credits="0";
        add_option('sl_remove_credits', $sl_remove_credits);
        }
    $sl_use_name_search=get_option('sl_use_name_search');
    if (empty($sl_use_name_search)) {
        $sl_use_name_search="0";
        add_option('sl_use_name_search', $sl_use_name_search);
        }
    $sl_use_city_search=get_option('sl_use_city_search');
    if (empty($sl_use_city_search)) {
        $sl_use_city_search="1";
        add_option('sl_use_city_search', $sl_use_city_search);
        }
    $sl_use_country_search=get_option('sl_use_country_search');
    if (empty($sl_use_country_search)) {
        $sl_use_country_search="1";
        add_option('sl_use_country_search', $sl_use_country_search);
        }
    $slplus_show_state_pd=get_option('slplus_show_state_pd');
    if (empty($slplus_show_state_pd)) {
        $slplus_show_state_pd="1";
        add_option('slplus_show_state_pd', $slplus_show_state_pd);
        }
    $sl_zoom_level=get_option('sl_zoom_level');
    if (empty($sl_zoom_level)) {
        $sl_zoom_level="4";
        add_option('sl_zoom_level', $sl_zoom_level);
        }
    $sl_zoom_tweak=get_option('sl_zoom_tweak');
    if (empty($sl_zoom_tweak)) {
        $sl_zoom_tweak="1";
        add_option('sl_zoom_tweak', $sl_zoom_tweak);
        }
    $sl_search_label=get_option('sl_search_label');
    if (empty($sl_search_label)) {
        $sl_search_label="Address";
        add_option('sl_search_label', $sl_search_label);
        }
	if (empty($slplus_name_label)) {
		$$slplus_name_label = "Store to search for";
		add_option('sl_name_label', $slplus_name_label);
	}
    $sl_location_table_view=get_option('sl_location_table_view');
    if (empty($sl_location_table_view)) {
        $sl_location_table_view="Normal";
        add_option('sl_location_table_view', $sl_location_table_view);
        }
    $sl_theme=get_option('sl_map_theme');
    if (empty($sl_theme)) {
        $sl_theme="";
        add_option('sl_map_theme', $sl_theme);
        }
    $sl_google_map_country=get_option('sl_google_map_country');
    if (empty($sl_google_map_country)) {
        $sl_google_map_country="United States";
        add_option('sl_google_map_country', $sl_google_map_country);
    }
    $sl_google_map_domain=get_option('sl_google_map_domain');
    if (empty($sl_google_map_domain)) {
        $sl_google_map_domain="maps.google.com";
        add_option('sl_google_map_domain', $sl_google_map_domain);
    }
    $cl_icon2=get_option('sl_map_end_icon');
    if (empty($cl_icon2)) {
        add_option('sl_map_end_icon', SLPLUS_COREURL . 'images/icons/marker.png');
        $cl_icon2=get_option('sl_map_end_icon');
    }
    $cl_icon=get_option('sl_map_home_icon');
    if (empty($cl_icon)) {
        add_option('sl_map_home_icon', SLPLUS_COREURL . 'images/icons/arrow.png');
        $cl_icon=get_option('sl_map_home_icon');
    }
    $sl_height=get_option('sl_map_height');
    if (empty($sl_height)) {
        add_option('sl_map_height', '350');
        $sl_height=get_option('sl_map_height');
        }
    
    $sl_height_units=get_option('sl_map_height_units');
    if (empty($sl_height_units)) {
        add_option('sl_map_height_units', "px");
        $sl_height_units=get_option('sl_map_height_units');
        }	
    
    $sl_width=get_option('sl_map_width');
    if (empty($sl_width)) {
        add_option('sl_map_width', "100");
        $sl_width=get_option('sl_map_width');
        }
    
    $sl_width_units=get_option('sl_map_width_units');
    if (empty($sl_width_units)) {
        add_option('sl_map_width_units', "%");
        $sl_width_units=get_option('sl_map_width_units');
        }	
}




/*----------------------------*/
function do_geocoding($address,$sl_id='') {    
    global $wpdb, $slplus_plugin;    
    
    $delay = 0;    
    $base_url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false";
    
    // Loop through for X retries
    //
    $iterations = get_option(SLPLUS_PREFIX.'-goecode_retries');
    if ($iterations <= 0) { $iterations = 1; }
    while($iterations){
    	$iterations--;     
    
        // Iterate through the rows, geocoding each address
        $request_url = $base_url . "&address=" . urlencode($address);
        $errorMessage = '';
        

        // Use HTTP Handler (WP_HTTP) first...
        //
        if (isset($slplus_plugin->http_handler)) { 
            $result = $slplus_plugin->http_handler->request( 
                            $request_url, 
                            array('timeout' => 3) 
                            ); 
            if ($slplus_plugin->http_result_is_ok($result) ) {
                $raw_json = $result['body'];
            }
            
        // Then Curl...
        //
        } elseif (extension_loaded("curl") && function_exists("curl_init")) {
                $cURL = curl_init();
                curl_setopt($cURL, CURLOPT_URL, $request_url);
                curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
                $raw_json = curl_exec($cURL);
                curl_close($cURL);

        // Lastly file_get_contents
        //
        } else {
             $raw_json = file_get_contents($request_url);
        }

        // If raw_json exists, parse it
        //
        if (isset($raw_json)) {
            $json = json_decode($raw_json);
            $status = $json->{'status'};
            
        // no raw json
        //
        } else {
            $json = '';
            $status = '';
        }
        
        // Geocode completed successfully
        //
        if (strcmp($status, "OK") == 0) {
            $iterations = 0;      // Break out of retry loop if we are OK
            $delay = 0;
            
            // successful geocode
            $geocode_pending = false;
            $lat = $json->results[0]->geometry->location->lat;
            $lng = $json->results[0]->geometry->location->lng;
            // Update newly inserted address
            //
            if ($sl_id=='') {
                $query = sprintf("UPDATE " . $wpdb->prefix ."store_locator " .
                       "SET sl_latitude = '%s', sl_longitude = '%s' " .
                       "WHERE sl_id = LAST_INSERT_ID()".
                       " LIMIT 1;", 
                       mysql_real_escape_string($lat), 
                       mysql_real_escape_string($lng)
                       );
            }
            // Update an existing address
            //
            else {
                $query = sprintf("UPDATE " . $wpdb->prefix ."store_locator SET sl_latitude = '%s', sl_longitude = '%s' WHERE sl_id = $sl_id LIMIT 1;", mysql_real_escape_string($lat), mysql_real_escape_string($lng));
            }
            
            // Run insert/update
            //
            $update_result = $wpdb->query($query);
            if ($update_result == 0) {
                $theDBError = htmlspecialchars(mysql_error($wpdb->dbh),ENT_QUOTES);
                $errorMessage .= __("Could not add/update address.  ", SLPLUS_PREFIX);
                if ($theDBError != '') {
                    $errorMessage .= sprintf(
                                            __("Error: %s.", SLPLUS_PREFIX),
                                            $theDBError
                                            );
                } elseif ($update_result === 0) {
                    $errorMessage .=  __("It appears the data did not change.", SLPLUS_PREFIX);
                } else {
                    $errorMessage .=  __("No error logged.", SLPLUS_PREFIX);
                    $errorMessage .= "<br/>\n" . __('Query: ', SLPLUS_PREFIX);
                    $errorMessage .= print_r($wpdb->last_query,true);
                    $errorMessage .= "<br/>\n" . "Results: " . gettype($update_result) . ' '. $update_result;
                }

            }

        // Geocoding done too quickly
        //
        } else if (strcmp($status, "OVER_QUERY_LIMIT") == 0) {
            
          // No iterations left, tell user of failure
          //
	      if(!$iterations){
            $errorMessage .= sprintf(__("Address %s <font color=red>failed to geocode</font>. ", SLPLUS_PREFIX),$address);
            $errorMessage .= sprintf(__("Received status %s.", SLPLUS_PREFIX),$status)."\n<br>";
	      }                       
          $delay += 100000;

        // Invalid address
        //
        } else if (strcmp($status, 'ZERO_RESULTS') == 0) {
	    	$iterations = 0; 
	    	$errorMessage .= sprintf(__("Address %s <font color=red>failed to geocode</font>. ", SLPLUS_PREFIX),$address);
	      	$errorMessage .= sprintf(__("Unknown Address! Received status %s.", SLPLUS_PREFIX),$status)."\n<br>";
          
        // Could Not Geocode
        //
        } else {
            $geocode_pending = false;
            echo sprintf(__("Address %s <font color=red>failed to geocode</font>. ", SLPLUS_PREFIX),$address);
            if ($status != '') {
                $errorMessage .= sprintf(__("Received data %s.", SLPLUS_PREFIX),'<pre>'.print_r($json,true).'</pre>')."\n";
            } else {
                $errorMessage .= sprintf(__("Reqeust sent to %s.", SLPLUS_PREFIX),$request_url)."\n<br>";
                $errorMessage .= sprintf(__("Received status %s.", SLPLUS_PREFIX),$status)."\n<br>";
            }
        }

        // Show Error Messages
        //
        if ($errorMessage != '') {
            print '<div class="geocode_error">' .
                    $errorMessage .
                    '</div>';
        }

        usleep($delay);
    }
}    


/***********************************
 ** Run install/update activation routines
 **
 ** [LE/PLUS]
 **/

function activate_slplus() {
    global $slplus_plugin;
    
   
    // Data Updates
    //
    global $sl_db_version, $sl_installed_ver;
	$sl_db_version='3.1';     //***** CHANGE THIS ON EVERY STRUCT CHANGE
    $sl_installed_ver = get_option( SLPLUS_PREFIX."-db_version" );

	install_main_table();
	if (function_exists('install_reporting_tables')) {
	    install_reporting_tables();
    }
    
    // Update the version
    //
    if ($sl_installed_ver == '') {
        add_option(SLPLUS_PREFIX."-db_version", $sl_db_version);
    } else {
        
        // Change Pro Pack license info to new SKU
        //
        if (get_option(SLPLUS_PREFIX.'-SLPLUS-PRO-lk','') == '') {
            update_option(SLPLUS_PREFIX.'-SLPLUS-PRO-lk',get_option(SLPLUS_PREFIX.'-SLPLUS-lk',''));
            update_option(SLPLUS_PREFIX.'-SLPLUS-PRO-isenabled',get_option(SLPLUS_PREFIX.'-SLPLUS-isenabled',''));
        }

        // Change Pages license info to new SKU
        //
        if (get_option(SLPLUS_PREFIX.'-SLPLUS-PAGES-lk','') == '') {
            update_option(SLPLUS_PREFIX.'-SLPLUS-PAGES-isenabled',get_option(SLPLUS_PREFIX.'-SLP-PAGES-isenabled',''));
        }

        update_option(SLPLUS_PREFIX."-db_version", $sl_db_version);
    }
    

    // Roles
    //
    if (function_exists('add_slplus_roles_and_caps')) {
        add_slplus_roles_and_caps();
    }      
    
    // Themes Cleaning
    //
    update_option($slplus_plugin->prefix.'-theme_lastupdated','2006-10-05');

	move_upload_directories();
}


/***********************************
 ** function: install_main_table
 **
 ** Install/update the main locations table.
 **
 **/
function install_main_table() {
	global $wpdb, $sl_installed_ver;
    
	
	//*****
	//***** CHANGE sl_db_version IN activate_slplus() 
	//***** ANYTIME YOU CHANGE THIS STRUCTURE
	//*****	
	$charset_collate = '';
    if ( ! empty($wpdb->charset) )
        $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    if ( ! empty($wpdb->collate) )
        $charset_collate .= " COLLATE $wpdb->collate";	
	$table_name = $wpdb->prefix . "store_locator";
	$sql = "CREATE TABLE $table_name (
			sl_id mediumint(8) unsigned NOT NULL auto_increment,
			sl_store varchar(255) NULL,
			sl_address varchar(255) NULL,
			sl_address2 varchar(255) NULL,
			sl_city varchar(255) NULL,
			sl_state varchar(255) NULL,
			sl_zip varchar(255) NULL,
			sl_country varchar(255) NULL,
			sl_latitude varchar(255) NULL,
			sl_longitude varchar(255) NULL,
			sl_tags mediumtext NULL,
			sl_description text NULL,
			sl_email varchar(255) NULL,
			sl_url varchar(255) NULL,
			sl_hours varchar(255) NULL,
			sl_phone varchar(255) NULL,
			sl_fax varchar(255) NULL,
			sl_image varchar(255) NULL,
			sl_private varchar(1) NULL,
			sl_neat_title varchar(255) NULL,
			sl_linked_postid int NULL,
			sl_pages_url varchar(255) NULL,
			sl_lastupdated  timestamp NOT NULL default current_timestamp,			
			PRIMARY KEY  (sl_id),
			INDEX (sl_store),
			INDEX (sl_longitude),
			INDEX (sl_latitude)
			) 
			$charset_collate
			";
		
    // If we updated an existing DB, do some mods to the data
    //
    if (slplus_dbupdater($sql,$table_name) === 'updated') {
        
        // We are upgrading from something less than 2.0
        //
	    if (floatval($sl_installed_ver) < 2.0) {
            dbDelta("UPDATE $table_name SET sl_lastupdated=current_timestamp " . 
                "WHERE sl_lastupdated < '2011-06-01'"
                );
        }   
	    if (floatval($sl_installed_ver) < 2.2) {
            dbDelta("ALTER $table_name MODIFY sl_description text ");
        }
    }         
	
	//set up google maps v3
	if (floatval($sl_installed_ver) < 3.0) {
		$old_option = get_option('sl_map_type');
		$new_option = 'roadmap';
		switch ($old_option) {
			case 'G_NORMAL_MAP':
				$new_option = 'roadmap';
				break;
			case 'G_SATELLITE_MAP':
				$new_option = 'satellite';
				break;
			case 'G_HYBRID_MAP':
				$new_option = 'hybrid';
				break;
			case 'G_PHYSICAL_MAP':
				$new_option = 'terrain';
				break;
			default:
				$new_option = 'roadmap';
				break;
		}
		
		update_option('sl_map_type', $new_option);
	}
}

/***********************************
 ** function: slplus_dbupdater
 ** 
 ** Update the data structures on new db versions.
 **
 **/ 
function slplus_dbupdater($sql,$table_name) {
    global $wpdb, $sl_db_version, $sl_installed_ver;
        
    // New installation
    //
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		return 'new';
		
    // Installation upgrade
    //
	} else {        
        if( $sl_installed_ver != $sl_db_version ) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            return 'updated';    
        }
    }   
}
/**************************************
 ** function: store_locator_shortcode
 **
 ** Process the store locator shortcode.
 **
 **/
 function store_locator_shortcode($attributes, $content = null) {
    // Variables this function uses and passes to the template
    // we need a better way to pass vars to the template parser so we don't
    // carry around the weight of these global definitions.
    // the other option is to unset($GLOBAL['<varname>']) at then end of this    
    // function call.
    //
    // Let's start using a SINGLE named array called "fnvars" to pass along anything
    // we want.
    //
    global  $sl_dir, $sl_base, $sl_upload_base, $sl_path, $sl_upload_path, $text_domain, $wpdb,
	    $slplus_plugin, $prefix,	        
	    $sl_search_label, $sl_width, $sl_height, $sl_width_units, $sl_height_units, $sl_hide,
	    $sl_radius, $sl_radius_label, $r_options, $button_style,
	    $sl_instruction_message, $cs_options, $slplus_name_label,
	    $sl_country_options, $slplus_state_options, $fnvars;	 	    
    $fnvars = array();

    //----------------------
    // Attribute Processing
    //
    if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {
        $slplus_plugin->shortcode_was_rendered = true;
        slplus_shortcode_atts($attributes);
    }
                   
    $sl_height         = get_option('sl_map_height','500');    
    $sl_height_units   = get_option('sl_map_height_units','px');    
    $sl_search_label   = get_option('sl_search_label',__('Address',SLPLUS_PREFIX));
    $unit_display   = get_option('sl_distance_unit','mi');    
    $sl_width          = get_option('sl_map_width','100');        
    $sl_width_units    = get_option('sl_map_width_units','%');
	$slplus_name_label = get_option('sl_name_label');
    $r_array        = explode(",",get_option('sl_map_radii','1,5,10,(25),50,100,200,500'));
    
    $sl_instruction_message = get_option('sl_instruction_message',__('Enter Your Address or Zip Code Above.',SLPLUS_PREFIX));
    
    
    $r_options      =(isset($r_options)         ?$r_options      :'');
    $cs_options     =(isset($cs_options)        ?$cs_options     :'');
    $sl_country_options=(isset($sl_country_options)   ?$sl_country_options:'');
    $slplus_state_options=(isset($slplus_state_options)   ?$slplus_state_options:'');

    foreach ($r_array as $sl_value) {
        $s=(ereg("\(.*\)", $sl_value))? " selected='selected' " : "" ;
        
        // Hiding Radius?
        if (get_option(SLPLUS_PREFIX.'_hide_radius_selections') == 1) {
            if ($s == " selected='selected' ") {
                $sl_value=ereg_replace("[^0-9]", "", $sl_value);
                $r_options = "<input type='hidden' id='radiusSelect' name='radiusSelect' value='$sl_value'>";
            }
            
        // Not hiding radius, build pulldown.
        } else {
            $sl_value=ereg_replace("[^0-9]", "", $sl_value);
            $r_options.="<option value='$sl_value' $s>$sl_value $unit_display</option>";
        }
    }
        
    //-------------------
    // Show City Search option is checked
    // setup the pulldown list
    //
    if (get_option('sl_use_city_search')==1) {
        $cs_array=$wpdb->get_results(
            "SELECT CONCAT(TRIM(sl_city), ', ', TRIM(sl_state)) as city_state " .
                "FROM ".$wpdb->prefix."store_locator " .
                "WHERE sl_city<>'' AND sl_state<>'' AND sl_latitude<>'' " .
                    "AND sl_longitude<>'' " .
                "GROUP BY city_state " .
                "ORDER BY city_state ASC", 
            ARRAY_A);
    
        if ($cs_array) {
            foreach($cs_array as $sl_value) {
        $cs_options.="<option value='$sl_value[city_state]'>$sl_value[city_state]</option>";
            }
        }
    }

    //----------------------
    // Create Country Pulldown
    //    
    if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {                    
        $sl_country_options = slplus_create_country_pd();    
        $slplus_state_options = slplus_create_state_pd();
    } else {
        $sl_country_options = '';    
        $slplus_state_options = '';
    }
        
    $sl_theme_base=$sl_upload_base."/images";
    $sl_theme_path=$sl_upload_path."/images";
    if (!file_exists($sl_theme_path."/search_button.png")) {
        $sl_theme_base=$sl_base."/images";
        $sl_theme_path=$sl_path."/images";
    }
    $sub_img=$sl_theme_base."/search_button.png";
    $mousedown=(file_exists($sl_theme_path."/search_button_down.png"))? 
        "onmousedown=\"this.src='$sl_theme_base/search_button_down.png'\" onmouseup=\"this.src='$sl_theme_base/search_button.png'\"" : 
        "";
    $mouseover=(file_exists($sl_theme_path."/search_button_over.png"))? 
        "onmouseover=\"this.src='$sl_theme_base/search_button_over.png'\" onmouseout=\"this.src='$sl_theme_base/search_button.png'\"" : 
        "";
    $button_style=(file_exists($sl_theme_path."/search_button.png"))? 
        "type='image' src='$sub_img' $mousedown $mouseover" : 
        "type='submit'";
    $sl_hide=(get_option('sl_remove_credits')==1)? 
        "style='display:none;'" : 
        "";

    $columns = 1;
    $columns += (get_option('sl_use_city_search')!=1) ? 1 : 0;
    $columns += (get_option('sl_use_country_search')!=1) ? 1 : 0; 	    
    $columns += (get_option('slplus_show_state_pd')!=1) ? 1 : 0; 	    
    $sl_radius_label=get_option('sl_radius_label');

    // Prep fnvars for passing to our template
    //
    $fnvars = array_merge($fnvars,(array) $attributes);       // merge in passed attributes
    
		
	//todo: make sure map type gets set to a sane value before getting here. Maybe not...

    //todo: if we allow map setting overrides via shortcode attributes we will need
    // to re-localize the script.  It was moved to the actions class so we can
    // localize prior to enqueue in the header.
    //

    // Setup the style sheets
    //
    setup_stylesheet_for_slplus();


    // Set our flag for later processing
    // of JavaScript files
    //
    if (!defined('SLPLUS_SHORTCODE_RENDERED')) {
        define('SLPLUS_SHORTCODE_RENDERED',true);
    }

    // Search / Map Actions
    //
    add_action('slp_render_search_form',array('SLPlus_UI','slp_render_search_form'));

    return get_string_from_phpexec(SLPLUS_COREDIR . 'templates/search_and_map.php');
}


/**************************************
 * SetMapCenter()
 *
 * Set the starting point for the center of the map.
 * Uses country by default.
 * Pro Pack v2.4+ allows for a custom address.
 */
function SetMapCenter() {
    global $slplus_plugin;
    $customAddress = get_option(SLPLUS_PREFIX.'_map_center');
    if (
        (preg_replace('/\W/','',$customAddress) != '') &&
        $slplus_plugin->license->packages['Pro Pack']->isenabled &&
        ($slplus_plugin->license->packages['Pro Pack']->active_version >= 2004000)
        ) {
        return str_replace(array("\r\n","\n","\r"),', ',esc_attr($customAddress));
    }
    return esc_attr(get_option('sl_google_map_country','United States'));    
}

/*-------------------------------------------------------------*/
function comma($a) {
	$a=ereg_replace('"', "&quot;", $a);
	$a=ereg_replace("'", "&#39;", $a);
	$a=ereg_replace(">", "&gt;", $a);
	$a=ereg_replace("<", "&lt;", $a);
	$a=ereg_replace(" & ", " &amp; ", $a);
	return ereg_replace("," ,"&#44;" ,$a);
	
}

/************************************************************
 * Copy a file, or recursively copy a folder and its contents
 */
function csl_copyr($source, $dest)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, 0755);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        csl_copyr("$source/$entry", "$dest/$entry");
    }

    // Clean up
    $dir->close();
    return true;
}

