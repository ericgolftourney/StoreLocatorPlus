<?php

/**
 * Store Locator Plus Ajax Handler
 *
 * Manage the AJAX calls that come in from our admin and frontend UI.
 * Currently only holds new AJAX calls, all calls need to go in here.
 *
 * @package StoreLocatorPlus\AjaxHandler
 * @author Lance Cleveland <lance@charlestonsw.com>
 * @copyright 2012-2013 Charleston Software Associates, LLC
 */
class SLPlus_AjaxHandler {

    //-------------------------------------
    // Properties
    //-------------------------------------
    
    /**
     * The plugin object.
     * 
     * @var SLPlus $plugin 
     */
    public $plugin;


    /**
     * The database query string.
     *
     * @var string $dbQuery
     */
    private $dbQuery;

    //----------------------------------
    // Methods
    //----------------------------------
    
    /*************************************
     * The Constructor
     */
    function __construct($params=null) {
    }

    /**
     * Set the plugin property to point to the primary plugin object.
     *
     * Returns false if we can't get to the main plugin object.
     *
     * @global wpCSL_plugin__slplus $slplus_plugin
     * @return boolean true if plugin property is valid
     */
    function setPlugin() {
        if (!isset($this->plugin) || ($this->plugin == null)) {
            global $slplus_plugin;
            $this->plugin = $slplus_plugin;
        }
        return (isset($this->plugin) && ($this->plugin != null));
    }

    /**
     * Format the result data into a named array.
     *
     * We will later use this to build our JSONP response.
     *
     * @param mixed[] $data the data from the SLP database
     * @return mixed[]
     */
    function slp_add_marker($row = null) {
        if ($row == null) {
            return '';
        }
        $marker = array(
              'name'        => esc_attr($row['sl_store']),
              'address'     => esc_attr($row['sl_address']),
              'address2'    => esc_attr($row['sl_address2']),
              'city'        => esc_attr($row['sl_city']),
              'state'       => esc_attr($row['sl_state']),
              'zip'         => esc_attr($row['sl_zip']),
              'country'     => esc_attr($row['sl_country']),
              'lat'         => $row['sl_latitude'],
              'lng'         => $row['sl_longitude'],
              'description' => html_entity_decode($row['sl_description']),
              'url'         => esc_attr($row['sl_url']),
              'sl_pages_url'=> esc_attr($row['sl_pages_url']),
              'email'       => esc_attr($row['sl_email']),
              'hours'       => esc_attr($row['sl_hours']),
              'phone'       => esc_attr($row['sl_phone']),
              'fax'         => esc_attr($row['sl_fax']),
              'image'       => esc_attr($row['sl_image']),
              'distance'    => $row['sl_distance'],
              'tags'        => ((get_option(SLPLUS_PREFIX.'_show_tags',0) ==1)? esc_attr($row['sl_tags']) : ''),
              'option_value'=> esc_js($row['sl_option_value']),
              'id'          => $row['sl_id'],
          );

          $marker = apply_filters('slp_results_marker_data',$marker);
          return $marker;
    }

    /**
     * Handle AJAX request for OnLoad action.
     *
     */
    function csl_ajax_onload() {
        $this->setPlugin();

        //.......
        // Params
        //.......
        $num_initial_displayed=trim(get_option('sl_num_initial_displayed','25'));

        // If tags are passed filter to just those tags
        //
        $tag_filter = '';
        if (
            isset($_POST['tags']) && ($_POST['tags'] != '')
           ){
            $posted_tag = preg_replace('/^\s+(.*?)/','$1',$_POST['tags']);
            $posted_tag = preg_replace('/(.*?)\s+$/','$1',$posted_tag);
            $tag_filter = " AND ( sl_tags LIKE '%%". $posted_tag ."%%') ";
        }

        // If store names are passed, filter show those names
        $name_filter = '';
        if ((get_option(SLPLUS_PREFIX.'_show_name_search') == 1) &&
            isset($_POST['name']) && ($_POST['name'] != ''))
        {
            $posted_name = preg_replace('/^\s+(.*?)/','$1',$_POST['name']);
            $posted_name = preg_replace('/(.*?)\s+$/','$1',$posted_name);
            $name_filter = " AND (sl_store LIKE '%%".$posted_name."%%')";
        }

        //.............
        // Get The Data
        //.............
        $result = $this->execute_LocationQuery($_POST['lat'],$_POST['lng'],$tag_filter,$name_filter,$_POST['radius'],$num_initial_displayed);

        // Iterate through the rows, printing json nodes for each
        $response = array();
        while ($row = @mysql_fetch_assoc($result)){
            $response[] = $this->slp_add_marker($row);
        }

        // Output the JSON and Exit
        //
        $this->renderJSON_Response(
                array(
                        'count'         => count($response) ,
                        'type'          => 'load',
                        'response'      => $response
                    )
                );
    }

