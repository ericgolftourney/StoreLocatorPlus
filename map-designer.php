<?php 
/******************************************************************************
 * file: map-designer.php
 *
 * provide the map designer admin interface
 ******************************************************************************/
 
$update_msg ='';

if (!$_POST) {
    move_upload_directories();
    
} else {
    if (isset($_POST['sl_language'])) { 
    	    update_option('sl_language', $_POST['sl_language']);
    }
    $sl_google_map_arr=explode(":", $_POST['google_map_domain']);
    update_option('sl_google_map_country', $sl_google_map_arr[0]);
    update_option('sl_google_map_domain', $sl_google_map_arr[1]);
    update_option('sl_map_character_encoding', $_POST['sl_map_character_encoding']);
    
    $_POST['height']=ereg_replace("[^0-9]", "", $_POST['height']);
    $_POST['width']=ereg_replace("[^0-9]", "", $_POST['width']);
    update_option('sl_map_height', $_POST['height']);
    update_option('sl_map_width', $_POST['width']);
    update_option('sl_map_radii', $_POST['radii']);
    update_option('sl_map_height_units', $_POST['height_units']);
    update_option('sl_map_width_units', $_POST['width_units']);
    update_option('sl_map_home_icon', $_POST['icon']);
    update_option('sl_map_end_icon', $_POST['icon2']);
    update_option('sl_search_label', $_POST['search_label']);
    update_option($prefix.'_search_tag_label', $_POST[$prefix.'_search_tag_label']);
    update_option('sl_radius_label', $_POST['sl_radius_label']);
    update_option('sl_website_label', $_POST['sl_website_label']);
    update_option('sl_instruction_message', $_POST['sl_instruction_message']);
    update_option('sl_zoom_level', $_POST['zoom_level']);
    update_option('sl_starting_image', $_POST['sl_starting_image']);
    update_option('sl_map_type', $_POST['sl_map_type']);
    update_option('sl_num_initial_displayed', $_POST['sl_num_initial_displayed']);    
    update_option('sl_distance_unit', $_POST['sl_distance_unit']);

    # Checkbox settings - can set to issset and save that because the
    # post variable is only set if it is checked, if not checked it is
    # false (0).
    #
    $_POST['sl_use_city_search']=isset($_POST['sl_use_city_search'])?1:0;
    update_option('sl_use_city_search',$_POST['sl_use_city_search']);

    
    $_POST['sl_use_country_search']=isset($_POST['sl_use_country_search'])?1:0;
    update_option('sl_use_country_search',$_POST['sl_use_country_search']);
       
    $_POST['sl_remove_credits']=isset($_POST['sl_remove_credits'])?1:0; 
    update_option('sl_remove_credits',$_POST['sl_remove_credits']);
    
    $_POST['sl_load_locations_default']=isset($_POST['sl_load_locations_default'])?1:0;
    update_option('sl_load_locations_default',$_POST['sl_load_locations_default']);

    $_POST['sl_map_overview_control'] = isset($_POST['sl_map_overview_control'])?1:0;  
    update_option('sl_map_overview_control',$_POST['sl_map_overview_control']);

    $_POST[$prefix.'_show_tag_search'] = isset($_POST[$prefix.'_show_tag_search'])?1:0;  
    update_option($prefix.'_show_tag_search',$_POST[$prefix.'_show_tag_search']);

    
    
    $update_msg = "<div class='highlight'>".__("Successful Update", $text_domain).'</div>';
}

//---------------------------
//
initialize_variables();

