<?php
/****************************************************************************
 ** file: csl-ajax-search.php
 **
 ** Perform a search via ajax
 ***************************************************************************/
error_reporting(0);
//header("Content-type: text/xml");


function csl_ajax_search() {
	global $wpdb;
	$username=DB_USER;
	$password=DB_PASSWORD;
	$database=DB_NAME;
	$host=DB_HOST;
	//include("database-info.php");
	$dbPrefix = $wpdb->prefix;
	
	// Get parameters from URL
	$center_lat = $_POST["lat"];
	$center_lng = $_POST["lng"];
	$radius = $_POST["radius"];
	/*
	// Opens a connection to a MySQL server
	$conection=mysql_connect ($host, $username, $password);
	if (!$connection) {
		die(json_encode( 
			array('success' => false, 
			'response' => 'mysql server disconnected: "'.$username.':'.$password.'@'.$host.'/'.$dbPrefix.'.'.$database.'" with error: '.mysql_error()
			)
		));
	}*/

	//-----------------
	// Set the active MySQL database
	//
	$connection=mysql_connect ($host, $username, $password);
	if (!$connection) { die(json_encode( array('success' => false, 'response' => 'Not connected : ' . mysql_error()))); }
	$db_selected = mysql_select_db($database, $connection);
	mysql_query("SET NAMES utf8");
	if (!$db_selected) {
		die (json_encode( array('success' => false, 'response' => 'Can\'t use db : ' . mysql_error())));
	}

	// If tags are passed filter to just those tags
	//
	$tag_filter = ''; 
	if (
		(get_option($prefix.'_show_tag_search') ==1) &&
		isset($_POST['tags']) && ($_POST['tags'] != '')
	){
		$posted_tag = preg_replace('/^\s+(.*?)/','$1',$_POST['tags']);
		$posted_tag = preg_replace('/(.*?)\s+$/','$1',$posted_tag);
		$tag_filter = " AND ( sl_tags LIKE '%%". $posted_tag ."%%') ";
	}

	//Since miles is default, if kilometers is selected, divide by 1.609344 in order to convert the kilometer value selection back in miles when generating the XML
	//
	$multiplier=3959;
	$multiplier=(get_option('sl_distance_unit')=="km")? ($multiplier*1.609344) : $multiplier;

	$option[SLPLUS_PREFIX.'_maxreturned']=(trim(get_option(SLPLUS_PREFIX.'_maxreturned'))!="")? 
    get_option(SLPLUS_PREFIX.'_maxreturned') : 
    '25';
    
	// Select all the rows in the markers table
	$query = sprintf(
	"SELECT *,".
	"( $multiplier * acos( cos( radians('%s') ) * cos( radians( sl_latitude ) ) * cos( radians( sl_longitude ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( sl_latitude ) ) ) ) AS sl_distance ".
	"FROM ${dbPrefix}store_locator HAVING (sl_distance < '%s') ".
	$tag_filter .
	'ORDER BY sl_distance ASC ' .
	'LIMIT %s',
	mysql_real_escape_string($center_lat),
	mysql_real_escape_string($center_lng),
	mysql_real_escape_string($center_lat),
	mysql_real_escape_string($radius),
	mysql_real_escape_string($option[SLPLUS_PREFIX.'_maxreturned'])
	);
    
	$result = mysql_query($query);
	if (!$result) {
		die(json_encode( array('success' => false, 'response' => 'Invalid query: ' . mysql_error())));
	}

	// Show Tags
	//
	$slplus_show_tags = (get_option(SLPLUS_PREFIX.'_show_tags') ==1);

	// Start the response string
	$response = array();
	
	// Iterate through the rows, printing XML nodes for each
	while ($row = @mysql_fetch_assoc($result)){
		// ADD to array of markers
		
		$marker = array(
			//'test' => stuff
			'name' => esc_attr($row['sl_store']),
			'address' => esc_attr($row['sl_address']),
			'address2' => esc_attr($row['sl_address2']),
			'city' => esc_attr($row['sl_city']),
			'state' => esc_attr($row['sl_state']),
			'zip' => esc_attr($row['sl_zip']),
			'lat' => $row['sl_latitude'],
			'lng' => $row['sl_longitude'],
			'description' => esc_attr($row['sl_description']),
			'url' => esc_attr($row['sl_url']),
			'sl_pages_url' => esc_attr($row['sl_pages_url']),
			'email' => esc_attr($row['sl_email']),
			'hours' => esc_attr($row['sl_hours']),
			'phone' => esc_attr($row['sl_phone']),
			'image' => esc_attr($row['sl_image']),
			'tags' => ($slplus_show_tags) ? esc_attr($row['sl_tags']) : '==notags=='
		);
		$response[] = $marker;
	}
	
	// generate the response
    $response = json_encode( array( 'success' => true, 'response' => $response ) );
 
    // response output
    header( "Content-Type: application/json" );
    echo $response;
	
	// Reporting
    // Insert the results into the reporting table
    //
    if (get_option(SLPLUS_PREFIX.'-reporting_enabled') === "on") {
        $wpdb->query(
            sprintf(
                "INSERT INTO ${dbPrefix}slp_rep_query_results 
                    (slp_repq_id,sl_id) values (%d,%d)",
                    $slp_QueryID,
                    $row['sl_id']  
                )
            );           
    }  
 
    // IMPORTANT: don't forget to "exit"
    exit;
 }
