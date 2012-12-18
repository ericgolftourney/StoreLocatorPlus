<?php
/**
 *
 * @global type $sl_height
 * @global type $sl_width
 * @global type $sl_width_units
 * @global type $sl_height_units
 * @global type $sl_google_map_domain
 * @global type $sl_google_map_country
 * @global type $sl_location_table_view
 * @global type $sl_search_label
 * @global type $sl_zoom_level
 * @global type $sl_zoom_tweak
 * @global type $sl_use_name_search
 * @global type $sl_radius_label
 * @global type $sl_website_label
 * @global type $sl_num_initial_displayed
 * @global type $sl_load_locations_default
 * @global type $sl_distance_unit
 * @global type $sl_map_overview_control
 */
function initialize_variables() {
    global $sl_height, $sl_width, $sl_width_units, $sl_height_units;
    global $sl_google_map_domain, $sl_google_map_country, $sl_location_table_view;
    global $sl_search_label, $sl_zoom_level, $sl_zoom_tweak, $sl_use_name_search;
    global $sl_radius_label, $sl_website_label, $sl_num_initial_displayed, $sl_load_locations_default;
    global $sl_distance_unit, $sl_map_overview_control;
    
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
    $sl_google_map_domain=get_option('sl_google_map_domain');
    if (empty($sl_google_map_domain)) {
        $sl_google_map_domain="maps.google.com";
        add_option('sl_google_map_domain', $sl_google_map_domain);
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
   

/**
 * Help deserialize data to array.
 *
 * Useful for sl_option_value  field processing.
 *
 * @param type $value
 * @return type
 */
function slp_deserialize_to_array($value) {
    $arrayData = maybe_unserialize($value);
    if (!is_array($arrayData)) {
        if ($arrayData == '') {
            $arrayData = array();
        } else {
            $arrayData = array('value' => $arrayData);
        }
    }
    return $arrayData;
}
