<?php
	global $slplus_plugin;
	
    echo CreateCheckboxDiv(
        '_hide_radius_selections',
        __('Hide radius selection',SLPLUS_PREFIX),
        __('Hides the radius selection from the user, the default radius will be used.', SLPLUS_PREFIX)
        );
    
    echo CreateCheckboxDiv(
        '_hide_address_entry',
        __('Hide address entry box',SLPLUS_PREFIX),
        __('Hides the address entry box from the user.', SLPLUS_PREFIX)
        );
	
	if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {
		echo CreateCheckboxDiv(
			'_show_search_by_name',
			__('Show the search by name box', SLPLUS_PREFIX),
			__('Shows the name search entry box to the user.', SLPLUS_PREFIX)
			);
	}
    
    echo CreateCheckboxDiv(
        '_disable_search',
        __('Disable search',SLPLUS_PREFIX),
        __('This makes the search form non-interactive.  Typically used with the immediately show locations feature with a smaller listing set.', SLPLUS_PREFIX)
        );            

    //----------------------------------------------------------------------
    // Pro Pack Enabled
    //
    if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {                
?>

<div class='form_entry'>
    <label for='slplus_show_state_pd'>
        <?php _e('Show State Pulldown', SLPLUS_PREFIX); ?>:
    </label>
    <input name='slplus_show_state_pd' 
        value='1' 
        type='checkbox' 
        <?php
        if (get_option('slplus_show_state_pd') ==1) {
            echo ' checked';
        }                
        ?> 
        >
</div>


<div class='form_entry'>
    <label for='sl_use_country_search'>
        <?php _e('Show Country Pulldown', SLPLUS_PREFIX); ?>:
    </label>
    <input name='sl_use_country_search' 
        value='1' 
        type='checkbox' 
        <?php
        if (get_option('sl_use_country_search') ==1) {
            echo ' checked';
        }                
        ?> 
        >
</div>

<?php
    if ($slplus_plugin->license->packages['Pro Pack']->active_version >= 3001000) {
    var_dump(get_option(SLPLUS_PREFIX.'_show_tag_search'));
    ?>
    <div class='form_entry'>
        <label for='<?php echo SLPLUS_PREFIX; ?>_show_tag_search'><?php _e('Tag Search Mode', SLPLUS_PREFIX);?>:</label>
            <select name='<?php echo SLPLUS_PREFIX; ?>_show_tag_search'>
                <option value='0'<?php echo (get_option(SLPLUS_PREFIX.'_show_tag_search')==0)?" selected >" : ">"; _e('No Tag Search', SLPLUS_PREFIX); ?></option>
                <option value='1'<?PHP echo (get_option(SLPLUS_PREFIX.'_show_tag_search')==1)?" selected >" : ">"; _e('Text Search Mode', SLPLUS_PREFIX); ?></option>
                <option value='2'<?PHP echo (get_option(SLPLUS_PREFIX.'_show_tag_search')==2)?" selected >" : ">"; _e('Image Bar Search', SLPLUS_PREFIX); ?></option>
            </select>
            <?php  
        echo slp_createhelpdiv(SLPLUS_PREFIX.'_show_tag_search',
        __("<strong>No Tag Search:</strong> Does not display the tag search to the end user.<br><strong>Text Search Mode:</strong> Uses the old style tag search to display using a text box to display a search by tag, or if you enter a comma separated list below, it will create a drop down to choose from.<br><strong>Image Bar Search:</strong> Using this mode, ensure you enter tags to display in the text box below.", SLPLUS_PREFIX)
        );
        ?>
    </div>
    <?php
    }
    else
    // Use the old way if they haven't updated yet
    {
        echo CreateCheckboxDiv('_show_tag_search', __('Tag Input', SLPLUS_PREFIX), __("Show the tag entry box on the search form", SLPLUS_PREFIX));
    }
?>

<div class='form_entry'>
    <label for='<?php echo SLPLUS_PREFIX; ?>_tag_search_selections'>
        <?php _e('Preselected Tag Searches', SLPLUS_PREFIX); ?>:
    </label>
    <input  name='<?php echo SLPLUS_PREFIX; ?>_tag_search_selections' 
        value='<?php print get_option(SLPLUS_PREFIX.'_tag_search_selections'); ?>' 
        >
    <?php
    echo slp_createhelpdiv('tag_search_selections',
        __("Enter a comma (,) separated list of tags to show in the search pulldown, mark the default selection with parenthesis '( )'. This is a default setting that can be overriden on each page within the shortcode.",SLPLUS_PREFIX)
        );
    ?>      
</div>        


<?php
    echo CreateCheckboxDiv(
        '_show_tag_any',
        __('Add "any" to tags pulldown',SLPLUS_PREFIX),
        __('Add an "any" selection on the tag pulldown list thus allowing the user to show all locations in the area, not just those matching a selected tag.', SLPLUS_PREFIX)
        );
    }    
?>

