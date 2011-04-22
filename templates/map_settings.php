<?php 
    // $sl_dir = SLPLUS_PLUGINDIR
    
    global $prefix, $text_domain, $update_msg;
    
    global $city_checked, $country_checked, $show_tag_checked, $show_any_checked;
?>

<div class='map_settings'>
    <div class="wrap">
        <h2>
            <?php _e('Map Settings',$text_domain); ?>
            
            <a href='/wp-admin/admin.php?page=<?php echo SLPLUS_PLUGINDIR?>add-locations.php' 
                class='button add-new-h2'><?php _e('Add Locations',$text_domain); ?></a>
            
            <a href='/wp-admin/admin.php?page=<?php echo SLPLUS_PLUGINDIR?>view-locations.php' 
                class='button add-new-h2'><?php _e('Manage Locations',$text_domain); ?></a>            
        </h2>
        
    <?php echo $update_msg?>
    
    <form method='post' name='mapDesigner'>
        <table class='widefat'>
            <thead>
                <tr>
                    <th colspan='2'><?php _e('Search Settings', $text_domain);?></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td class='left_side'>                
                    <h3><?php _e('Search Features', $text_domain);?></h3>
                    <h4><?php _e('Show Search By...',$text_domain);?></h4>
                    
                    <div class='form_entry'>
                        <label for='sl_use_city_search'>
                            <?php _e('City Pulldown', $text_domain); ?>:
                        </label>
                        <input name='sl_use_city_search' 
                            value='1' 
                            type='checkbox' 
                            <?php echo $city_checked?> 
                            >
                    </div>
       
                   <div class='form_entry'>
                        <label for='sl_use_country_search'>
                            <?php _e('Country Pulldown', $text_domain); ?>:
                        </label>
                        <input name='sl_use_country_search' 
                            value='1' 
                            type='checkbox' 
                            <?php echo $country_checked?> 
                            >
                   </div>
                   
                   <div class='form_entry'>
                        <label for='<?php echo $prefix?>_show_tag_search'>
                            <?php _e('Tag Input', $text_domain); ?>:
                        </label>
                        <input name='<?php echo $prefix?>_show_tag_search' 
                            value='1' 
                            type='checkbox' 
                            <?php echo $show_tag_checked?> 
                            >
                   </div>     
                                      
                    <div class='form_entry'>
                        <label for='<?php echo $prefix?>_tag_search_selections'>
                            <?php _e('Preselected Tag Searches', $text_domain); ?>:
                        </label>
                        <input  name='<?php echo $prefix?>_tag_search_selections' 
                            value='<?php print get_option($prefix.'_tag_search_selections'); ?>' 
                            >
                        <span class='input_note'>
                          <?php 
                            _e("Enter a comma (,) separated list of tags to show in the search pulldown, mark the default selection with parenthesis '( )'. This is a default setting that can be overriden on each page within the shortcode.", $text_domain); 
                          ?>
                       </span>
                    </div>        
                    
                   <div class='form_entry'>
                        <label for='<?php echo $prefix?>_show_tag_any'>
                            <?php _e('Show "any" on tag pulldown', $text_domain); ?>:
                        </label>
                        <input name='<?php echo $prefix?>_show_tag_any' 
                            value='1' 
                            type='checkbox' 
                            <?php echo $show_any_checked?> 
                            >
                        <span class='input_note'>
                          <?php 
                            _e("If checked the tag pulldown list will have an 'any' option that returns any location.", $text_domain); 
                          ?>
                       </span>
                   </div>                       
               </td>
            
            
            