$the_domain["United States"]="maps.google.com";
$the_domain["Argentina"]="maps.google.com.ar"; //added 12/5/09
$the_domain["Australia"]="maps.google.com.au";
$the_domain["Austria"]="maps.google.at"; //updated 6/13/09
$the_domain["Belgium"]="maps.google.be";
$the_domain["Brazil"]="maps.google.com.br";
$the_domain["Canada"]="maps.google.ca";
$the_domain["Chile"]="maps.google.cl"; //added 12/5/09
$the_domain["China"]="ditu.google.com"; //added 6/13/09
$the_domain["Czech Republic"]="maps.google.cz";
$the_domain["Denmark"]="maps.google.dk";
$the_domain["Finland"]="maps.google.fi";
$the_domain["France"]="maps.google.fr";
$the_domain["Germany"]="maps.google.de";
$the_domain["Hong Kong"]="maps.google.com.hk"; //added 6/13/09
$the_domain["India"]="maps.google.co.in"; //added 6/13/09
$the_domain["Italy"]="maps.google.it";
$the_domain["Japan"]="maps.google.co.jp"; //updated 6/13/09
$the_domain["Liechtenstein"]="maps.google.li"; //added 6/13/09
$the_domain["Mexico"]="maps.google.com.mx"; //added 12/5/09
$the_domain["Netherlands"]="maps.google.nl";
$the_domain["New Zealand"]="maps.google.co.nz";
$the_domain["Norway"]="maps.google.no";
$the_domain["Poland"]="maps.google.pl";
$the_domain["Portugal"]="maps.google.pt"; //added 12/5/09
$the_domain["Russia"]="maps.google.ru";
$the_domain["Singapore"]="maps.google.com.sg"; //added 12/5/09
$the_domain["South Korea"]="maps.google.co.kr"; //added 6/13/09
$the_domain["Spain"]="maps.google.es";
$the_domain["Sweden"]="maps.google.se";
$the_domain["Switzerland"]="maps.google.ch";
$the_domain["Taiwan"]="maps.google.com.tw"; //updated 6/13/09
$the_domain["United Kingdom"]="maps.google.co.uk";

$char_enc["Default (UTF-8)"]="utf-8";
$char_enc["Western European (ISO-8859-1)"]="iso-8859-1";
$char_enc["Western/Central European (ISO-8859-2)"]="iso-8859-2";
$char_enc["Western/Southern European (ISO-8859-3)"]="iso-8859-3";
$char_enc["Western European/Baltic Countries (ISO-8859-4)"]="iso-8859-4";
$char_enc["Russian (Cyrillic)"]="iso-8859-5";
$char_enc["Arabic (ISO-8859-6)"]="iso-8859-6";
$char_enc["Greek (ISO-8859-7)"]="iso-8859-7";
$char_enc["Hebrew (ISO-8859-8)"]="iso-8859-8";
$char_enc["Western European w/amended Turkish (ISO-8859-9)"]="iso-8859-9";
$char_enc["Western European w/Nordic characters (ISO-8859-10)"]="iso-8859-10";
$char_enc["Thai (ISO-8859-11)"]="iso-8859-11";
$char_enc["Baltic languages & Polish (ISO-8859-13)"]="iso-8859-13";
$char_enc["Celtic languages (ISO-8859-14)"]="iso-8859-14";
$char_enc["Japanese (Shift JIS)"]="shift_jis";
$char_enc["Simplified Chinese (China)(GB 2312)"]="gb2312";
$char_enc["Traditional Chinese (Taiwan)(Big 5)"]="big5";
$char_enc["Hong Kong (HKSCS)"]="hkscs";
$char_enc["Korea (EUS-KR)"]="eus-kr";


//-- Set Checkboxes
//
$checked2   	  = (isset($checked2)  ?$checked2  :'');
$city_checked	  = (get_option('sl_use_city_search')       ==1)?' checked ':'';
$country_checked  = (get_option('sl_use_country_search')    ==1)?' checked ':'';
$checked3	      = (get_option('sl_remove_credits')        ==1)?' checked ':'';
$checked4	      = (get_option('sl_load_locations_default')==1)?' checked ':'';
$checked5	      = (get_option('sl_map_overview_control')  ==1)?' checked ':'';
$show_tag_checked = (get_option($prefix.'_show_tag_search') ==1)?' checked ':'';



$map_type_options=(isset($map_type_options)?$map_type_options:'');
$map_type["".__("Normal", $text_domain).""]="G_NORMAL_MAP";
$map_type["".__("Satellite", $text_domain).""]="G_SATELLITE_MAP";
$map_type["".__("Hybrid", $text_domain).""]="G_HYBRID_MAP";
$map_type["".__("Physical", $text_domain).""]="G_PHYSICAL_MAP";

$icon_str   =(isset($icon_str)  ?$icon_str  :'');
$icon2_str  =(isset($icon2_str) ?$icon2_str :'');


$icon_dir=opendir(SLPLUS_PLUGINDIR."/icons/"); 
while (false !== ($an_icon=readdir($icon_dir))) {
	if (!ereg("^\.{1,2}$", $an_icon) && !ereg("shadow", $an_icon) && !ereg("\.db", $an_icon)) {

		$icon_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='$sl_base/icons/$an_icon' onclick='document.forms[\"mapDesigner\"].icon.value=this.src;document.getElementById(\"prev\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
	}
}
if (is_dir($sl_upload_path."/custom-icons/")) {
	$icon_upload_dir=opendir($sl_upload_path."/custom-icons/");
	while (false !== ($an_icon=readdir($icon_upload_dir))) {
		if (!ereg("^\.{1,2}$", $an_icon) && !ereg("shadow", $an_icon) && !ereg("\.db", $an_icon)) {

			$icon_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='$sl_upload_base/custom-icons/$an_icon' onclick='document.forms[\"mapDesigner\"].icon.value=this.src;document.getElementById(\"prev\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
		}
	}
}

