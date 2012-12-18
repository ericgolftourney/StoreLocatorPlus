<?php 
/******************************************************************************
 * file: map-designer.php
 *
 * provide the map designer admin interface
 ******************************************************************************/
 
//===========================================================================
// Supporting Functions
//===========================================================================
require_once(SLPLUS_PLUGINDIR . '/include/slp-adminui_mapsettings_class.php');

global $slplus_plugin;
$slplus_plugin->AdminUI->MapSettings = new SLPlus_AdminUI_MapSettings();


/**************************************
 ** function: slp_createhelpdiv()
 **
 ** Generate the string that displays the help icon and the expandable div
 ** that mimics the WPCSL-Generic forms more info buttons.
 **
 ** Parameters:
 **  $divname (string, required) - the name of the div to toggle
 **  $msg (string, required) - the message to display
 **/
function slp_createhelpdiv($divname,$msg) {
    return "<a class='moreinfo_clicker' onclick=\"swapVisibility('".SLPLUS_PREFIX."-help$divname');\" href=\"javascript:;\">".
        '<div class="'.SLPLUS_PREFIX.'-moreicon" title="click for more info"><br/></div>'.
        "</a>".
        "<div id='".SLPLUS_PREFIX."-help$divname' class='input_note' style='display: none;'>".
            $msg.
        "</div>"
        ;
}

/**
 * function: SavePostToOptionsTable
 */
function SavePostToOptionsTable($optionname,$default=null) {
    if ($default != null) {
        if (!isset($_POST[$optionname])) {
            $_POST[$optionname] = $default;
        }
    }
    if (isset($_POST[$optionname])) {
        update_option($optionname,$_POST[$optionname]);
    }
}

/**************************************
 ** function: SaveCheckboxToDB
 **
 ** Update the checkbox setting in the database.
 **
 ** Parameters:
 **  $boxname (string, required) - the name of the checkbox (db option name)
 **  $prefix (string, optional) - defaults to SLPLUS_PREFIX, can be '' 
 **/
function SaveCheckboxToDB($boxname,$prefix = SLPLUS_PREFIX, $separator='-') {
    $whichbox = $prefix.$separator.$boxname;
    $_POST[$whichbox] = isset($_POST[$whichbox])?1:0;  
    SavePostToOptionsTable($whichbox,0);
}

/**************************************
** function: CreateCheckboxDiv
 **
 ** Update the checkbox setting in the database.
 **
 ** Parameters:
 **  $boxname (string, required) - the name of the checkbox (db option name)
 **  $label (string, optional) - default '', the label to go in front of the checkbox
 **  $message (string, optional) - default '', the help message 
 **  $prefix (string, optional) - defaults to SLPLUS_PREFIX, can be ''  
 **/
function CreateCheckboxDiv($boxname,$label='',$msg='',$prefix=SLPLUS_PREFIX, $disabled=false, $default=0) {
    $whichbox = $prefix.$boxname; 
    return 
        "<div class='form_entry'>".
            "<div class='".SLPLUS_PREFIX."-input'>" .
            "<label  for='$whichbox' ".
                ($disabled?"class='disabled '":' ').
                ">$label:</label>".
            "<input name='$whichbox' value='1' ".
                "type='checkbox' ".
                ((get_option($whichbox,$default) ==1)?' checked ':' ').
                ($disabled?"disabled='disabled'":' ') .
            ">".
            "</div>".
            slp_createhelpdiv($boxname,$msg) .
        "</div>"
        ;
}


/**
 * function: CreateInputDiv
 */
function CreateInputDiv($boxname,$label='',$msg='',$prefix=SLPLUS_PREFIX, $default='') {
    $whichbox = $prefix.$boxname;
    return
        "<div class='form_entry'>" .
            "<div class='".SLPLUS_PREFIX."-input'>" .
                "<label for='$whichbox'>$label:</label>".
                "<input  name='$whichbox' value='".get_option($whichbox,$default)."'>".
            "</div>".
            slp_createhelpdiv($boxname,$msg).
         "</div>"
        ;

}

/**
 * function: CreatePulldownDiv
 */
function CreatePulldownDiv($boxname,$values,$label='',$msg='',$prefix=SLPLUS_PREFIX, $default='') {
    $whichbox = $prefix.$boxname;
    $selected = get_option($whichbox,$default);

    $content =
            "<div class='form_entry'>".
                "<div class='".SLPLUS_PREFIX."-input'>" .
                    "<label for='$whichbox'>$label:</label>" .
                    "<select name='$whichbox'>"
            ;

    foreach ($values as $value){
        $content.="<option value='$value' ".(($value == $selected)?'selected':'').">".
                  $value.
                "</option>";
    }

    $content.=      "</select>".
                "</div>".
                slp_createhelpdiv($boxname,$msg).
            "</div>"
            ;

    return $content;
}

/**
 * function: CreateTextAreaDiv
 */
