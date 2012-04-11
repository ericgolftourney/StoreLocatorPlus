<?php

/***********************************************************************
* Class: SLPlus_Actions
*
* The Store Locator Plus action hooks and helpers.
*
* The methods in here are normally called from an action hook that is
* called via the WordPress action stack.  
* 
* See http://codex.wordpress.org/Plugin_API/Action_Reference
*
************************************************************************/

if (! class_exists('SLPlus_Actions')) {
    class SLPlus_Actions {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        
        //-----------------------------
        // The Constructor
        //
        function __construct($params) {
        } 
        
        
        //-----------------------------
        // method: activate_plugin()
        // 
        // This is called whenever the plugin is activated.
        //
        // Useful for doing stuff during initial installs or upgrades.
        //
        static function activate_plugin() {
            
            // If theme is not set...
            //            
            if (get_option('csl-slplus-theme') == '') {
                update_option('csl-slplus-theme','csl-slplus');            
            }      
        }
        
        //-----------------------------
        // method: wp_enqueue_scripts()
        // 
        // This is called whenever the WordPress wp_enqueue_scripts action is called.
        //
        static function wp_enqueue_scripts() {
            global $slplus_plugin;
            if (isset($slplus_plugin) && $slplus_plugin->ok_to_show()) {            
                $api_key=$slplus_plugin->driver_args['api_key'];
                $google_map_domain=(get_option('sl_google_map_domain')!="")? 
                        get_option('sl_google_map_domain') : 
                        "maps.google.com";                
                $sl_map_character_encoding=get_option('sl_map_character_encoding');  
                
                //------------------------
                // Register our scripts for later enqueue when needed
                //
                wp_register_script('slplus_functions',SLPLUS_PLUGINURL.'/core/js/functions.js');
                wp_register_script(
                        'google_maps',
                        "http://$google_map_domain/maps?file=api&amp;v=2&amp;key=$api_key&amp;sensor=false{$sl_map_character_encoding}"                        
                        );
                wp_register_script(
                    'slplus_php',
                    SLPLUS_PLUGINURL.'/core/js/store-locator-js.php',
                    array('google_maps')
                    );
                wp_register_script(
                        'slplus_map',
                        SLPLUS_PLUGINURL.'/core/js/store-locator-map.js',
                        array('google_maps','slplus_php')
                        ); 
                
                // Setup Email Form Script If Selected
                //                
                if (get_option(SLPLUS_PREFIX.'_email_form')==1) {
                    wp_register_script(
                            'slplus_map',
                            SLPLUS_PLUGINURL.'/core/js/store-locator-emailform.js',
                            array('google_maps','slplus_php')
                            );                       
                }

                //------------------------
                // Register our styles for later enqueue when needed
                //                
                if (get_option(SLPLUS_PREFIX . '-theme' ) != '') {
                    setup_stylesheet_for_slplus();
                } else {
                    $has_custom_css=(file_exists($sl_upload_path."/custom-css/csl-slplus.css"))? 
                        $sl_upload_base."/custom-css" : 
                        $sl_base; 
                    wp_register_style('slplus_customcss',$has_custom_css.'/core/css/csl-slplus.css');
                }
                $theme=get_option('sl_map_theme');
                if ($theme!="") {
                    wp_register_style('slplus_themecss',$sl_upload_base.'/themes/'.$theme.'/style.css');
                }                                
            }               
        }        
    }
}        
     