$icon_dir=opendir(SLPLUS_PLUGINDIR."/icons/");
while (false !== ($an_icon=readdir($icon_dir))) {
	if (!ereg("^\.{1,2}$", $an_icon) && !ereg("shadow", $an_icon) && !ereg("\.db", $an_icon)) {

		$icon2_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='$sl_base/icons/$an_icon' onclick='document.forms[\"mapDesigner\"].icon2.value=this.src;document.getElementById(\"prev2\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
	}
}
if (is_dir($sl_upload_path."/custom-icons/")) {
	$icon_upload_dir=opendir($sl_upload_path."/custom-icons/");
	while (false !== ($an_icon=readdir($icon_upload_dir))) {
		if (!ereg("^\.{1,2}$", $an_icon) && !ereg("shadow", $an_icon) && !ereg("\.db", $an_icon)) {

			$icon2_str.="<img style='height:25px; cursor:hand; cursor:pointer; border:solid white 2px; padding:2px' src='$sl_upload_base/custom-icons/$an_icon' onclick='document.forms[\"mapDesigner\"].icon2.value=this.src;document.getElementById(\"prev2\").src=this.src;' onmouseover='style.borderColor=\"red\";' onmouseout='style.borderColor=\"white\";'>";
		}
	}
}
if (is_dir($sl_upload_path."/themes/")) {
	$theme_dir=opendir($sl_upload_path."/themes/"); 

	while (false !== ($a_theme=readdir($theme_dir))) {
		if (!ereg("^\.{1,2}$", $a_theme) && !ereg("\.(php|txt|htm(l)?)", $a_theme)) {

			$selected=($a_theme==get_option('sl_map_theme'))? " selected " : "";
			$theme_str.="<option value='$a_theme' $selected>$a_theme</option>\n";
		}
	}
}
	
$zl[]=0;$zl[]=1;$zl[]=2;$zl[]=3;$zl[]=4;$zl[]=5;$zl[]=6;$zl[]=7;$zl[]=8;
$zl[]=9;$zl[]=10;$zl[]=11;$zl[]=12;$zl[]=13;$zl[]=14;$zl[]=15;$zl[]=16;
$zl[]=17;$zl[]=18;$zl[]=19;

$zoom="<select name='zoom_level'>";
foreach ($zl as $value) {
	$zoom.="<option value='$value' ";
	if (get_option('sl_zoom_level')==$value){ $zoom.=" selected ";}
	$zoom.=">$value</option>";
}
$zoom.="</select>";

foreach($map_type as $key=>$value) {
	$selected2=(get_option('sl_map_type')==$value)? " selected " : "";
	$map_type_options.="<option value='$value' $selected2>$key</option>\n";
}
$icon_notification_msg=((ereg("wordpress-store-locator-location-finder", get_option('sl_map_home_icon')) && ereg("^store-locator", $sl_dir)) || (ereg("wordpress-store-locator-location-finder", get_option('sl_map_end_icon')) && ereg("^store-locator", $sl_dir)))? "<div class='highlight' style='background-color:LightYellow;color:red'><span style='color:red'>".__("You have switched from <strong>'wordpress-store-locator-location-finder'</strong> to <strong>'store-locator'</strong> --- great!<br>Now, please re-select your <b>'Home Icon'</b> and <b>'Destination Icon'</b> below, so that they show up properly on your store locator map.", $text_domain)."</span></div>" : "" ;
$sl_starting_image=get_option('sl_starting_image');


# Design Header
#
print '<div class="map_settings"><div class="wrap"><h2>' . 
    __('Map Settings',$text_domain) .
    "<a href='/wp-admin/admin.php?page=$sl_dir/add-locations.php' class='button add-new-h2'>".
     __('Add Locations',$text_domain). 
    '</a>'.
    "<a href='/wp-admin/admin.php?page=$sl_dir/view-locations.php' class='button add-new-h2'>".
    __('Manage Locations',$text_domain). 
    '</a>'.
    '</h2>'.
    $update_msg .
    "<form method='post' name='mapDesigner'>
        <table class='widefat'>"
        ;
    
