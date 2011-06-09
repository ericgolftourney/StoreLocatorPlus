<?php
/****************************************************************************
 ** file: plus.php
 **
 ** The functions that make up the PLUS in Store Locator Plus
 ***************************************************************************/

/**************************************
 ** function: add_slplus_roles_and_caps()
 ** 
 ** Make sure the administrator role has the manage_slp capability.
 **
 **/
function add_slplus_roles_and_caps() {
    $role = get_role('administrator');
    if(!$role->has_cap('manage_slp')) {
        $role->add_cap('manage_slp');
    }
}

/**************************************
 ** function: custom_upload_mimes
 **
 ** Allows WordPress to process csv file types
 **
 **/
function custom_upload_mimes ( $existing_mimes=array() ) {

     // add CSV type     
    $existing_mimes['csv'] = 'text/csv'; 

    // and return the new full result
    return $existing_mimes;

} 

/**************************************
 ** function: execute_and_output_plustemplate()
 ** 
 ** Executes the included php (or html) file and prints out the results.
 ** Makes for easy include templates that depend on processing logic to be
 ** dumped mid-stream into a WordPress page.  A plugin in a plugin sorta.
 **
 ** Parameters:
 **  $file (string, required) - name of the file in the plugin/templates dir
 **/
function execute_and_output_plustemplate($file) {
    $file = SLPLUS_PLUGINDIR.'/plustemplates/'.$file;
    print get_string_from_phpexec($file);
}

 
/**************************************
 ** function: slplus_create_country_pd()
 ** 
 ** Create the county pulldown list, mark the checked item.
 **
 **/
function slplus_create_country_pd() {
    global $wpdb;
    
    $myOptions = '';
    
    // If Use Country Search option is enabled
    // build our country pulldown.
    //
    if (get_option('sl_use_country_search')==1) {
        $cs_array=$wpdb->get_results(
            "SELECT TRIM(sl_country) as country " .
                "FROM ".$wpdb->prefix."store_locator " .
                "WHERE sl_country<>'' " .
                    "AND sl_latitude<>'' AND sl_longitude<>'' " .
                "GROUP BY country " .
                "ORDER BY country ASC", 
            ARRAY_A);
    
        // If we have country data show it in the pulldown
        //
        if ($cs_array) {
            foreach($cs_array as $value) {
              $myOptions.=
                "<option value='$value[country]'>" .
                $value['country']."</option>";
            }
        }
    }    
    return $myOptions;
}


/**************************************
 ** function: slplus_shortcode_atts()
 ** 
 ** Set the entire list of accepted attributes.
 ** The shortcode_atts function ensures that all possible
 ** attributes that could be passed are given a value which
 ** makes later processing in the code a bit easier.
 ** This is basically the equivalent of the php array_merge()
 ** function.
 **
 **/
function slplus_shortcode_atts($attributes) {
    
    shortcode_atts(
        array(
            'tags_for_pulldown'=> null, 
            'only_with_tag'    => null,
            ),
        $attributes
        );

}

