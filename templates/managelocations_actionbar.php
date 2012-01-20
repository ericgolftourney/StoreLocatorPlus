<?php
/****************************************************************************
 ** file: core/templates/managelocations_actionbar.php
 **
 ** The action bar for the manage locations page.
 ***************************************************************************/
 
 global $slplus_plugin, $hidden;

 if (get_option('sl_location_table_view') == 'Expanded') {
     $altViewText = __('Switch to normal view?',SLPLUS_PREFIX);
     $viewText = __('Normal View',SLPLUS_PREFIX);
 } else {
     $altViewText = __('Switch to expanded view?',SLPLUS_PREFIX);
     $viewText = __('Expanded View',SLPLUS_PREFIX);
 }
?>
<script type="text/javascript">
function doAction(theAction,thePrompt) {
    if(confirm(thePrompt)){
        LF=document.forms['locationForm'];
        LF.act.value=theAction;
        LF.submit();
    }else{
        return false;
    }
}
</script>
<div id="action_buttons">
    <div id="other_actions">
        <ul>
        <li class='like-a-button'><a href="#" onclick="doAction('delete','<?php echo __('Delete selected?',SLPLUS_PREFIX);?>')" name="delete_selected"><?php echo __("Delete Selected", SLPLUS_PREFIX); ?></a></li>
            
            <?php 
            //--------------------------------
            // Plus Version : Show Reports Tab
            //
            if ($slplus_plugin->license->packages['Plus Pack']->isenabled) {      
            }
            ?>    
        </ul>
    </div>
    <div id="tag_block">
        <div id="tag_actions">
            <ul>
                <li class='like-a-button'><a href="#" name="tag_selected"    onclick="doAction('add_tag','<?php echo __('Tag selected?',SLPLUS_PREFIX);?>');"   ><?php echo __('Tag Selected', SLPLUS_PREFIX);?></a></li>
                <li class='like-a-button'><a href="#" name="untag_selected"  onclick="doAction('remove_tag','<?php echo __('Remove tag from selected?',SLPLUS_PREFIX);?>');"><?php echo __('Untag Selected', SLPLUS_PREFIX);?></a></li>
            </ul>
        </div>
        <div id="tagentry">
            <label for="sl_tags"><?php echo __('Tags', SLPLUS_PREFIX); ?></label><input name='sl_tags'>
        </div>        
    </div>
    <div id="search_block" class='searchlocations'>
            <input class='like-a-button' type='submit' value='<?php print __("Search Locations", SLPLUS_PREFIX); ?>'>
            <input id='search-q' value='<?php print (isset($_REQUEST['q'])?$_REQUEST['q']:''); ?>' name='q'>
            <?php print $hidden; ?>
    </div>  
    <div id="list_options" class='viewtype'>
        <a class='like-a-button' href='#' onclick="doAction('changeview','<?php echo $altViewText; ?>');"><?php echo $viewText; ?></a><br/>
    </div>    
    
    
</div>

