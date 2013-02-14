<?php

/***********************************************************************
* Class: SLPlus_AdminUI
*
* The Store Locator Plus admin UI class.
*
* Provides various UI functions when someone is an admin on the WP site.
*
************************************************************************/

if (! class_exists('SLPlus_AdminUI')) {
    class SLPlus_AdminUI {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        public $addingLocation = false;
        public $currentLocation = array();
        public $parent = null;
        public $plugin = null;
        public $styleHandle = 'csl_slplus_admin_css';

        /*************************************
         * The Constructor
         */
        function __construct($params=null) {

            // Register our admin styleseheet
            //
            if (file_exists(SLPLUS_PLUGINDIR.'css/admin.css')) {
                wp_register_style($this->styleHandle, SLPLUS_PLUGINURL .'/css/admin.css');
            }
        }

        /**
         * Set the parent property to point to the primary plugin object.
         *
         * Returns false if we can't get to the main plugin object.
         *
         * @global wpCSL_plugin__slplus $slplus_plugin
         * @return type boolean true if plugin property is valid
         */
        function setParent() {
            if (!isset($this->parent) || ($this->parent == null)) {
                global $slplus_plugin;
                $this->parent = $slplus_plugin;
                $this->plugin = $slplus_plugin;
            }
            return (isset($this->parent) && ($this->parent != null));
        }

        /**
         * Add an address into the SLP locations database.
         *
         * Returns 'added' or 'duplicate'
         * 
         * @global type $wpdb
         * @param type $fields
         * @param type $sl_values
         * @param type $theaddress
         *
         */
        function add_this_addy($fields,$sl_values,$theaddress,$skipdupes=false,$storename='',$skipGeocode=false) {
            global $wpdb;
            $fields=substr($fields, 0, strlen($fields)-1);
            $sl_values=substr($sl_values, 0, strlen($sl_values)-1);

            // Dupe check?
            //
            if ($skipdupes) {
                $checkDupeQuery =
                'SELECT 1 FROM '. $wpdb->prefix . 'store_locator ' .
                    ' WHERE ' .
                        "CONCAT_WS(', ',sl_store,sl_address,sl_address2,sl_city,sl_state,sl_zip,sl_country)".
                        "='$storename, $theaddress' " .
                      'LIMIT 1'
                        ;
                $wpdb->query($checkDupeQuery);
                if ($wpdb->num_rows == 1) {
                    return 'duplicate';
                }
            }

            $wpdb->query("INSERT into ". $wpdb->prefix . "store_locator ($fields) VALUES ($sl_values);");
            
            // Fire slp_location_added hook
            //
            do_action('slp_location_added',mysql_insert_id());

            if (!$skipGeocode) {
                $this->do_geocoding($theaddress);
            }
            return 'added';
        }

        /**
         * Setup some of the general settings interface elements.
         */
        function build_basic_admin_settings() {
            if (!$this->setParent()) { return; }

            //-------------------------
            // Navbar Section
            //-------------------------
            $this->parent->settings->add_section(
                array(
                    'name'          => 'Navigation',
                    'div_id'        => 'slplus_navbar_wrapper',
                    'description'   => $this->parent->AdminUI->create_Navbar(),
                    'innerdiv'      => false,
                    'is_topmenu'    => true,
                    'auto'          => false,
                    'headerbar'     => false
                )
            );

            //-------------------------
            // How to Use Section
            //-------------------------
             $this->parent->settings->add_section(
                array(
                    'name' => 'How to Use',
                    'description' => $this->parent->helper->get_string_from_phpexec(SLPLUS_PLUGINDIR.'/how_to_use.txt'),
                    'start_collapsed' => false
                )
            );

            //-------------------------
            // Google Communication
            //-------------------------
             $this->parent->settings->add_section(
                array(
                    'name'        => 'Google Communication',
                    'description' => 'These settings affect how the plugin communicates with Google to create your map.'.
                                        '<br/><br/>'
                )
            );

             $this->parent->settings->add_item(
                'Google Communication',
                __('Google API Key','csa-slplus'),
                'api_key',
                'text',
                false,
                'Your Google Maps V3 API Key.  Used for searches only. You will need to ' .
                '<a href="http://code.google.com/apis/console/" target="newinfo">'.
                'go to Google</a> to get your Google Maps API Key.'
            );


             $this->parent->settings->add_item(
                'Google Communication',
                __('Geocode Retries','csa-slplus'),
                'goecode_retries',
                'list',
                false,
                sprintf(__('How many times should we try to set the latitude/longitude for a new address. ' .
                    'Higher numbers mean slower bulk uploads ('.
                    '<a href="%s">plus version</a>'.
                    '), lower numbers makes it more likely the location will not be set during bulk uploads.',
                     'csa-slplus'),
                     'http://www.charlestonsw.com/product/store-locator-plus/'
                     ),                        
                array (
                      'None' => 0,
                      '1' => '1',
                      '2' => '2',
                      '3' => '3',
                      '4' => '4',
                      '5' => '5',
                      '6' => '6',
                      '7' => '7',
                      '8' => '8',
                      '9' => '9',
                      '10' => '10',
                    )
            );

             $this->parent->settings->add_item(
                'Google Communication',
                'Turn Off SLP Maps',
                'no_google_js',
                'checkbox',
                false,
                __('Check this box if your Theme or another plugin is providing Google Maps and generating warning messages.  THIS MAY BREAK THIS PLUGIN.', 'csa-slplus')
            );

            //-------------------------
            // Pro Pack
            //
            $proPackMsg = (
                    $this->parent->license->packages['Pro Pack']->isenabled            ?
                    '' :
                    __('This is a <a href="http://www.charlestonsw.com/product/store-locator-plus/">Pro Pack</a>  feature. ', 'csa-slplus')
                    );
            $slp_rep_desc = __('These settings affect how the Pro Pack add-on behaves. ', 'csa-slplus');
            if (!$this->parent->license->AmIEnabled(true, "SLPLUS-PRO")) {
                $slp_rep_desc .= '<br/><br/>'.$proPackMsg;
            } else {
                $slp_rep_desc .= '<span style="float:right;">(<a href="#" onClick="'.
                        'jQuery.post(ajaxurl,{action: \'license_reset_propack\'},function(response){alert(response);});'.
                        '">'.__('Delete license','csa-slplus').'</a>)</span>';
            }
            $slp_rep_desc .= '<br/><br/>';
            $this->parent->settings->add_section(
                array(
                    'name'        => 'Pro Pack',
                    'description' => $slp_rep_desc
                )
            );
            if ($this->parent->license->AmIEnabled(true, "SLPLUS-PRO")) {
                $this->parent->settings->add_item(
                    'Pro Pack',
                    __('Enable reporting', 'csa-slplus'),
                    'reporting_enabled',
                    'checkbox',
                    false,
                    __('Enables tracking of searches and returned results.  The added overhead ' .
                    'can increase how long it takes to return location search results.', 'csa-slplus')
                );
            }
            // Custom CSS Field
            //
            $this->parent->settings->add_item(
                    'Pro Pack',
                    __('Custom CSS','csa-slplus'),
                    'custom_css',
                    'textarea',
                    false,
                    __('Enter your custom CSS, preferably for SLPLUS styling only but it can be used for any page element as this will go in your page header.','csa-slplus')
                    .$proPackMsg
                        ,
                    null,
                    null,
                    !$this->parent->license->packages['Pro Pack']->isenabled
                    );
        }

        /**
         *
         * @param type $a
         * @return type
         */
        function slp_escape($a) {
            $a=preg_replace("/'/"     , '&#39;'   , $a);
            $a=preg_replace('/"/'     , '&quot;'  , $a);
            $a=preg_replace('/>/'     , '&gt;'    , $a);
            $a=preg_replace('/</'     , '&lt;'    , $a);
            $a=preg_replace('/,/'     , '&#44;'   , $a);
            $a=preg_replace('/ & /'   , ' &amp; ' , $a);
            return $a;
        }

        /**
         * GeoCode a given location and update it in the database.
         *
         * Google Server-Side API geocoding is documented here:
         * https://developers.google.com/maps/documentation/geocoding/index
         *
         * Required Google Geocoding API Params:
         * address
         * sensor=true|false
         *
         * Optional Google Goecoding API Params:
         * bounds
         * language
         * region
         * components
         * 
         * @global type $wpdb
         * @global type $slplus_plugin
         * @param type $address
         * @param type $sl_id
         */
        function do_geocoding($address,$sl_id='') {
            global $wpdb, $slplus_plugin;

            $language = '&language='.$slplus_plugin->helper->getData('map_language','get_item',null,'en');

            $delay = 0;
            $request_url =
                'http://maps.googleapis.com/maps/api/geocode/json'.
                '?sensor=false' .
                $language .
                '&address=' . urlencode($address)
                ;

            // Loop through for X retries
            //
            $iterations = get_option(SLPLUS_PREFIX.'-goecode_retries');
            if ($iterations <= 0) { $iterations = 1; }
            $initial_iterations = $iterations;
            while($iterations){
                $iterations--;

                // Iterate through the rows, geocoding each address
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
                    // Update an existing address
                    //
                    } else {
                        $query = sprintf("UPDATE " . $wpdb->prefix ."store_locator SET sl_latitude = '%s', sl_longitude = '%s' WHERE sl_id = $sl_id LIMIT 1;", mysql_real_escape_string($lat), mysql_real_escape_string($lng));
                    }

                    // Run insert/update
                    //
                    $update_result = $wpdb->query($query);
                    if ($update_result == 0) {
                        $theDBError = htmlspecialchars(mysql_error($wpdb->dbh),ENT_QUOTES);
                        $errorMessage .= (($sl_id!='')?'Location #'.$sl_id.' : ' : '');
                        $errorMessage .= __("Could not set the latitude and/or longitude  ", 'csa-slplus');
                        if ($theDBError != '') {
                            $errorMessage .= sprintf(
                                                    __("Error: %s.", 'csa-slplus'),
                                                    $theDBError
                                                    );
                        } elseif ($update_result === 0) {
                            $errorMessage .=  __(", The latitude and longitude did not change.", 'csa-slplus');
                        } else {
                            $errorMessage .=  __("No error logged.", 'csa-slplus');
                            $errorMessage .= "<br/>\n" . __('Query: ', 'csa-slplus');
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
                    $errorMessage .= sprintf(__("Address %s <font color=red>failed to geocode</font>. ", 'csa-slplus'),$address);
                    $errorMessage .= sprintf(__("URL %s.", 'csa-slplus'),$request_url)."\n<br>";
                    $errorMessage .= sprintf(__("Received status %s.", 'csa-slplus'),$status)."\n<br>";
                    $errorMessage .= sprintf(
                            __("Total attempts %d, waited up to %4.2 seconds between request.", 'csa-slplus'),
                            $initial_iterations,
                            $delay/100000
                            ).
                            "\n<br>";
                  }
                  $delay += 100000;

                // Invalid address
                //
                } else if (strcmp($status, 'ZERO_RESULTS') == 0) {
                    $iterations = 0;
                    $errorMessage .= sprintf(__("Address %s <font color=red>failed to geocode</font>. ", 'csa-slplus'),$address);
                    $errorMessage .= sprintf(__("URL %s.", 'csa-slplus'),$request_url)."\n<br>";
                    $errorMessage .= sprintf(__("Unknown Address! Received status %s.", 'csa-slplus'),$status)."\n<br>";

                // Could Not Geocode
                //
                } else {
                    $geocode_pending = false;
                    echo sprintf(__("Address %s <font color=red>failed to geocode</font>. ", 'csa-slplus'),$address);
                    if ($status != '') {
                        $errorMessage .= sprintf(__("URL %s.", 'csa-slplus'),$request_url)."\n<br>";
                        $errorMessage .= sprintf(__("Received data %s.", 'csa-slplus'),'<pre>'.print_r($json,true).'</pre>')."\n";
                    } else {
                        $errorMessage .= sprintf(__("Reqeust sent to %s.", 'csa-slplus'),$request_url)."\n<br>";
                        $errorMessage .= sprintf(__("Received status %s.", 'csa-slplus'),$status)."\n<br>";
                    }
                }

                // Show Error Messages
                //
                if ($errorMessage != '') {
                    print '<div class="geocode_error">' .
                            '<strong>'.
                            sprintf(
                                __('Read <a href="%s">this</a> if you are having geocoding issues.','csa_slplus'),
                                'http://www.charlestonsw.com/support/documentation/store-locator-plus/troubleshooting/geocoding-errors/'
                                ).
                            "</strong><br/>\n" .
                            $errorMessage .
                            '</div>';
                }

                usleep($delay);
            }
        }

        /**
         * Initialize variables for the map settings.
         * 
         * @global type $sl_google_map_country
         * @global type $sl_location_table_view
         * @global type $sl_search_label
         * @global type $sl_zoom_level
         * @global type $sl_zoom_tweak
         * @global type $sl_use_name_search
         * @global type $sl_radius_label
         * @global type $sl_website_label
         * @global type $sl_load_locations_default
         * @global type $sl_distance_unit
         */
        function initialize_variables() {
            global $sl_google_map_country, $sl_location_table_view,
                $sl_search_label, $sl_zoom_level, $sl_zoom_tweak, $sl_use_name_search,
                $sl_radius_label, $sl_website_label, $sl_load_locations_default,
                $sl_distance_unit;

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

            $sl_zoom_level=get_option('sl_zoom_level','4');
            add_option('sl_zoom_level', $sl_zoom_level);

            $sl_zoom_tweak=get_option('sl_zoom_tweak','1');
            add_option('sl_zoom_tweak', $sl_zoom_tweak);

            $sl_search_label=get_option('sl_search_label');
            if (empty($sl_search_label)) {
                $sl_search_label="Address";
                add_option('sl_search_label', $sl_search_label);
                }
            $sl_location_table_view=get_option('sl_location_table_view');
            if (empty($sl_location_table_view)) {
                $sl_location_table_view="Normal";
                add_option('sl_location_table_view', $sl_location_table_view);
                }
            $sl_google_map_country=get_option('sl_google_map_country');
            if (empty($sl_google_map_country)) {
                $sl_google_map_country="United States";
                add_option('sl_google_map_country', $sl_google_map_country);
            }
        }


        /**
         * Display the manage locations pagination
         *
         * @param type $totalLocations
         * @param int $num_per_page
         * @param int $start
         */
        function manage_locations_pagination($totalLocations = 0, $num_per_page = 10, $start = 0) {
            
            // Variable Init
            $pos=0;
            $prev = min(max(0,$start-$num_per_page),$totalLocations);
            $next = min(max(0,$start+$num_per_page),$totalLocations);
            $num_per_page = max(1,$num_per_page);
            $qry = isset($_GET['q'])?$_GET['q']:'';
            $cleared=preg_replace('/q=$qry/', '', $_SERVER['REQUEST_URI']);

            $extra_text=(trim($qry)!='')    ?
                __("for your search of", 'csa-slplus').
                    " <strong>\"$qry\"</strong>&nbsp;|&nbsp;<a href='$cleared'>".
                    __("Clear&nbsp;Results", 'csa-slplus')."</a>" :
                "" ;

            // URL Regex Replace
            //
            if (preg_match('#&start='.$start.'#',$_SERVER['QUERY_STRING'])) {
                $prev_page=str_replace("&start=$start","&start=$prev",$_SERVER['REQUEST_URI']);
                $next_page=str_replace("&start=$start","&start=$next",$_SERVER['REQUEST_URI']);
            } else {
                $prev_page=$_SERVER['REQUEST_URI']."&start=$prev";
                $next_page=$_SERVER['REQUEST_URI']."&start=$next";
            }
            
            // Pages String
            //
            $pagesString = '';
            if ($totalLocations>$num_per_page) {
                if ((($start/$num_per_page)+1)-5<1) {
                    $beginning_link=1;
                } else {
                    $beginning_link=(($start/$num_per_page)+1)-5;
                }
                if ((($start/$num_per_page)+1)+5>(($totalLocations/$num_per_page)+1)) {
                    $end_link=(($totalLocations/$num_per_page)+1);
                } else {
                    $end_link=(($start/$num_per_page)+1)+5;
                }
                $pos=($beginning_link-1)*$num_per_page;
                for ($k=$beginning_link; $k<$end_link; $k++) {
                    if (preg_match('#&start='.$start.'#',$_SERVER['QUERY_STRING'])) {
                        $curr_page=str_replace("&start=$start","&start=$pos",$_SERVER['QUERY_STRING']);
                    }
                    else {
                        $curr_page=$_SERVER['QUERY_STRING']."&start=$pos";
                    }
                    if (($start-($k-1)*$num_per_page)<0 || ($start-($k-1)*$num_per_page)>=$num_per_page) {
                        $pagesString .= "<a class='page-button' href=\"{$_SERVER['SCRIPT_NAME']}?$curr_page\" >";
                    } else {
                        $pagesString .= "<a class='page-button thispage' href='#'>";
                    }


                    $pagesString .= "$k</a>";
                    $pos=$pos+$num_per_page;
                }
            }

            $prevpages = 
                "<a class='prev-page page-button" .
                    ((($start-$num_per_page)>=0) ? '' : ' disabled' ) .
                    "' href='".
                    ((($start-$num_per_page)>=0) ? $prev_page : '#' ).
                    "'>‹</a>"
                ;
            $nextpages = 
                "<a class='next-page page-button" .
                    ((($start+$num_per_page)<$totalLocations) ? '' : ' disabled') .
                    "' href='".
                    ((($start+$num_per_page)<$totalLocations) ? $next_page : '#').
                    "'>›</a>"
                ;

            $pagesString =
                $prevpages .
                $pagesString .
                $nextpages
                ;

            print
                '<div id="slp_pagination" class="tablenav top">'              .
                    '<div id="slp_pagination_pages" class="tablenav-pages">'    .
                        '<span class="displaying-num">'                         .
                                $totalLocations                                 .
                                ' '.__('locations','csa-slplus')               .
                            '</span>'                                           .
                            '<span class="pagination-links">'                   .
                            $pagesString                                        .
                            '</span>'                                           .
                        '</div>'                                                .
                        $extra_text                                             .
                    '</div>'
                ;
        }

        /**
         * Render the manage locations table header
         *
         * @param array $slpManageColumns - the manage locations columns pre-filter
         */
        function manage_locations_table_header($slpManageColumns,$slpCleanURL,$opt,$dir) {
            $tableHeaderString =
                    "<thead>
                    <tr >
                        <th colspan='1'><input type='checkbox' onclick='checkAll(this,document.forms[\"locationForm\"])' class='button'></th>
                        <th colspan='1'>".__("Actions", 'csa-slplus')."</th>"
                    ;
            foreach ($slpManageColumns as $slpField => $slpLabel) {
                $tableHeaderString .= $this->slpCreateColumnHeader($slpCleanURL,$slpField,$slpLabel,$opt,$dir);
            }
            $tableHeaderString .= '<th>Lat</th><th>Lon</th></tr></thead>';
            return $tableHeaderString;
        }

        /**
         * Enqueue the admin stylesheet when needed.
         */
        function enqueue_admin_stylesheet() {
            wp_enqueue_style($this->styleHandle);
        }

        /**
         * Setup the stylesheet only when needed.
         */
        function set_style_as_needed() {
            $slugPrefix = 'store-locator-plus_page_';

            // Add Locations
            //
            add_action(
                   'admin_print_styles-' . $slugPrefix . 'slp_add_locations',
                    array($this,'enqueue_admin_stylesheet')
                    );

            // General Settings
            //
           add_action(
                   'admin_print_styles-'  . $slugPrefix . 'slp_general_settings',
                    array($this,'enqueue_admin_stylesheet')
                    );
           add_action(
                   'admin_print_styles-'  . 'settings_page_csl-slplus-options',
                    array($this,'enqueue_admin_stylesheet')
                    );


            // Manage Locations
            //
            add_action(
                   'admin_print_styles-' . $slugPrefix . 'slp_manage_locations',
                    array($this,'enqueue_admin_stylesheet')
                    );

            // Map Settings
            //
            add_action(
                   'admin_print_styles-' . $slugPrefix . 'slp_map_settings',
                    array($this,'enqueue_admin_stylesheet')
                    );

            // Reporting
            //
            add_action(
                   'admin_print_styles-' . 'store-locator-le/reporting.php',
                    array($this,'enqueue_admin_stylesheet')
                    );

        }

        /**
         * Check if a URL starts with http://
         *
         * @param type $url
         * @return type
         */
        function url_test($url) {
            return (strtolower(substr($url,0,7))=="http://");
        }

        /**
         * Create the column headers for sorting the table.
         *
         * @param type $theURL
         * @param type $fldID
         * @param type $fldLabel
         * @param type $opt
         * @param type $dir
         * @return type
         */
        function slpCreateColumnHeader($theURL,$fldID='sl_store',$fldLabel='ID',$opt='sl_store',$dir='ASC') {
            if ($opt == $fldID) {
                $curDIR = (($dir=='ASC')?'DESC':'ASC');
            } else {
                $curDIR = $dir;
            }
            return "<th class='manage-column sortable'><a href='$theURL&o=$fldID&sortorder=$curDIR'>" .
                    "<span>$fldLabel</span>".
                    "<span class='sorting-indicator'></span>".
                    "</a></th>";
        }

        /**
         * Draw the add locations page.
         *
         * @global type $wpdb
         */
         function renderPage_AddLocations() {
                global $slplus_plugin,$wpdb;
                $this->initialize_variables();

                print "<div class='wrap'>
                            <div id='icon-add-locations' class='icon32'><br/></div>
                            <h2>Store Locator Plus - ".
                            __('Add Locations', 'csa-slplus').
                            "</h2>" .
                            $this->parent->AdminUI->create_Navbar()
                      ;

                //Inserting addresses by manual input
                //
                if ( isset($_POST['store-']) && $_POST['store-']) {
                    $fieldList = '';
                    $sl_valueList = '';
                    foreach ($_POST as $key=>$sl_value) {
                        if (preg_match('#\-$#', $key)) {
                            $fieldList.='sl_'.preg_replace('#\-$#','',$key).',';
                            $sl_value=$this->slp_escape($sl_value);
                            $sl_valueList.="\"".stripslashes($sl_value)."\",";
                        }
                    }

                    $this_addy = 
                              $_POST['address-'].', '.
                              $_POST['address2-'].', '.
                              $_POST['city-'].', '.$_POST['state-'].' '.
                              $_POST['zip-'] . ', ' .
                              $_POST['country-']
                              ;

                    $slplus_plugin->AdminUI->add_this_addy($fieldList,$sl_valueList,$this_addy);
                    print "<div class='updated fade'>".
                            $_POST['store-'] ." " .
                            __("Added Succesfully",'csa-slplus') . '.</div>';

                /** Bulk Upload
                 **/
                } elseif ( 
                    isset($this->plugin->ProPack)     &&
                    isset($_FILES['csvfile']['name']) &&
                    ($_FILES['csvfile']['name']!='')  &&
                    ($_FILES['csvfile']['size'] > 0)
                   ) {
                    $this->plugin->ProPack->bulk_upload_processing();
                }

                $base=get_option('siteurl');
                $this->addingLocation = true;
                print 
                    '<div id="location_table_wrapper">'.
                        "<table id='manage_locations_table' class='slplus wp-list-table widefat fixed posts' cellspacing=0>" .
                            '<tr><td class="slp_locationinfoform_cell">' .
                                $slplus_plugin->AdminUI->createString_LocationInfoForm(array(),'', true) .
                            '</td></tr>' .
                        '</table>' .
                    '</div>'
                    ;
         }

         /**
          * Return the value of the field specified for the current location.
          * @param string $fldname - a location field
          * @param boolean $blankifnull - if true return '' if fldname===null
          * @return string - value of the field
          */
         function getFieldValue($fldname=null,$blankifnull = false) {
             if ($blankifnull && ($fldname === null)) { return ''; }
             if (($fldname === null) || ($this->addingLocation)) { return ''; }
             return $this->currentLocation[$fldname];
         }

        /**
         * Render the edit locations form fields.
         *
         * @param named array $sl_value the location data.
         * @return string HTML of the form inputs
         */
        function renderFields_editlocation() {
            if (!$this->setParent()) { return; }

            $content = '';
            ob_start();
            ?>
            <table>
                <tr>
                    <td><div class="add_location_form">
                        <label  for='store-<?php echo $this->getFieldValue('sl_id')?>'><?php _e('Name of Location', 'csa-slplus');?></label>
                        <input name='store-<?php echo $this->getFieldValue('sl_id')?>' value='<?php echo $this->getFieldValue('sl_store')?>'><br/>

                        <label  for='address-<?php echo $this->getFieldValue('sl_id')?>'><?php _e('Street - Line 1', 'csa-slplus');?></label>
                        <input name='address-<?php echo $this->getFieldValue('sl_id')?>' value='<?php echo $this->getFieldValue('sl_address')?>'><br/>

                        <label  for='address2-<?php echo $this->getFieldValue('sl_id')?>'><?php _e('Street - Line 2', 'csa-slplus');?></label>
                        <input name='address2-<?php echo $this->getFieldValue('sl_id')?>' value='<?php echo $this->getFieldValue('sl_address2')?>'><br/>

                        <label  for='city-<?php echo $this->getFieldValue('sl_id')?>'><?php _e('City, State, ZIP', 'csa-slplus');?></label>
                        <input name='city-<?php echo $this->getFieldValue('sl_id')?>'    value='<?php echo $this->getFieldValue('sl_city')?>'     style='width: 21.4em; margin-right: 1em;'>
                        <input name='state-<?php echo $this->getFieldValue('sl_id')?>'   value='<?php echo $this->getFieldValue('sl_state')?>'    style='width: 7em; margin-right: 1em;'>
                        <input name='zip-<?php echo $this->getFieldValue('sl_id')?>'     value='<?php echo $this->getFieldValue('sl_zip')?>'      style='width: 7em;'><br/>

                        <label  for='country-<?php echo $this->getFieldValue('sl_id')?>'><?php _e('Country', 'csa-slplus');?></label>
                        <input name='country-<?php echo $this->getFieldValue('sl_id')?>' value='<?php echo $this->getFieldValue('sl_country')?>'  style='width: 40em;'><br/>

                        <?php
                        if ($this->parent->AdminUI->addingLocation === false) {
                        ?>
                            <label  for='latitude-<?php echo $this->getFieldValue('sl_id')?>'><?php _e('Latitude (N/S)', 'csa-slplus');?></label>
                            <?php if ($this->parent->license->packages['Pro Pack']->isenabled) { ?>
                                <input name='latitude-<?php echo $this->getFieldValue('sl_id')?>' value='<?php echo $this->getFieldValue('sl_latitude')?>'  style='width: 40em;'><br/>
                            <?php } else { ?>
                                <input class='disabled'  name='latitude-<?php echo $this->getFieldValue('sl_id')?>' value='<?php echo __('Changing the latitude is a Pro Pack feature.','csa-slplus').' ('.$this->getFieldValue('sl_latitude').')';?>'  style='width: 40em;'><br/>
                            <?php } ?>

                            <label  for='longitude-<?php echo $this->getFieldValue('sl_id')?>'><?php _e('Longitude (E/W)', 'csa-slplus');?></label>
                            <?php if ($this->parent->license->packages['Pro Pack']->isenabled) { ?>
                                <input name='longitude-<?php echo $this->getFieldValue('sl_id')?>' value='<?php echo $this->getFieldValue('sl_longitude')?>'  style='width: 40em;'><br/>
                            <?php } else { ?>
                                <input class='disabled' name='longitude-<?php echo $this->getFieldValue('sl_id')?>' value='<?php echo __('Changing the longitude is a Pro Pack feature.','csa-slplus').' ('.$this->getFieldValue('sl_longitude').')'; ?>'  style='width: 40em;'><br/>
                            <?php } ?>
                        <?php
                        }
                        ?>
                        </div>
                    </td>
                </tr>
            </table>
            <?php
            $content .= ob_get_clean();
            return $content;
        }

        /**
         * Render the General Settings admin page.
         *
         */
        function renderPage_GeneralSettings() {
            global $slplus_plugin;
            $slplus_plugin->settings->render_settings_page();
        }


        /**
         * Render the Manage Locations admin page.
         */
        function renderPage_ManageLocations() {
            require_once(SLPLUS_PLUGINDIR . '/include/slp-adminui_managelocations_class.php');
            $this->parent->AdminUI->ManageLocations = new SLPlus_AdminUI_ManageLocations();
            $this->parent->AdminUI->ManageLocations->render_adminpage();
        }

        /**
         * Render the Map Settings admin page.
         */
        function renderPage_MapSettings() {
            require_once(SLPLUS_PLUGINDIR . '/include/slp-adminui_mapsettings_class.php');
            $this->parent->AdminUI->MapSettings = new SLPlus_AdminUI_MapSettings();
            $this->parent->AdminUI->MapSettings->render_adminpage();
        }

         /**
          * Returns the string that is the Location Info Form guts.
          *
          * @global wpCSL_plugin__slplus $slplus_plugin
          * @param mixed $sl_value - the data values for this location in array format
          * @param int $locID - the ID number for this location
          * @param bool $addform - true if rendering add locations form
          */
         function createString_LocationInfoForm($sl_value, $locID, $addform=false) {
            global $slplus_plugin;
            $this->addingLocation = $addform;
            
            $slpEditForm = '';
            $this->currentLocation = apply_filters('slp_edit_location_data',$sl_value);

            /**
             * @see  http://goo.gl/ooXFC 'slp_edit_location_data' filter to manipulate edit location incoming data
             */
             $content  = ''                                                                     .
                "<form id='manualAddForm' name='manualAddForm' method='post' enctype='multipart/form-data'>"       .
                "<input type='hidden' name='locationID' id='locationID' value='$locID' />" .
                "<a name='a".$locID."'></a>"                                                    .
                "<table cellpadding='0' class='slp_locationinfoform_table'>"                           .
                "<tr><td valign='top'>"                                                         .
                $slplus_plugin->AdminUI->renderFields_editlocation()
                ;

                // Store Pages URLs
                //
                if (
                    ($slplus_plugin->license->packages['Store Pages']->isenabled) &&
                    !$addform &&
                    ($sl_value['sl_pages_url'] != '')
                    ){
                    $shortSPurl = preg_replace('/^.*?store_page=/','',$sl_value['sl_pages_url']);
                    $slpEditForm .= "<label for='store_page'>Store Page</label><a href='$sl_value[sl_pages_url]' target='csa'>$shortSPurl</a><br/>";
                }

                $edCancelURL = isset($_GET['edit']) ?
                    preg_replace('/&edit='.$_GET['edit'].'/', '',$_SERVER['REQUEST_URI']) :
                    $_SERVER['REQUEST_URI']
                    ;

                $alTitle =
                    ($addform?
                        __('Add Location','csa-slplus'):
                        sprintf("%s #%d",__('Update Location', 'csa-slplus'),$locID)
                    );
                $slpEditForm .= 
                        ($addform? '' : "<span class='slp-edit-location-id'>Location # $locID</span>") .
                        "<div id='slp_form_buttons'>" .
                        "<input type='submit' value='".($addform?__('Add','csa-slplus'):__('Update', 'csa-slplus')).
                            "' alt='$alTitle' title='$alTitle' class='button-primary'>".
                        "<input type='button' class='button' value='".__('Cancel', 'csa-slplus')."' onclick='location.href=\"".$edCancelURL."\"'>".
                        "<input type='hidden' name='option_value-$locID' value='".($addform?'':$sl_value['sl_option_value'])."' />"  .
                        "</div>"
                        ;

                /**
                 * @see  http://goo.gl/ooXFC 'slp_edit_location_left_column' filter to manipulate edit location form, left column
                 */
                $content .= apply_filters('slp_edit_location_left_column',$slpEditForm)             .
                    '</td>'                                                                         .
                    "<td id='slp_manual_update_table_right_cell'>"
                    ;
                        
                $slpEditForm =
                        "<div id='slp_edit_right_column'>" .

                        "<strong>".__("Additional Information", 'csa-slplus')."</strong><br>".

                        "<textarea name='description-$locID' rows='5' cols='17'>".($addform?'':$sl_value['sl_description'])."</textarea>&nbsp;<small>".
                            __("Description", 'csa-slplus')."</small><br>".

                        "<input    name='tags-$locID'  value='".($addform?'':$sl_value['sl_tags'] )."'>&nbsp;<small>".
                            __("Tags (seperate with commas)", 'csa-slplus')."</small><br>".

                        "<input    name='url-$locID'   value='".($addform?'':$sl_value['sl_url']  )."'>&nbsp;<small>".
                            get_option('sl_website_label','Website')."</small><br>".

                        "<input    name='email-$locID' value='".($addform?'':$sl_value['sl_email'])."'>&nbsp;<small>".
                            __("Email", 'csa-slplus')."</small><br>".

                        "<input    name='hours-$locID' value='".($addform?'':$sl_value['sl_hours'])."'>&nbsp;<small>".
                            $slplus_plugin->settings->get_item('label_hours','Hours','_')."</small><br>".

                        "<input    name='phone-$locID' value='".($addform?'':$sl_value['sl_phone'])."'>&nbsp;<small>".
                            $slplus_plugin->settings->get_item('label_phone','Phone','_')."</small><br>".

                        "<input    name='fax-$locID'   value='".($addform?'':$sl_value['sl_fax']  )."'>&nbsp;<small>".
                            $slplus_plugin->settings->get_item('label_fax','Fax','_')."</small><br>".

                        "<input    name='image-$locID' value='".($addform?'':$sl_value['sl_image'])."'>&nbsp;<small>".
                            __("Image URL (shown with location)", 'csa-slplus')."</small>" .

                        '</div>'
                        ;

                /**
                 * @see  http://goo.gl/ooXFC 'slp_edit_location_right_column' filter to manipulate edit location form, right column
                 */
                $content .= apply_filters('slp_edit_location_right_column',$slpEditForm);
                $content .= '</td></tr></table>';

                // Bulk upload form
                //
                if ($addform) {
                    $content .= apply_filters('slp_add_location_form_footer', '');
                }

                $content .= '</form>';

                return apply_filters('slp_locationinfoform',$content);
         }

        /**
         * Render the admin page navbar (tabs)
         *
         * @global type $submenu - the WordPress Submenu array
         * @return type
         */
        function create_Navbar() {
            if (!$this->setParent()) { return; }

            global $submenu;
            if (!isset($submenu[$this->plugin->prefix]) || !is_array($submenu[$this->plugin->prefix])) {
                echo apply_filters('slp_navbar','');
            } else {
                $content =
                    '<div id="slplus_navbar">' .
                        '<div class="about-wrap"><h2 class="nav-tab-wrapper">';

                // Loop through all SLP sidebar menu items on admin page
                //
                foreach ($submenu[$this->plugin->prefix] as $slp_menu_item) {

                    // Create top menu item
                    //
                    $selectedTab = ((isset($_REQUEST['page']) && ($_REQUEST['page'] === $slp_menu_item[2])) ? ' nav-tab-active' : '' );
                    $content .= apply_filters(
                            'slp_navbar_item_tweak',
                            '<a class="nav-tab'.$selectedTab.'" href="'.menu_page_url( $slp_menu_item[2], false ).'">'.
                                $slp_menu_item[0].
                            '</a>'
                            );
                }
                $content .= apply_filters('slp_navbar_item','');
                $content .='</h2></div></div>';
                return apply_filters('slp_navbar',$content);
            }
        }

        /**
         * Return the icon selector HTML for the icon images in saved icons and default icon directories.
         *
         * @param type $inputFieldID
         * @param type $inputImageID
         * @return string
         */
         function CreateIconSelector($inputFieldID = null, $inputImageID = null) {
            if (!$this->setParent()) { return 'could not set parent'; }
            if (($inputFieldID == null) || ($inputImageID == null)) { return ''; }


            $htmlStr = '';
            $files=array();
            $fqURL=array();


            // If we already got a list of icons and URLS, just use those
            //
            if (
                isset($this->plugin->data['iconselector_files']) &&
                isset($this->plugin->data['iconselector_urls'] ) 
               ) {
                $files = $this->plugin->data['iconselector_files'];
                $fqURL = $this->plugin->data['iconselector_urls'];

            // If not, build the icon info but remember it for later
            // this helps cut down looping directory info twice (time consuming)
            // for things like home and end icon processing.
            //
            } else {

                // Load the file list from our directories
                //
                // using the same array for all allows us to collapse files by
                // same name, last directory in is highest precedence.
                $iconAssets = apply_filters('slp_icon_directories',
                        array(
                                array('dir'=>SLPLUS_UPLOADDIR.'saved-icons/',
                                      'url'=>SLPLUS_UPLOADURL.'saved-icons/'
                                     ),
                                array('dir'=>SLPLUS_ICONDIR,
                                      'url'=>SLPLUS_ICONURL
                                     )
                            )
                        );
                $fqURLIndex = 0;
                foreach ($iconAssets as $icon) {
                    if (is_dir($icon['dir'])) {
                        if ($iconDir=opendir($icon['dir'])) {
                            $fqURL[] = $icon['url'];
                            while ($filename = readdir($iconDir)) {
                                if (strpos($filename,'.')===0) { continue; }
                                $files[$filename] = $fqURLIndex;
                            };
                            closedir($iconDir);
                            $fqURLIndex++;
                        } else {
                            $this->parent->notifications->add_notice(
                                    9,
                                    sprintf(
                                            __('Could not read icon directory %s','csa-slplus'),
                                            $directory
                                            )
                                    );
                             $this->parent->notifications->display();
                        }
                   }
                }
                ksort($files);
                $this->plugin->data['iconselector_files'] = $files;
                $this->plugin->data['iconselector_urls']  = $fqURL;
            }

            // Build our icon array now that we have a full file list.
            //
            foreach ($files as $filename => $fqURLIndex) {
                if (
                    (preg_match('/\.(png|gif|jpg)/i', $filename) > 0) &&
                    (preg_match('/shadow\.(png|gif|jpg)/i', $filename) <= 0)
                    ) {
                    $htmlStr .=
                        "<div class='slp_icon_selector_box'>".
                            "<img class='slp_icon_selector'
                                 src='".$fqURL[$fqURLIndex].$filename."'
                                 onclick='".
                                    "document.getElementById(\"".$inputFieldID."\").value=this.src;".
                                    "document.getElementById(\"".$inputImageID."\").src=this.src;".
                                 "'>".
                         "</div>"
                         ;
                }
            }

            // Wrap it in a div
            //
            if ($htmlStr != '') {
                $htmlStr = '<div id="'.$inputFieldID.'_icon_row" class="slp_icon_row">'.$htmlStr.'</div>';

            }


            return $htmlStr;
         }

    }
}        
     