# Search Section
#
print "
    <thead>
        <tr>
            <th colspan='2'>".__("Search Settings", $text_domain)."</th>
        </tr>
    </thead>
    ";

# Search : Left Side
#
print "<tr><td class='left_side'>".
      '<h3>'.__('Search Features', $text_domain).'</h3>'.
      '<h4>'.__('Show Search By...',$text_domain).'</h4>'.
      
       "<div class='form_entry'><label for='sl_use_city_search'>".
            __('City Pulldown', $text_domain).":</label>" .
            "<input name='sl_use_city_search' value='1' type='checkbox' $city_checked>".
       "</div>".
       
       "<div class='form_entry'>".
            "<label for='sl_use_country_search'>".
            __('Country Pulldown', $text_domain).":".
            "</label>".
            "<input name='sl_use_country_search' value='1' type='checkbox' $country_checked>".
       "</div>".
       
       "<div class='form_entry'><label for='{$prefix}_show_tag_search'>".
            __('Tag Input', $text_domain).":</label>".
            "<input name='{$prefix}_show_tag_search' value='1' type='checkbox' $show_tag_checked>".
       "</div>".

       
      '</td>';


# Search : Right Side
# Search Labels
#
print "<td class='right_side'>" .
        '<h3>'.__("Labels", $text_domain).'</h3>'.
        
       "<div class='form_entry'>".
            "<label for='search_label'>".
                __("Address Input", $text_domain).':'.
                "</label>".
            "<input name='search_label' value='$search_label'>".
            "<span class='input_note'>".
            __("Label for search form address entry.</span>", $text_domain).
            "</span>".                    
       "</div>".
       
       "<div class='form_entry'>".
            "<label for='search_tag_label'>".
                __("Search By Tag Label", $text_domain).':'.
                "</label>".
                "<input name='{$prefix}_search_tag_label' value='".
                    get_option($prefix.'_search_tag_label').
                    "'>".
            "<span class='input_note'>".
            __("Label for search form tags field.</span>", $text_domain).
            "</span>".                    
       "</div>".       
        
       "<div class='form_entry'>".
            "<label for='sl_radius_label'>".
                __("Radius Dropdown", $text_domain).':'.
                "</label>".
            "<input name='sl_radius_label' value='$sl_radius_label'><br/>".
            "<span class='input_note'>".
            __("Label for search form radius pulldown.</span>", $text_domain).
            "</span>".                    
       "</div>".
    
       "<div class='form_entry'>".
            "<label for='sl_website_label'>".
                __("Website URL", $text_domain).':'.
                "</label>".
            "<input name='sl_website_label' value='$sl_website_label'><br/>".
            "<span class='input_note'>".
            __("Label for website URL in search results.</span>", $text_domain).
            "</span>".                    
        "</div>" .            
    
       "<div class='form_entry'>".
            "<label for='sl_instruction_message'>".
                __("Instruction Message", $text_domain).':'.
                "</label>".
            "<input name='sl_instruction_message' value='$sl_instruction_message' size='50'><br/>".
            "<span class='input_note'>".
            __("Instruction text when map is first displayed.</span>", $text_domain).
            "</span>".                    
        "</div>".
    
    "</td></tr>"
    ;


# Map Designer
#    
print "<thead>
    <tr><th colspan='2'>".__("Map Designer", $text_domain)."</th></tr>
    </thead>";
    
# Map Designer : Left Side
#
print "<tr><td width='40%' class='left_side'>
        <div class='map_designer_settings'>
            <h3>".__("Defaults", $text_domain)."</h3>    
            <div class='form_entry'><label for='sl_map_type'>".__("Default Map Type", $text_domain).":</label>
            <select name='sl_map_type'>\n".$map_type_options."</select></div>
            
            <div class='form_entry'><label for='sl_map_overview_control'>".__("Show Map Inset Box", $text_domain).":</label>    
            <input name='sl_map_overview_control' value='1' type='checkbox' $checked5></div>
            
            <div class='form_entry'><label for='sl_load_locations_default'>".__("Immediately Show Locations", $text_domain).":</label>
            <input name='sl_load_locations_default' value='1' type='checkbox' $checked4></div>
            
            <div class='form_entry'><label for='sl_num_initial_displayed'>".__("Default Locations Shown", $text_domain).":</label>
            <input name='sl_num_initial_displayed' value='$sl_num_initial_displayed' class='small'><br/>
            <span class='input_note'>".__("Recommended Max: 50", $text_domain)."</span>
            </div>
            
            <div class='form_entry'><label for='sl_starting_image'>".__("Starting Image",$text_domain).":</label>
            <input name='sl_starting_image' value='$sl_starting_image' size='25'><br/>
            <span class='input_note'>".__("If set, this image will be displayed until a search is performed.",$text_domain)."</span>
            </div>
        </div>            
    </td>";

