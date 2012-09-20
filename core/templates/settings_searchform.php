<?php 
    global  $sl_city_checked, $sl_country_checked, $sl_show_tag_checked, $sl_show_any_checked,
        $sl_radius_label, $sl_website_label,$sl_instruction_message,$slpMapSettings,$sl_the_distance_unit;
?>       
<div id='search_settings'>

    <!-- Features Section -->
    <div class='section_column'>              
            <h2><?php _e('Features', SLPLUS_PREFIX);?></h2>
<?php
            echo CreateInputDiv(
                    'sl_map_radii',
                    __('Radii Options', SLPLUS_PREFIX),
                    __('Separate each number with a comma ",". Put parenthesis "( )" around the default.',SLPLUS_PREFIX),
                    '',
                    '10,25,50,100,(200),500'
                    );

?>
            
        <div class='form_entry'>
            <label for='sl_distance_unit'><?php _e('Distance Unit', SLPLUS_PREFIX);?>:</label>
            <select name='sl_distance_unit'>
            <?php
                $sl_the_distance_unit[__("Kilometers", SLPLUS_PREFIX)]="km";
                $sl_the_distance_unit[__("Miles", SLPLUS_PREFIX)]="miles";
                
                foreach ($sl_the_distance_unit as $key=>$sl_value) {
                    $selected=(get_option('sl_distance_unit')==$sl_value)?" selected " : "";
                    print "<option value='$sl_value' $selected>$key</option>\n";
                }
                ?>
            </select>
        </div>    
        
           
        <?php

        //----------------------------------------------------------------------
        // Pro Pack Enabled
        //
        global $slplus_plugin;
        if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {
            echo CreateCheckboxDiv(
                '_hide_radius_selections',
                __('Hide radius selection',SLPLUS_PREFIX),
                __('Hides the radius selection from the user, the default radius will be used.', SLPLUS_PREFIX)
                );

            echo CreateCheckboxDiv(
                '_show_search_by_name',
                __('Show search by name box', SLPLUS_PREFIX),
                __('Shows the name search entry box to the user.', SLPLUS_PREFIX)
                );

            echo CreateCheckboxDiv(
                '_hide_address_entry',
                __('Hide address entry box',SLPLUS_PREFIX),
                __('Hides the address entry box from the user.', SLPLUS_PREFIX)
                );

            echo CreateCheckboxDiv(
                '_use_location_sensor',
                __('Use location sensor', SLPLUS_PREFIX),
                __('This turns on the location sensor for your customers so they can easily get accurate results')
            );

            echo CreateCheckboxDiv(
                    'sl_use_city_search',
                    __('Show City Pulldown',SLPLUS_PREFIX),
                    __('Displays the city pulldown on the search form. It is built from the unique city names in your location list.',SLPLUS_PREFIX),
                    ''
                    );

            echo CreateCheckboxDiv(
                'sl_use_country_search',
                __('Show Country Pulldown',SLPLUS_PREFIX),
                __('Displays the country pulldown on the search form. It is built from the unique country names in your location list.',SLPLUS_PREFIX),
                ''
                );

            echo CreateCheckboxDiv(
                'slplus_show_state_pd',
                __('Show State Pulldown',SLPLUS_PREFIX),
                __('Displays the state pulldown on the search form. It is built from the unique state names in your location list.',SLPLUS_PREFIX),
                ''
                );


            echo CreateCheckboxDiv(
                '_disable_search',
                __('Hide Find Locations button',SLPLUS_PREFIX),
                __('Remove the "Find Locations" button from the search form.', SLPLUS_PREFIX)
                );

        //-----
        // No Pro Pack
        //
        } else {
            print "<div class='form_entry' style='text-align:right;padding-top:136px;'>Want more?<br/> <a href='http://www.charlestonsw.com/'>Check out our other WordPress offerings.</a></div>";
        }


        do_action('slp_add_search_form_features_setting');

        ?>        
    </div>

    <!-- Tags Section -->