function CreateTextAreaDiv($boxname,$label='',$msg='',$prefix=SLPLUS_PREFIX, $default='') {
    $whichbox = $prefix.$boxname;
    return
        "<div class='form_entry'>" .
            "<div class='".SLPLUS_PREFIX."-input'>" .
                "<label for='$whichbox'>$label:</label>".
                "<textarea  name='$whichbox'>".stripslashes(esc_textarea(get_option($whichbox,$default)))."</textarea>".
            "</div>".
            slp_createhelpdiv($boxname,$msg).
         "</div>"
        ;

}


//===========================================================================
// Main Processing
//===========================================================================
if (!$_POST) {
    if (is_a($slplus_plugin->Activate,'SLPlus_Activate')) {
        $slplus_plugin->Activate->move_upload_directories();
    }
    $update_msg ='';
} else {
    $sl_google_map_arr=explode(":", $_POST['google_map_domain']);
    update_option('sl_google_map_country', $sl_google_map_arr[0]);
    update_option('sl_google_map_domain', $sl_google_map_arr[1]);
    
    $_POST['height']=preg_replace('/[^0-9]/', '', $_POST['height']);
    $_POST['width'] =preg_replace('/[^0-9]/', '', $_POST['width']);

    // Height if % set range 0..100    
    if ($_POST['height_units'] == '%') {
        $_POST['height'] = max(0,min($_POST['height'],100));
    }    
    update_option('sl_map_height_units', $_POST['height_units']);
    update_option('sl_map_height', $_POST['height']);
    
    // Width if % set range 0..100        
    if ($_POST['width_units'] == '%') {
        $_POST['width'] = max(0,min($_POST['width'],100));
    }    
    update_option('sl_map_width_units', $_POST['width_units']);
    update_option('sl_map_width', $_POST['width']);

    update_option('sl_map_home_icon', $_POST['icon']);
    update_option('sl_map_end_icon', $_POST['icon2']);


    // Text boxes
    //
    $BoxesToHit = array(
        'sl_language'                           ,
        'sl_map_character_encoding'             ,
        'sl_map_radii'                          ,
        'sl_instruction_message'                ,
        'sl_zoom_level'                         ,
        'sl_zoom_tweak'                         ,
        'sl_map_type'                           ,
        'sl_num_initial_displayed'              ,
        'sl_distance_unit'                      ,
        'sl_name_label'                         ,
        'sl_radius_label'                       ,
        'sl_search_label'                       ,
        'sl_website_label'                      ,

        SLPLUS_PREFIX.'_label_directions'       ,
        SLPLUS_PREFIX.'_label_fax'              ,
        SLPLUS_PREFIX.'_label_hours'            ,
        SLPLUS_PREFIX.'_label_phone'            ,
        
        SLPLUS_PREFIX.'_message_noresultsfound' ,
        
        'sl_starting_image'                     ,
        SLPLUS_PREFIX.'_tag_search_selections'  ,
        SLPLUS_PREFIX.'_map_center'             ,
        SLPLUS_PREFIX.'_maxreturned'            ,
        
        SLPLUS_PREFIX.'_search_tag_label'       ,
        SLPLUS_PREFIX.'_state_pd_label'         ,
        SLPLUS_PREFIX.'_find_button_label'      ,

        );
    foreach ($BoxesToHit as $JustAnotherBox) {
        SavePostToOptionsTable($JustAnotherBox);
    }


    // Checkboxes with custom names
    //
    $BoxesToHit = array(
        SLPLUS_PREFIX.'-force_load_js',
        'sl_use_city_search',
        'sl_use_country_search',
        'sl_load_locations_default',
        'sl_map_overview_control',
        'sl_remove_credits',
        'slplus_show_state_pd',
        );
    foreach ($BoxesToHit as $JustAnotherBox) {
        SaveCheckBoxToDB($JustAnotherBox, '','');
    }
       
    // Checkboxes with normal names
    //
    $BoxesToHit = array(
        'show_tag_search',
        'show_tag_any',
        'email_form',
        'show_tags',
        'disable_find_image',
        'disable_initialdirectory',
        'disable_largemapcontrol3d',
        'disable_scalecontrol',
        'disable_scrollwheel',
        'disable_search',
        'disable_maptypecontrol',
        'hide_radius_selections',
        'hide_address_entry',
		'show_search_by_name',
        'use_email_form',
        'use_location_sensor',
        );
    foreach ($BoxesToHit as $JustAnotherBox) {        
        SaveCheckBoxToDB($JustAnotherBox, SLPLUS_PREFIX, '_');
    }

    do_action('slp_save_map_settings');       
    $update_msg = "<div class='highlight'>".__("Successful Update", SLPLUS_PREFIX).'</div>';
}

//---------------------------
//
$slplug_plugin->AdminUI->initialize_variables();

