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
        
        /**
         * PUBLIC PROPERTIES & METHODS
         */
        public $parent = null;

        /**
         * The Constructor
         */
        function __construct($params=null) {
        }

        /**
         * Set the parent property to point to the primary plugin object.
         *
         * Returns false if we can't get to the main plugin object.
         *
         * @global wpCSL_plugin__slplus $slplus_plugin
         * @return type boolean true if plugin property is valid
         */
        function setParent() {
            if (!isset($this->parent) || ($this->parent == null)) {
                global $slplus_plugin;
                $this->parent = $slplus_plugin;
            }
            return (isset($this->parent) && ($this->parent != null));
        }
        
        /**
         * method: admin_init()
         *
         * Called when the WordPress admin_init action is processed.
         *
         * Builds the interface elements used by WPCSL-generic for the admin interface.
         *
         */
        function admin_init() {
            if (!$this->setParent()) { return; }
        
            // Already been here?  Get out.
            if (isset($this->parent->settings->sections['How to Use'])) { return; }

            // Update system hook
            // Premium add-ons can use the admin_init hook to utilize this.
            //
            require_once(SLPLUS_PLUGINDIR . '/include/storelocatorplus-updates_class.php');

            // Activation Helpers
            // Updates are handled via WPCSL via namespace style call
            //
            require_once(SLPLUS_PLUGINDIR . '/include/storelocatorplus-activation_class.php');
            $this->parent->Activate = new SLPlus_Activate();
            register_activation_hook( __FILE__, array($this->parent->Activate,'update')); // WP built-in activation call

            // Admin UI Helpers
            //
            require_once(SLPLUS_PLUGINDIR . '/include/storelocatorplus-adminui_class.php');
            $this->parent->AdminUI = new SLPlus_AdminUI();     // Lets invoke this and make it an object
            $this->parent->AdminUI->set_style_as_needed();
            $this->parent->AdminUI->build_basic_admin_settings();
        }

        /**
         * method: admin_menu()
         *
         * Add the Store Locator panel to the admin sidebar.
         *
         */
        function admin_menu() {
            if (
                (!function_exists('add_slplus_roles_and_caps') || current_user_can('manage_slp'))
                )
            {

                global $slplus_plugin;
                
                // The main hook for the menu
                //
                add_menu_page(
                    $slplus_plugin->name,
                    $slplus_plugin->name,
                    'administrator',
                    $slplus_plugin->prefix,
                    array('SLPlus_AdminUI','renderPage_GeneralSettings'),
                    SLPLUS_COREURL . 'images/icon_from_jpg_16x16.png'
                    );

                // Default menu items
                //
                $menuItems = array(
                    array(
                        'label'             => __('General Settings',SLPLUS_PREFIX),
                        'slug'              => 'slp_general_settings',
                        'class'             => 'SLPlus_AdminUI',
                        'function'          => 'renderPage_GeneralSettings'
                    ),
                    array(
                        'label'             => __('Add Locations',SLPLUS_PREFIX),
                        'slug'              => 'slp_add_locations',
                        'class'             => 'SLPlus_AdminUI',
                        'function'          => 'renderPage_AddLocations'
                    ),
                    array(
                        'label' => __('Manage Locations',SLPLUS_PREFIX),
                        'url'   => SLPLUS_COREDIR.'view-locations.php'
                    ),
                    array(
                        'label' => __('Map Settings',SLPLUS_PREFIX),
                        'url'   => SLPLUS_COREDIR.'map-designer.php'
                    )
                );

                // Pro Pack menu items
                //
                if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {
                    $menuItems = array_merge(
                                $menuItems,
                                array(
                                    array(
                                    'label' => __('Reports',SLPLUS_PREFIX),
                                    'url'   => SLPLUS_PLUGINDIR.'reporting.php'
                                    )
                                )
                            );
                }

                // Third party plugin add-ons
                //
                $menuItems = apply_filters('slp_menu_items', $menuItems);

                // Attach Menu Items To Sidebar and Top Nav
                //
                foreach ($menuItems as $menuItem) {

                    // Sidebar connect...
                    //

                    // Using class names (or objects)
                    //
                    if (isset($menuItem['class'])) {
                        add_submenu_page(
                            $slplus_plugin->prefix,
                            $menuItem['label'],
                            $menuItem['label'],
                            'administrator',
                            $menuItem['slug'],
                            array($menuItem['class'],$menuItem['function'])
                            );

                    // Full URL or plain function name
                    //
                    } else {
                        add_submenu_page(
                            $slplus_plugin->prefix,
                            $menuItem['label'],
                            $menuItem['label'],
                            'administrator',
                            $menuItem['url']
                            );
                    }
                }

                // Remove the duplicate menu entry
                //
                remove_submenu_page($slplus_plugin->prefix, $slplus_plugin->prefix);
            }
        }
        
        /**
         * Called when the WordPress init action is processed.
         */
        function init() {
            if (!$this->setParent()) { return; }
            
            //--------------------------------
            // Store Pages Is Licensed
            //
            if ($this->parent->license->packages['Store Pages']->isenabled) {

                // Register Store Pages Custom Type
                register_post_type( 'store_page',
                    array(
                        'labels' => array(
                            'name'              => __( 'Store Pages',SLPLUS_PREFIX ),
                            'singular_name'     => __( 'Store Page', SLPLUS_PREFIX ),
                            'add_new'           => __('Add New Store Page', SLPLUS_PREFIX),
                        ),
                    'public'            => true,
                    'has_archive'       => true,
                    'description'       => __('Store Locator Plus location pages.',SLPLUS_PREFIX),
                    'menu_postion'      => 20,   
                    'menu_icon'         => SLPLUS_COREURL . 'images/icon_from_jpg_16x16.png',
                    'capability_type'   => 'page',
                    'supports'          =>
                        array(
                            'title',
                            'editor',
                            'author',
                            'excerpt',
                            'trackback',
                            'thumbnail',
                            'comments',
                            'revisions',
                            'custom-fields',
                            'page-attributes',
                            'post-formats'
                        ),
                    )
                );                
                
            }

            // Do not texturize our shortcodes
            //
            add_filter('no_texturize_shortcodes',array('SLPlus_UI','no_texturize_shortcodes'));

            // Register Stores Taxonomy
            //
            $this->register_store_taxonomy();
        }

        /**
         * Register the store taxonomy.
         *
         * We need this for Store Pages, Tagalong, and third party plugins.
         *
         */
        function register_store_taxonomy() {
            register_taxonomy(
                    'stores',
                    'store_page',
                    array (
                        'hierarchical'  => true,
                        'labels'        =>
                            array(
                                    'menu_name' => __('Categories',SLPLUS_PREFIX),
                                    'name'      => __('Store Categories',SLPLUS_PREFIX),
                                 )
                        )
                );
        }

        /**
         * Set the starting point for the center of the map.
         *
         * Uses country by default.
         */
        function SetMapCenter() {
            global $slplus_plugin;
            $customAddress = get_option(SLPLUS_PREFIX.'_map_center');
            if (
                (preg_replace('/\W/','',$customAddress) != '') &&
                $slplus_plugin->license->packages['Pro Pack']->isenabled
                ) {
                return str_replace(array("\r\n","\n","\r"),', ',esc_attr($customAddress));
            }
            return esc_attr(get_option('sl_google_map_country','United States'));
        }

        /**
         * This is called whenever the WordPress wp_enqueue_scripts action is called.
         */
        static function wp_enqueue_scripts() {
            global $slplus_plugin;            
            $api_key= (isset($slplus_plugin) && $slplus_plugin->ok_to_show()) ?
                $slplus_plugin->driver_args['api_key'] :
                ''
                ;
            $force_load = (
                        isset($slplus_plugin) ?
                        $slplus_plugin->settings->get_item('force_load_js',true) :
                        false
                    );

            $sl_google_map_domain=get_option('sl_google_map_domain','maps.google.com');
            $sl_map_character_encoding='&oe='.get_option('sl_map_character_encoding','utf8');    

            //------------------------
            // Register our scripts for later enqueue when needed
            //
            //wp_register_script('slplus_functions',SLPLUS_PLUGINURL.'/core/js/functions.js');
            if (get_option(SLPLUS_PREFIX.'-no_google_js','off') != 'on') {
                if (isset($api_key))
                {
                     //todo:character encoding ???
                    //"http://$sl_google_map_domain/maps?file=api&amp;v=2&amp;key=$api_key&amp;sensor=false{$sl_map_character_encoding}"
                    wp_enqueue_script(
                            'google_maps',
                            'http'.(is_ssl()?'s':'').'://'.$sl_google_map_domain.'/maps/api/js?sensor=false&v=3.9&key='.$api_key
                            );
                }
                else {
                    wp_enqueue_script(
                        'google_maps',
                        'http'.(is_ssl()?'s':'').'://'.$sl_google_map_domain.'/maps/api/js?sensor=false&v=3.9'
                    );
                }
            }

            $sslURL =
                (is_ssl()?
                preg_replace('/http:/','https:',SLPLUS_PLUGINURL) :
                SLPLUS_PLUGINURL
                );
            wp_enqueue_script(
                    'csl_script',
                    SLPLUS_PLUGINURL.'/core/js/csl.js',
                    array('jquery'),
                    false,
                    !$force_load
            );

            //--------------------
            // Localize The Script
            //--------------------
            // Prepare some data for JavaScript injection...
            //
            $slplus_home_icon = get_option('sl_map_home_icon');
            $slplus_end_icon  = get_option('sl_map_end_icon');
            $slplus_home_icon_file = str_replace(SLPLUS_ICONURL,SLPLUS_ICONDIR,$slplus_home_icon);
            $slplus_end_icon_file  = str_replace(SLPLUS_ICONURL,SLPLUS_ICONDIR,$slplus_end_icon);
            $slplus_home_size=(function_exists('getimagesize') && file_exists($slplus_home_icon_file))?
                getimagesize($slplus_home_icon_file) :
                array(0 => 20, 1 => 34);
            $slplus_end_size =(function_exists('getimagesize') && file_exists($slplus_end_icon_file)) ?
                getimagesize($slplus_end_icon_file)  :
                array(0 => 20, 1 => 34);

            /** 
             * Results Output String In JavaScript Format
             *
             *              {0} aMarker.name,
             *              {1} parseFloat(aMarker.distance).toFixed(1),
             *              {2} slplus.distance_unit,
             *              {3} street,
             *              {4} street2,
             *              {5} city_state_zip,
             *              {6} thePhone,
             *              {7} theFax,
             *              {8} link,
             *              {9} elink,
             *              {10} slplus.map_domain,
             *              {11} encodeURIComponent(this.address),
             *              {12} encodeURIComponent(address),
             *              {13} slplus.label_directions,
             *              {14} tagInfo,
             *              {15} aMarker.id
             *              {16} aMarker.country
             *              {17} aMarker.hours
             */
            $results_string =
                    '<center>' .
                    '<table width="96%" cellpadding="4px" cellspacing="0" class="searchResultsTable" id="slp_results_table">'  .
                        '<tr class="slp_results_row" id="slp_location_{15}">'  .
                            '<td class="results_row_left_column" id="slp_left_cell_{15}"><span class="location_name">{0}</span><br/>{1} {2}</td>'  .
                            '<td class="results_row_center_column" id="slp_center_cell_{15}">' .
                                '<span class="slp_result_address slp_result_street">{3}</span>'.
                                '<span class="slp_result_address slp_result_street2">{4}</span>' .
                                '<span class="slp_result_address slp_result_citystatezip">{5}</span>' .
                                '<span class="slp_result_address slp_result_country">{16}</span>'.
                                '<span class="slp_result_address slp_result_phone">{6}</span>' .
                                '<span class="slp_result_address slp_result_fax">{7}</span>' .
                            '</td>'   .
                            '<td class="results_row_right_column" id="slp_right_cell_{15}">{8}{9}'  .
                                '<a href="http://{10}' .
                                '/maps?saddr={11}'  .
                                '&daddr={12}'  .
                                '" target="_blank" class="storelocatorlink">{13}</a>{14}</td>'  .
                            '</tr>'  .
                        '</table>'  .
                        '</center>';

            // Lets get some variables into our script
            //
            $scriptData = array(
                'core_url'          => SLPLUS_COREURL,
                'debug_mode'        => (get_option(SLPLUS_PREFIX.'-debugging') == 'on'),
                'disable_scroll'    => (get_option(SLPLUS_PREFIX.'_disable_scrollwheel')==1),
                'disable_dir'       => (get_option(SLPLUS_PREFIX.'_disable_initialdirectory' )==1),
                'distance_unit'     => esc_attr(get_option('sl_distance_unit'),'miles'),
                'load_locations'    => (get_option('sl_load_locations_default')==1),
                'label_directions'  => esc_attr(get_option(SLPLUS_PREFIX.'_label_directions',   'Directions')  ),
                'label_fax'         => esc_attr(get_option(SLPLUS_PREFIX.'_label_fax',          'Fax: ')         ),
                'label_hours'       => esc_attr(get_option(SLPLUS_PREFIX.'_label_hours',        'Hours: ')       ),
                'label_phone'       => esc_attr(get_option(SLPLUS_PREFIX.'_label_phone',        'Phone: ')       ),
                'map_3dcontrol'     => (get_option(SLPLUS_PREFIX.'_disable_largemapcontrol3d')==0),
                'map_country'       => $slplus_plugin->Actions->SetMapCenter(),
                'map_domain'        => get_option('sl_google_map_domain','maps.google.com'),
                'map_home_icon'     => $slplus_home_icon,
                'map_home_sizew'    => $slplus_home_size[0],
                'map_home_sizeh'    => $slplus_home_size[1],
                'map_end_icon'      => $slplus_end_icon,
                'map_end_sizew'     => $slplus_end_size[0],
                'map_end_sizeh'     => $slplus_end_size[1],
                'use_sensor'        => (get_option(SLPLUS_PREFIX."_use_location_sensor",0)==1),
                'map_scalectrl'     => (get_option(SLPLUS_PREFIX.'_disable_scalecontrol')==0),
                'map_type'          => get_option('sl_map_type','roadmap'),
                'map_typectrl'      => (get_option(SLPLUS_PREFIX.'_disable_maptypecontrol')==0),
                'msg_noresults'     => $slplus_plugin->settings->get_item('message_noresultsfound','No results found.','_'),
                'results_string'    => apply_filters('slp_javascript_results_string',$results_string),
                'show_tags'         => (get_option(SLPLUS_PREFIX.'_show_tags')==1),
                'overview_ctrl'     => get_option('sl_map_overview_control',0),
                'use_email_form'    => (get_option(SLPLUS_PREFIX.'_use_email_form',0)==1),
                'use_pages_links'   => ($slplus_plugin->settings->get_item('use_pages_links','off')=='on'),
                'use_same_window'   => ($slplus_plugin->settings->get_item('use_same_window')=='on'),
                'website_label'     => esc_attr(get_option('sl_website_label','Website')),
                'zoom_level'        => get_option('sl_zoom_level',12),
                'zoom_tweak'        => get_option('sl_zoom_tweak',1)
                );
            wp_localize_script('csl_script','slplus',$scriptData);
            wp_localize_script('csl_script','csl_ajax',array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('em')));
        }     
        

        /**
         * This is called whenever the WordPress shutdown action is called.
         */
        function wp_footer() {
            SLPlus_Actions::ManageTheScripts();
		}


        /**
         * Called when the <head> tags are rendered.
         */
        function wp_head() {
            $output = strip_tags($this->parent->settings->get_item('custom_css',''));
            if ($output != '') {
                echo '<!-- SLP Pro Pack Custom CSS -->'."\n".'<style type="text/css">'."\n" . $output . '</style>'."\n\n";
            }
        }

        /**
         * This is called whenever the WordPress shutdown action is called.
         */
        function shutdown() {
            // Safety for themes not using wp_footer
            SLPlus_Actions::ManageTheScripts();
		}

        /**
         * Unload The SLP Scripts If No Shortcode
         */
        function ManageTheScripts() {
            if (!defined('SLPLUS_SCRIPTS_MANAGED') || !SLPLUS_SCRIPTS_MANAGED) {

                // If no shortcode rendered, remove scripts
                //
                if (!defined('SLPLUS_SHORTCODE_RENDERED') || !SLPLUS_SHORTCODE_RENDERED) {
                    wp_dequeue_script('google_maps');
                    wp_deregister_script('google_maps');
                    wp_dequeue_script('csl_script');
                    wp_deregister_script('csl_script');
                }
                define('SLPLUS_SCRIPTS_MANAGED',true);
            }
        }
	}
}
