<?php


/**************************************
 ** function: configure_slplus_propack
 **
 ** Configure the Pro Pack.
 **/
function configure_slplus_propack() {
    global $slplus_plugin;
   
    // Setup metadata
    //
    $myPurl = 'http://www.charlestonsw.com/product/store-locator-plus/';
    $slplus_plugin->license->add_licensed_package(
            array(
                'name'              => 'Pro Pack',
                'help_text'         => 'A variety of enhancements are provided with this package.  ' .
                                       'See the <a href="'.$myPurl.'" target="newinfo">product page</a> for details.  If you purchased this add-on ' .
                                       'come back to this page to enter the license key to activate the new features.',
                'sku'               => 'SLPLUS-PRO',
                'paypal_button_id'  => '59YT3GAJ7W922',
                'paypal_upgrade_button_id' => '59YT3GAJ7W922',
                'purchase_url'      => $myPurl
            )
        );
    
    // Enable Features Is Licensed
    //
    if ($slplus_plugin->license->packages['Pro Pack']->isenabled_after_forcing_recheck()) {
        
             //--------------------------------
             // Enable Themes
             //
             $slplus_plugin->themes_enabled = true;
             $slplus_plugin->themes->css_dir = SLPLUS_PLUGINDIR . 'css/';
    }        
}


/**************************************
 ** function: configure_slplus_storepages
 **
 ** Configure Store Pages.
 **/
function configure_slplus_storepages() {
    global $slplus_plugin;
   
    // Setup metadata
    //
    $myPurl = 'http://www.charlestonsw.com/product/store-locator-plus-store-pages/';
    $slplus_plugin->license->add_licensed_package(
            array(
                'name'              => 'Store Pages',
                'help_text'         => 'Create individual WordPress pages from your locations data. Great for SEO.  ' .
                                       'See the <a href="'.$myPurl.'" target="newinfo">product page</a> for details.  If you purchased this add-on ' .
                                       'come back to this page to enter the license key to activate the new features.',
                'sku'               => 'SLPLUS-PAGES',
                'paypal_button_id'  => 'CT449P2ZH454E',
                'paypal_upgrade_button_id' => 'CT449P2ZH454E',
                'purchase_url'      => $myPurl
            )
        );
}