    /**
     * Handle AJAX request for Search calls.
     *
     * @global type $wpdb
     */
    function csl_ajax_search() {
        $this->setPlugin();
        global $wpdb;

        //.......
        // Params
        //.......

        // Get parameters from URL
        $center_lat = $_POST["lat"];
        $center_lng = $_POST["lng"];
        $radius     = $_POST["radius"];

        // If tags are passed filter to just those tags
        //
        $tag_filter = '';
        if (
            isset($_POST['tags']) && ($_POST['tags'] != '')
        ){
            $posted_tag = preg_replace('/^\s+(.*?)/','$1',$_POST['tags']);
            $posted_tag = preg_replace('/(.*?)\s+$/','$1',$posted_tag);
            $tag_filter = " AND ( sl_tags LIKE '%%". $posted_tag ."%%') ";
        }

        $name_filter = '';
        if(isset($_POST['name']) && ($_POST['name'] != ''))
        {
            $posted_name = preg_replace('/^\s+(.*?)/','$1',$_POST['name']);
            $posted_name = preg_replace('/(.*?)\s+$/','$1',$posted_name);
            $name_filter = " AND (sl_store LIKE '%%".$posted_name."%%')";
        }

        $option[SLPLUS_PREFIX.'_maxreturned']=(trim(get_option(SLPLUS_PREFIX.'_maxreturned'))!="")?
        get_option(SLPLUS_PREFIX.'_maxreturned') :
        '25';

        //.............
        // Get The Data
        //.............
        $result = $this->execute_LocationQuery($_POST['lat'],$_POST['lng'],$tag_filter,$name_filter,$_POST['radius'],$option[SLPLUS_PREFIX.'_maxreturned']);

        // Iterate through the rows, printing XML nodes for each
        $response = array();
        while ($row = @mysql_fetch_assoc($result)){
            $thisLocation = $this->slp_add_marker($row);
            if (!empty($thisLocation)) {
                $response[] = $thisLocation;

                // Reporting
                // Insert the results into the reporting table
                //
                if (get_option(SLPLUS_PREFIX.'-reporting_enabled') === "on") {
                    $wpdb->query(
                        sprintf(
                            "INSERT INTO {$this->plugin->db->prefix}slp_rep_query_results
                                (slp_repq_id,sl_id) values (%d,%d)",
                                $slp_QueryID,
                                $row['sl_id']
                            )
                        );
                }
            }
        }

        // Reporting
        // Insert the query into the query DB
        //
        if (get_option(SLPLUS_PREFIX.'-reporting_enabled','off') === 'on') {
            $qry = sprintf(
                    "INSERT INTO {$this->plugin->db->prefix}slp_rep_query ".
                               "(slp_repq_query,slp_repq_tags,slp_repq_address,slp_repq_radius) ".
                        "values ('%s','%s','%s','%s')",
                        mysql_real_escape_string($_SERVER['QUERY_STRING']),
                        mysql_real_escape_string($_POST['tags']),
                        mysql_real_escape_string($_POST['address']),
                        mysql_real_escape_string($_POST['radius'])
                    );
            $wpdb->query($qry);
            $slp_QueryID = mysql_insert_id();
        }

        // Output the JSON and Exit
        //
        $this->renderJSON_Response(
                array(  
                        'count'         => count($response),
                        'option'        => $_POST['address'],
                        'type'          => 'search',
                        'response'      => $response
                    )
                );
     }

