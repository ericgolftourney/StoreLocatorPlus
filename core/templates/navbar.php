<?php
/****************************************************************************
 ** file: core/templates/navbar.php
 **
 ** The top Store Locator Settings navigation bar.
 ***************************************************************************/

//    $slplus_plugin->AdminUI->old_add_navbar_item('Settings: General', 'options-general.php?page=csl-slplus-options',admin_url())

// Put all SLP sidebar nav items in main navbar
//
global $submenu, $slplus_plugin;
$content =
    '<script src="'.SLPLUS_COREURL.'js/functions.js"></script>'.
    '<ul>';

// Loop through all SLP sidebar menu items on admin page
//
foreach ($submenu['store-locator-le/core/add-locations.php'] as $slp_menu_item) {

    // Skip the main sidebar header link
    //
    if (!isset($slp_menu_item[4])) {

        //--------------------------------------------
        // Check for Pro Pack, if not enabled skip:
        //  - Show Reports Tab
        //
        if (
                (!$slplus_plugin->license->packages['Pro Pack']->isenabled) &&
                ($slp_menu_item[0] == __('Reports',SLPLUS_PREFIX))
            ){
            continue;
        }

        // Create top menu item
        //
        $content .= apply_filters(
                'slp_navbar_item_tweak',
                '<a href="'.menu_page_url( $slp_menu_item[2], false ).'">'.
                    "<li class='like-a-button'>$slp_menu_item[0]</li>".
                '</a>'
                );
    }
}
$content .= apply_filters('slp_navbar_item','');
$content .='</ul>';
echo apply_filters('slp_navbar',$content);