# Map Designer : Right Side
#    
print "<td class='right_side'>".
    "<h3>".__("Google Map Interface", $text_domain)."</h3>".
    "<div class='form_entry'>".
        "<label for='google_map_domain'>".
        __("Select Your Location", $text_domain).
        "</label>".
        "<select name='google_map_domain'>";

foreach ($the_domain as $key=>$value) {
	$selected=(get_option('sl_google_map_domain')==$value)?" selected " : "";
	print "<option value='$key:$value' $selected>$key ($value)</option>\n";
}

print "</select></div>".
    "<div class='form_entry'>".
        "<label for='sl_map_character_encoding'>".    
        __("Select Character Encoding", $text_domain).
        "</label>".
        "<select name='sl_map_character_encoding'>";

foreach ($char_enc as $key=>$value) {
	$selected=(get_option('sl_map_character_encoding')==$value)?" selected " : "";
	print "<option value='$value' $selected>$key</option>\n";
}
print "</select></div></td></tr>";
    

# Map Designer : Left Side Row 2
#        
print "<tr><td class='left_side'>    
        <div class='map_designer_settings'>
            <h3>".__("Dimensions", $text_domain)."</h3>
    
            <div><label for='zoom_level'>".__("Zoom Level", $text_domain).":</label>
            $zoom
            <span class='input_note'>".__("19=street level, 0=world view. Show locations overrides this setting.",$text_domain)."</span>
            </div>
            
            <div><label for='height'>".__("Map Height", $text_domain).":</label>
            <input name='height' value='$height' class='small'>&nbsp;".choose_units($height_units, "height_units")."
            </div>
            
            <div><label for='height'>".__("Map Width", $text_domain).":</label>
            <input name='width' value='$width'  class='small'>&nbsp;".choose_units($width_units, "width_units")."
            </div>

            <div><label for='radii'>".__("Radii Options", $text_domain).":</label>
            <input  name='radii' value='$radii' size='25'>
            <span class='input_note'>".
            __("Separate each number with a comma ','. Put parenthesis '( )' around the default.</span>", $text_domain).
            "</span>
            </div>  
            
            <div><label for='height'>".__("Distance Unit", $text_domain).":</label>
            <select name='sl_distance_unit'>".

$the_distance_unit["".__("Kilometers", $text_domain).""]="km";
$the_distance_unit["".__("Miles", $text_domain).""]="miles";

foreach ($the_distance_unit as $key=>$value) {
	$selected=(get_option('sl_distance_unit')==$value)?" selected " : "";
	print "<option value='$value' $selected>$key</option>\n";
}            
    
print "</select>
            </div>            
        </div>
    </td>    
    </td>
    <td class='rightside'>
    <h3>".__("Design", $text_domain)."</h3>".
    
    $icon_notification_msg.
    
    "<div class='form_entry'>".
        "<label for='sl_remove_credits'>".
        __("Remove Credits", $text_domain).
        "</label>".
        "<input name='sl_remove_credits' value='1' type='checkbox' $checked3>".
    "</div>".
    
    "<div class='form_entry'>".
        "<label for='icon'>".
        __("Home Icon", $text_domain).
        "</label>".
        "<input name='icon' size='45' value='$icon' onchange=\"document.getElementById('prev').src=this.value\">".
        "&nbsp;&nbsp;<img id='prev' src='$icon' align='top'><br/>".
        "<div style='margin-left: 150px;'>$icon_str</div>".        
    "</div>".
    
    "<div class='form_entry'>".
        "<label for='icon'>".
        __("Destination Icon", $text_domain).
        "</label>".
        "<input name='icon2' size='45' value='$icon2' onchange=\"document.getElementById('prev2').src=this.value\">".
        "&nbsp;&nbsp;<img id='prev2' src='$icon2'align='top'><br/>".
        "<div style='margin-left: 150px;'>$icon2_str</div>".
    "</div>".
    "</td></tr>".
    "<tr><td colspan='2'>".
    "<input type='submit' value='".__("Update", $text_domain)."' class='button-primary'>".
    "</td></tr>".
    "</table></form>".
    "</div></div>";