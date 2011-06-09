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
 ** function: slplus_create_country_pd()
 ** 
 ** Create the county pulldown list, mark the checked item.
 **
 **/
function slplus_create_country_pd() {

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
              $country_options.=
                "&lt;option value='$value[country]'&gt;" .
                "$value[country]&lt;/option&gt;";
            }
        }
    }        
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
function slplus_shortcode_atts() {
    shortcode_atts(
        array(
            'tags_for_pulldown'=> null, 
            'only_with_tag'    => null,
            ),
        $attributes
        );

}

