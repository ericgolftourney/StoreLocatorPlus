<?php 
    global $prefix, $update_msg,   
        $city_checked, $country_checked, $show_tag_checked, $show_any_checked,
        $sl_radius_label, $sl_website_label,$sl_instruction_message, $slpMapSettings;
?>       
<div id='search_settings'>
    <div class='section_column'>              
            <h2><?php _e('Features', SLPLUS_PREFIX);?></h2>
            
            <div class='form_entry'>
                <label for='sl_use_city_search'>
                    <?php _e('City Pulldown', SLPLUS_PREFIX); ?>:
                </label>
                <input name='sl_use_city_search' 
                    value='1' 
                    type='checkbox' 
                    <?php echo $city_checked?> 
                    >
            </div>
           
        <?php
        if (function_exists('execute_and_output_plustemplate')) {
            execute_and_output_plustemplate('mapsettings_searchfeatures.php');
        } else {
            print "<div class='form_entry' style='text-align:right;padding-top:136px;'>Want more?<br/> <a href='http://www.cybersprocket.com/'>Check out our other WordPress offerings.</a></div>";
        }                    
        ?>                   
    </div>           
    <div class='section_column'>                     
        <h2><?php _e("Labels", SLPLUS_PREFIX); ?></h2>
        
        <div class='form_entry'>
            <label for='search_label'><?php _e("Address Input", SLPLUS_PREFIX); ?>:</label>
            <input name='search_label' value='<?php echo get_option('sl_search_label'); ?>'>
            <span class='input_note'><?php _e("Label for search form address entry.", SLPLUS_PREFIX); ?></span>                    
        </div>
        
        <?php
        if (function_exists('execute_and_output_plustemplate')) {
            execute_and_output_plustemplate('mapsettings_labels.php');
        }                
        ?>                     

        <div class='form_entry'>
            <label for='sl_radius_label'><?php _e("Radius Dropdown", SLPLUS_PREFIX); ?>:</label>
            <input name='sl_radius_label' value='<?php echo $sl_radius_label; ?>'><br/>
            <span class='input_note'><?php _e("Label for search form radius pulldown.", SLPLUS_PREFIX);?></span>                    
        </div>                

        <div class='form_entry'>
            <label for='sl_website_label'><?php _e("Website URL", SLPLUS_PREFIX);?>:</label>
            <input name='sl_website_label' value='<?php echo $sl_website_label; ?>'><br/>
            <span class='input_note'><?php _e("Label for website URL in search results.", SLPLUS_PREFIX);?></span>                    
        </div>            

        <div class='form_entry'>
            <label for='sl_instruction_message'><?php _e("Instruction Message", SLPLUS_PREFIX); ?>:</label>
            <input name='sl_instruction_message' value='<?php echo $sl_instruction_message; ?>' size='50'><br/>
            <span class='input_note'><?php _e("Instruction text when map is first displayed.", SLPLUS_PREFIX);?></span>                    
        </div>
    </div>
</div>