//-- Set Checkboxes
//
$checked2   	    = (isset($checked2)  ?$checked2  :'');
$sl_city_checked	= (get_option('sl_use_city_search',0) ==1)?' checked ':'';
$checked3	        = (get_option('sl_remove_credits',0)  ==1)?' checked ':'';

/**
 * @see http://goo.gl/UAXly - endIcon - the default map marker to be used for locations shown on the map
 * @see http://goo.gl/UAXly - endIconPicker -  the icon selection HTML interface
 * @see http://goo.gl/UAXly - homeIcon - the default map marker to be used for the starting location during a search
 * @see http://goo.gl/UAXly - homeIconPicker -  the icon selection HTML interface
 * @see http://goo.gl/UAXly - iconNotice - the admin panel message if there is a problem with the home or end icon
 * @see http://goo.gl/UAXly - siteURL - get_site_url() WordPress call
 */
if (!isset($slplus_plugin->data['homeIconPicker'] )) {
    $slplus_plugin->data['homeIconPicker'] = $slplus_plugin->AdminUI->rendorIconSelector('icon','prev');
}
if (!isset($slplus_plugin->data['endIconPicker'] )) {
    $slplus_plugin->data['endIconPicker'] = preg_replace('/\.icon\.value/','.icon2.value',$slplus_plugin->data['homeIconPicker']);
    $slplus_plugin->data['endIconPicker'] = preg_replace('/getElementById\("prev"\)/','getElementById("prev2")',$slplus_plugin->data['endIconPicker']);
    $slplus_plugin->data['endIconPicker'] = preg_replace('/getElementById\("icon"\)/','getElementById("icon2")',$slplus_plugin->data['endIconPicker']);
}

// Icon is the old path, notify them to re-select
//
$slplus_plugin->data['iconNotice'] = '';
if (!isset($slplus_plugin->data['siteURL'] )) { $slplus_plugin->data['siteURL']  = get_site_url();                  }
$slplus_plugin->helper->setData(
          'homeicon',
          'get_option',
          array('sl_map_home_icon', SLPLUS_ICONURL . 'sign_yellow_home.png')
          );
$slplus_plugin->helper->setData(
          'endicon',
          'get_option',
          array('sl_map_end_icon', SLPLUS_ICONURL . 'a_marker_azure.png')
          );
if (!(strpos($slplus_plugin->data['homeicon'],'http')===0)) {
    $slplus_plugin->data['homeicon'] = $slplus_plugin->data['siteURL']. $slplus_plugin->data['homeicon'];
}
if (!(strpos($slplus_plugin->data['endicon'],'http')===0)) {
    $slplus_plugin->data['endicon'] = $slplus_plugin->data['siteURL']. $slplus_plugin->data['endicon'];
}
if (!$slplus_plugin->helper->webItemExists($slplus_plugin->data['homeicon'])) {
    $slplus_plugin->data['iconNotice'] .=
        sprintf(
                __('Your home icon %s cannot be located, please select a new one.', 'csl-slplus'),
                $slplus_plugin->data['homeicon']
                )
                .
        '<br/>'
        ;
}
if (!$slplus_plugin->helper->webItemExists($slplus_plugin->data['endicon'])) {
    $slplus_plugin->data['iconNotice'] .=
        sprintf(
                __('Your destination icon %s cannot be located, please select a new one.', 'csl-slplus'),
                $slplus_plugin->data['endicon']
                )
                .
        '<br/>'
        ;
}
if ($slplus_plugin->data['iconNotice'] != '') {
    $slplus_plugin->data['iconNotice'] =
        "<div class='highlight' style='background-color:LightYellow;color:red'><span style='color:red'>".
            $slplus_plugin->data['iconNotice'] .
        "</span></div>"
        ;
}

//-------------------------
// Navbar Section
//-------------------------    
$slplus_plugin->AdminUI->MapSettings->settings->add_section(
    array(
        'name'          => 'Navigation',
        'div_id'        => 'slplus_navbar_wrapper',
        'description'   => $slplus_plugin->helper->get_string_from_phpexec(SLPLUS_COREDIR.'/templates/navbar.php'),
        'auto'          => false,
        'headerbar'     => false,
        'innerdiv'      => false,
        'is_topmenu'    => true
    )
);

//------------------------------------
// Create The Search Form Settings Panel
//
add_action('slp_build_map_settings_panels',array($slplus_plugin->AdminUI->MapSettings,'search_form_settings') ,10);
add_action('slp_build_map_settings_panels',array($slplus_plugin->AdminUI->MapSettings,'map_settings')         ,20);
add_action('slp_build_map_settings_panels',array($slplus_plugin->AdminUI->MapSettings,'results_settings')     ,30);

    
//------------------------------------
// Render It 
//
print $update_msg;
do_action('slp_build_map_settings_panels');
$slplus_plugin->AdminUI->MapSettings->settings->render_settings_page();