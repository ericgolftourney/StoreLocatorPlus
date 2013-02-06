<?php
/****************************************************************************
 ** file: core/templates/managelocations_actionbar.php
 **
 ** The action bar for the manage locations page.
 ***************************************************************************/
 
 global $slplus_plugin, $sl_hidden;
 $slplus_plugin->helper->loadPluginData();


 if (get_option('sl_location_table_view') == 'Expanded') {
     $altViewText = __('Switch to normal view?','csa-slplus');
     $viewText = __('Normal View','csa-slplus');
 } else {
     $altViewText = __('Switch to expanded view?','csa-slplus');
     $viewText = __('Expanded View','csa-slplus');
 }

 $actionBoxes = array();
?>
<script type="text/javascript">
function doAction(theAction,thePrompt) {
    if((thePrompt == '') || confirm(thePrompt)){
        LF=document.forms['locationForm'];
        LF.act.value=theAction;
        LF.submit();
    }else{
        return false;
    }
}
</script>
<div id="action_buttons">
    <div id="action_bar_header"><h3><?php print __('Actions and Filters','csa-slplus'); ?></h3></div>
    <div class="boxbar">
<?php

    // Basic Delete Icon
    //
    $actionBoxes['A'][] =
            '<p class="centerbutton">' .
                '<a class="like-a-button" href="#" ' .
                        'onclick="doAction(\'delete\',\''.__('Delete selected?','csa-slplus').'\');" ' .
                        'name="delete_selected">'.__("Delete Selected", 'csa-slplus').
                '</a>'.
            '</p>'
            ;

    // Loop through the action boxes content array
    //
    $actionBoxes = apply_filters('slp_action_boxes',$actionBoxes);
    ksort($actionBoxes);
    foreach ($actionBoxes as $boxNumber => $actionBoxLine) {
        print "<div id='box_$boxNumber' class='actionbox'>";
        foreach ($actionBoxLine as $LineHTML) {
            print $LineHTML;
        }
        print '</div>';
    }

    // Search Locations Button
    //
    print 
        '<div id="search_block" class="searchlocations filterbox">' .
            '<p class="centerbutton">'.
                "<input class='like-a-button' type='submit' ".
                    "value='".__('Search Locations', 'csa-slplus')."'>".
            '</p>'.
            "<input id='searchfor' " .
                "value='".(isset($_REQUEST['searchfor'])?$_REQUEST['searchfor']:'')."' name='searchfor'>" .
            $sl_hidden .
        '</div>'
        ;

    print '<div id="list_options" class="filterbox">';
?>
            <p class="centerbutton"><a class='like-a-button' href='#' onclick="doAction('changeview','<?php echo $altViewText; ?>');"><?php echo $viewText; ?></a></p>
            <?php print __('Show ', 'csa-slplus'); ?>
            <select name='sl_admin_locations_per_page'
               onchange="doAction('locationsPerPage','');">
    <?php
        $opt_arr=array(10,25,50,100,200,300,400,500,1000,2000,4000,5000,10000);
        foreach ($opt_arr as $sl_value) {
            $selected=($slplus_plugin->data['sl_admin_locations_per_page']==$sl_value)? " selected " : "";
            print "<option value='$sl_value' $selected>$sl_value</option>";
        }
    ?>
            </select>
            <?php print __(' locations', 'csa-slplus') . '.';
        print '</div>';

do_action('slp_add_manage_locations_action_box');

print
    '</div>' .
'</div>'
;