<?php
    global $slplus_plugin;
    $slplus_message = ($slplus_plugin->license->packages['Pro Pack']->isenabled) ?
        __('Thank you for purchasing the <a href="%s">%s</a> premium add-on.',SLPLUS_PREFIX) :
        __('Tag features are available in the <a href="%s">%s</a> premium add-on.',SLPLUS_PREFIX)
?>
    <div class='section_column'>
        <h2><?php _e("Tags", SLPLUS_PREFIX); ?></h2>
        <div class="section_column_content">
            <p><?php printf($slplus_message,$slplus_plugin->purchase_url,'Pro Pack'); ?></p>

<?php
        //----------------------------------------------------------------------
        // Pro Pack Enabled
        //
        if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {
            echo CreateCheckboxDiv(
                '_show_tag_search',
                __('Tag Input',SLPLUS_PREFIX),
                __('Show the tag entry box on the search form.', SLPLUS_PREFIX)
                );


            echo CreateInputDiv(
                    '_tag_search_selections',
                    __('Preselected Tag Searches', SLPLUS_PREFIX),
                    __("Enter a comma (,) separated list of tags to show in the search pulldown, mark the default selection with parenthesis '( )'. This is a default setting that can be overriden on each page within the shortcode.",SLPLUS_PREFIX)
                    );
            
            echo CreateCheckboxDiv(
                '_show_tag_any',
                __('Add "any" to tags pulldown',SLPLUS_PREFIX),
                __('Add an "any" selection on the tag pulldown list thus allowing the user to show all locations in the area, not just those matching a selected tag.', SLPLUS_PREFIX)
                );
        }

        do_action('slp_add_search_form_tag_setting');
?>
        </div>
    </div>

    <!-- Labels Section -->
    <div class='section_column'>                     
        <h2><?php _e("Labels", SLPLUS_PREFIX); ?></h2>
        
        <div class='form_entry'>
            <label for='search_label'><?php _e("Address Input", SLPLUS_PREFIX); ?>:</label>
            <input name='search_label' value='<?php echo get_option('sl_search_label'); ?>'>
            <?php
            echo slp_createhelpdiv('search_label',
                __("Label for search form address entry.", SLPLUS_PREFIX)
                );
            ?>             
        </div>
		
		<div class='form_entry'>
			<label for='sl_name_label'><?php _e("Name Input", SLPLUS_PREFIX); ?>:</label>
			<input name='sl_name_label' value='<?php echo get_option('sl_name_label'); ?>'>
			<?php
				echo slp_createhelpdiv('sl_name_label',
				__("Label for name search form address entry.", SLPLUS_PREFIX)
				);
			?>
		</div>
        
        <?php
        if (function_exists('execute_and_output_plustemplate')) {
            execute_and_output_plustemplate('mapsettings_labels.php');
        }                
        ?>                     

        <div class='form_entry'>
            <label for='sl_radius_label'><?php _e("Radius Dropdown", SLPLUS_PREFIX); ?>:</label>
            <input name='sl_radius_label' value='<?php echo $sl_radius_label; ?>'>
            <?php
            echo slp_createhelpdiv('sl_radius_label',
                __("Label for search form radius pulldown.", SLPLUS_PREFIX)
                );
            ?>              
        </div>                

        <div class='form_entry'>
            <label for='sl_website_label'><?php _e("Website URL", SLPLUS_PREFIX);?>:</label>
            <input name='sl_website_label' value='<?php echo $sl_website_label; ?>'>
            <?php
            echo slp_createhelpdiv('sl_website_label',
                __("Label for website URL in search results.", SLPLUS_PREFIX)
                );
            ?>              
        </div>            

        <div class='form_entry'>
            <label for='sl_instruction_message'><?php _e("Instruction Message", SLPLUS_PREFIX); ?>:</label>
            <input name='sl_instruction_message' value='<?php echo $sl_instruction_message; ?>' size='50'>
            <?php
            echo slp_createhelpdiv('sl_instruction_message',
                __("Instruction text when map is first displayed.", SLPLUS_PREFIX)
                );
            ?>            
        </div>
    </div>





</div>
