<?php

/***********************************************************************
* Class: SLPlus_UI
*
* The Store Locator Plus UI class.
*
* Provides various UI functions when someone is surfing the site.
*
************************************************************************/

if (! class_exists('SLPlus_UI')) {
    class SLPlus_UI {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        
        /*************************************
         * The Constructor
         */
        function __construct($params) {
        } 
        
        /*************************************
         * method: slp-render-search-form()
         *
         * Render the search form for the map.
         */
        function slp_render_search_form() {
            echo get_string_from_phpexec(SLPLUS_COREDIR . 'templates/search_form.php');
        }          
    }
}        
     

