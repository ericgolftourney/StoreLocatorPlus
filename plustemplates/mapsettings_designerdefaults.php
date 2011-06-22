<!-- No Initial Directory -->
<div class='form_entry'>
    <label  for='<?php echo SLPLUS_PREFIX; ?>_disable_initialdirectory'><?php _e('Disable Initial Directory',SLPLUS_PREFIX); ?>:</label>
    <input name='<?php echo SLPLUS_PREFIX; ?>_disable_initialdirectory' value='1' type='checkbox'
    <?php
               if (get_option(SLPLUS_PREFIX.'_disable_initialdirectory') ==1) {
                   echo ' checked';
               }
    ?>
    >
    <span class='input_note'><?php _e('Disable the scrollwheel zoom on the maps interface.',SLPLUS_PREFIX); ?></span>
</div>

<!-- Disable ScrollWheel -->
<div class='form_entry'>
    <label  for='<?php echo SLPLUS_PREFIX; ?>_disable_scrollwheel'><?php _e('Disable Scroll Wheel',SLPLUS_PREFIX); ?>:</label>
    <input name='<?php echo SLPLUS_PREFIX; ?>_disable_scrollwheel' value='1' type='checkbox'
    <?php
               if (get_option(SLPLUS_PREFIX.'_disable_scrollwheel') ==1) {
                   echo ' checked';
               }
    ?>
    >
    <span class='input_note'><?php _e('Disable the scrollwheel zoom on the maps interface.',SLPLUS_PREFIX); ?></span>
</div>

<!-- Starting Image -->
<div class='form_entry'>
    <label  for='sl_starting_image'><?php _e("Starting Image",SLPLUS_PREFIX); ?>:</label>
    <input name='sl_starting_image' value='<?php echo get_option('sl_starting_image'); ?>' size='25'><br/>
    <span class='input_note'><?php _e("If set, this image will be displayed until a search is performed.",SLPLUS_PREFIX); ?></span>
</div>

<!-- Email Form -->
<div class='form_entry'>
    <label for='<?php echo SLPLUS_PREFIX; ?>_use_email_form'><?php _e("Use Email Form",SLPLUS_PREFIX); ?>:</label>
    <input name='<?php echo SLPLUS_PREFIX; ?>_email_form' value='1' type='checkbox'
    <?php
        if (get_option(SLPLUS_PREFIX.'_email_form') ==1) {
            echo ' checked';
        }
    ?>
    >
    <span class='input_note'><?php _e("Use email form instead of mailto: link when showing email addresses.",SLPLUS_PREFIX); ?></span>
</div>


