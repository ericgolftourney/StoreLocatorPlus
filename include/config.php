<?php



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

