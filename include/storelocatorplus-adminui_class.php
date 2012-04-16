<?php

/***********************************************************************
* Class: SLPlus_AdminUI
*
* The Store Locator Plus admin UI class.
*
* Provides various UI functions when someone is an admin on the WP site.
*
************************************************************************/

if (! class_exists('SLPlus_AdminUI')) {
    class SLPlus_AdminUI {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        
        /*************************************
         * The Constructor
         */
        function __construct($params) {
        } 
        
        /*************************************
         * method: slpRenderCreatePageButton()
         *
         * Render The Create Page Button
         */
        function slpRenderCreatePageButton($locationID=-1) {
            if ($locationID < 0) { return; }
            print "<a   class='action_icon createpage_icon' 
                        alt='".__('create page',SLPLUS_PREFIX)."' 
                        title='".__('create page',SLPLUS_PREFIX)."' 
                        href='".
                            ereg_replace("&createpage=".(isset($_GET['createpage'])?$_GET['createpage']:''), "",$_SERVER['REQUEST_URI']).
                            "&act=createpage&sl_id=$locationID#a$locationID'
                   ></a>";            
        }  
        
        /*****************************************************
         * method: slpCreatePage()
         *
         * Create a new store pages page.
         */
         function slpCreatePage($locationID=-1)  {
            global $slplus_plugin;
            
            // If not licensed or incorrect location ID get out of here
            //
            if (
                !$slplus_plugin->license->packages['Store Pages']->isenabled ||
                ($locationID < 0)
                ) {
                return;
            } 
            
            // Create the page
            //
            print "<pre>\n\n\nCreated page for location: $locationID\n\n\n</pre>";
         }        
    }
}        
     

