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
    }    
?>

