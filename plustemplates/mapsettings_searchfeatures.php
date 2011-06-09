<div class='form_entry'>
    <label for='sl_use_country_search'>
        <?php _e('Country Pulldown', SLPLUS_PREFIX); ?>:
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

<div class='form_entry'>
    <label for='<?php echo SLPLUS_PREFIX; ?>_show_tag_search'>
        <?php _e('Tag Input', SLPLUS_PREFIX); ?>:
    </label>
    <input name='<?php echo SLPLUS_PREFIX; ?>_show_tag_search' 
        value='1' 
        type='checkbox' 
        <?php
        if (get_option(SLPLUS_PREFIX.'_show_tag_search') ==1) {
            echo ' checked';
        }                
        ?> 
        >
</div>     

<div class='form_entry'>
    <label for='<?php echo SLPLUS_PREFIX; ?>_tag_search_selections'>
        <?php _e('Preselected Tag Searches', SLPLUS_PREFIX); ?>:
    </label>
    <input  name='<?php echo SLPLUS_PREFIX; ?>_tag_search_selections' 
        value='<?php print get_option(SLPLUS_PREFIX.'_tag_search_selections'); ?>' 
        >
    <span class='input_note'>
      <?php 
        _e("Enter a comma (,) separated list of tags to show in the search pulldown, mark the default selection with parenthesis '( )'. This is a default setting that can be overriden on each page within the shortcode.", SLPLUS_PREFIX); 
      ?>
   </span>
</div>        

<div class='form_entry'>
    <label for='<?php echo SLPLUS_PREFIX; ?>_show_tag_any'>
        <?php _e('Show "any" on tag pulldown', SLPLUS_PREFIX); ?>:
    </label>
    <input name='<?php echo SLPLUS_PREFIX; ?>_show_tag_any' 
        value='1' 
        type='checkbox' 
        <?php
        if (get_option(SLPLUS_PREFIX.'_show_tag_any')    ==1) {
            echo ' checked';
        }                
        ?> 
        >
    <span class='input_note'>
      <?php 
        _e("If checked the tag pulldown list will have an 'any' option that returns any location.", SLPLUS_PREFIX); 
      ?>
   </span>
</div>