    /**
     * Run a database query to fetch the locations the user asked for.
     *
     * @param string $lat the latitude
     * @param string $lng the longitude
     * @param string $tagFilter tag filter, if any
     * @param string $nameFilter name filter, if any
     * @param string $searchRadius radius to search within
     * @param string $maxReturned how many results to max out at
     * @return object a MySQL result object
     */
    function execute_LocationQuery($lat,$lng,$tagFilter='',$nameFilter='',$searchRadius='10000',$maxReturned='50') {
        // MySQL
        //
        $username=DB_USER;
        $password=DB_PASSWORD;
        $database=DB_NAME;
        $host=DB_HOST;
        $connection=mysql_connect ($host, $username, $password);
        if (!$connection) {
            die (json_encode( array('success' => false, 'slp_version' => $this->plugin->version, 'response' => 'Not connected : ' . mysql_error())));
        }
        $db_selected = mysql_select_db($database, $connection);
        mysql_query("SET NAMES utf8");
        if (!$db_selected) {
          die (json_encode( array('success' => false, 'slp_version' => $this->plugin->version, 'response' => 'Can\'t use db : ' . mysql_error())));
        }

        // SLP options that tweak the query
        //
        
        //Since miles is default, if kilometers is selected, divide by 1.609344 in order to convert the kilometer value selection back in miles
        //
        $multiplier=(get_option('sl_distance_unit')=="km")? 6371 : 3959;

        // Make sure max returned is ok
        //
        if (!is_numeric($maxReturned)) { $maxReturned = 50; }

        // Run the Query
        //
        $this->dbQuery = sprintf(
            "SELECT *,".
            "( $multiplier * acos( cos( radians('%s') ) * cos( radians( sl_latitude ) ) * cos( radians( sl_longitude ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( sl_latitude ) ) ) ) AS sl_distance ".
            "FROM {$this->plugin->db->prefix}store_locator ".
            "WHERE sl_longitude<>'' and sl_longitude<>'' %s %s ".
            "HAVING (sl_distance < '%s') ".
            'ORDER BY sl_distance ASC '.
            'LIMIT %s',
            mysql_real_escape_string($lat),
            mysql_real_escape_string($lng),
            mysql_real_escape_string($lat),
            $tagFilter,
            $nameFilter,
            mysql_real_escape_string($searchRadius),
            $maxReturned
        );

        // FILTER: slp_mysql_search_query
        //
        $result = mysql_query(apply_filters('slp_mysql_search_query',$this->dbQuery));

        // Problems?  Oh crap.  Die.
        //
        if (!$result) {
            die(json_encode(array(
                'success'       => false, 
                'response'      => 'Invalid query: ' . mysql_error()
            )));
        }

        // Return the results
        //
        return $result;
    }


    /**
     * Output a JSON response based on the incoming data and die.
     *
     * Used for AJAX processing in WordPress where a remote listener expects JSON data.
     *
     * @param mixed[] $data named array of keys and values to turn into JSON data
     * @return null dies on execution
     */
    function renderJSON_Response($data) {
        header( "Content-Type: application/json" );

        // What do you mean we didn't get an array?
        //
        if (!is_array($data)) {
            $data = array(
                'success'       => false,
                'count'         => 0,
                'message'       => __('renderJSON_Response did not get an array()','csa-slplus')
            );
        }

        // Add our SLP Version and DB Query to the output
        //
        $data = array_merge(
                    array(
                        'success'       => true,
                        'slp_version'   => $this->plugin->version,
                        'dbQuery'       => $this->dbQuery
                    ),
                    $data
                );

        // Tell them what is coming...
        //
        header( "Content-Type: application/json" );

        // Go forth and spew data
        //
        echo json_encode($data);

        // Then die.
        //
        die();
    }


    /**
     * Remove the Pro Pack license.
     * 
     * TODO: kill this when Pro Pack is no longer a licensed product.
     */
    function license_reset_propack() {
        if (!$this->setPlugin()) { die(__('Pro Pack license could not be removed.',SLPLUS_PREFIX)); }

        global $wpdb;

        foreach (array(
                    SLPLUS_PREFIX.'-SLPLUS-PRO-isenabled',
                    SLPLUS_PREFIX.'-SLPLUS-PRO-last_lookup',
                    SLPLUS_PREFIX.'-SLPLUS-PRO-latest-version',
                    SLPLUS_PREFIX.'-SLPLUS-PRO-latest-version-numeric',
                    SLPLUS_PREFIX.'-SLPLUS-PRO-lk',
                    SLPLUS_PREFIX.'-SLPLUS-PRO-version',
                    SLPLUS_PREFIX.'-SLPLUS-PRO-version-numeric',
                    )
                as $optionName) {
            $query = 'DELETE FROM '.$wpdb->prefix."options WHERE option_name='$optionName'";
            $wpdb->query($query);
        }

        die(__('Pro Pack license has been removed. Refresh the General Settings page.', SLPLUS_PREFIX));
    }
}
