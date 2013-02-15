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
        public $initialized = false;
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
                $this->plugin = $slplus_plugin;
            }
            return (isset($this->parent) && ($this->parent != null));
        }

        /**
         * Attach and instantiated AdminUI object to the main plugin object.
         *
         * @return boolean - true unless the main plugin is not found
         */
        function attachAdminUI() {
            if (!$this->setParent()) { return false; }
            if (!isset($this->parent->AdminUI) || !is_object($this->parent->AdminUI)) {
                require_once(SLPLUS_PLUGINDIR . '/include/storelocatorplus-adminui_class.php');
                $this->parent->AdminUI = new SLPlus_AdminUI();     // Lets invoke this and make it an object
            }
            return true;
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
            if ($this->initialized)  { return; }            
            $this->initialized = true;

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
            $this->attachAdminUI();
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
            if (!$this->setParent()) { return; }

            if (current_user_can('manage_slp')) {
                $this->attachAdminUI();
                
                // The main hook for the menu
                //
                add_menu_page(
                    $this->parent->name,
                    $this->parent->name,
                    'manage_slp',
                    $this->parent->prefix,
                    array('SLPlus_AdminUI','renderPage_GeneralSettings'),
                    SLPLUS_COREURL . 'images/icon_from_jpg_16x16.png'
                    );

                // Default menu items
                //
                $menuItems = array(
                    array(
                        'label'             => __('General Settings','csa-slplus'),
                        'slug'              => 'slp_general_settings',
                        'class'             => $this->parent->AdminUI,
                        'function'          => 'renderPage_GeneralSettings'
                    ),
                    array(
                        'label'             => __('Add Locations','csa-slplus'),
                        'slug'              => 'slp_add_locations',
                        'class'             => $this->parent->AdminUI,
                        'function'          => 'renderPage_AddLocations'
                    ),
                    array(
                        'label' => __('Manage Locations','csa-slplus'),
                        'slug'              => 'slp_manage_locations',
                        'class'             => $this->parent->AdminUI,
                        'function'          => 'renderPage_ManageLocations'
                    ),
                    array(
                        'label' => __('Map Settings','csa-slplus'),
                        'slug'              => 'slp_map_settings',
                        'class'             => $this->parent->AdminUI,
                        'function'          => 'renderPage_MapSettings'
                    )
                );

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
                            $this->parent->prefix,
                            $menuItem['label'],
                            $menuItem['label'],
                            'manage_slp',
                            $menuItem['slug'],
                            array($menuItem['class'],$menuItem['function'])
                            );

                    // Full URL or plain function name
                    //
                    } else {
                        add_submenu_page(
                            $this->parent->prefix,
                            $menuItem['label'],
                            $menuItem['label'],
                            'manage_slp',
                            $menuItem['url']
                            );
                    }
                }

                // Remove the duplicate menu entry
                //
                remove_submenu_page($this->parent->prefix, $this->parent->prefix);
            }
        }


        /**
         * Retrieves map setting options, whether serialized or not.
         *
         * Simple options (non-serialized) return with a normal get_option() call result.
         *
         * Complex options (serialized) save any fetched result in $this->settingsData.
         * Doing so provides a basic cache so we don't keep hammering the database when
         * getting our map settings.  Legacy code expects a 1:1 relationship for options
         * to settings.   This mechanism ensures on database read/page render for the
         * complex options v. one database read/serialized element.
         *
         * @param string $optionName - the option name
         * @param mixed $default - what the default value should be
         * @return mixed the value of the option as saved in the database
         */
        function getCompoundOption($optionName,$default='') {
            if (!$this->setParent()) { return; }
            $matches = array();
            if (preg_match('/^(.*?)\[(.*?)\]/',$optionName,$matches) === 1) {
                if (!isset($this->parent->mapsettingsData[$matches[1]])) {
                    $this->parent->mapsettingsData[$matches[1]] = get_option($matches[1],$default);
                }
                return 
                    isset($this->parent->mapsettingsData[$matches[1]][$matches[2]]) ?
                    $this->parent->mapsettingsData[$matches[1]][$matches[2]] :
                    ''
                    ;

            } else {
                return $this->parent->helper->getData($optionName,'get_option',array($optionName,$default));
                //return get_option($optionName,$default);
            }
        }
        
        /**
         * Called when the WordPress init action is processed.
         */
        function init() {
            if (!$this->setParent()) { return; }
            
            // Do not texturize our shortcodes
            //
            add_filter('no_texturize_shortcodes',array('SLPlus_UI','no_texturize_shortcodes'));

            /**
             * Register the store taxonomy & page type.
             *
             * This is used in multiple add-on packs.
             *
             */
            if (!taxonomy_exists('stores')) {
                // Store Page Labels
                //
                $storepage_labels =
                    apply_filters(
                        'slp_storepage_labels',
                        array(
                            'name'              => __( 'Store Pages','csa-slplus' ),
                            'singular_name'     => __( 'Store Page', 'csa-slplus' ),
                            'add_new'           => __('Add New Store Page', 'csa-slplus'),
                        )
                    );

                $storepage_features =
                    apply_filters(
                        'slp_storepage_features',
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
                        )
                    );

                $storepage_attributes =
                    apply_filters(
                        'slp_storepage_attributes',
                        array(
                            'labels'            => $storepage_labels,
                            'public'            => false,
                            'has_archive'       => true,
                            'description'       => __('Store Locator Plus location pages.','csa-slplus'),
                            'menu_postion'      => 20,
                            'menu_icon'         => SLPLUS_COREURL . 'images/icon_from_jpg_16x16.png',
                            'show_in_menu'      => current_user_can('manage_slp'),
                            'capability_type'   => 'page',
                            'supports'          => $storepage_features,
                        )
                    );

                // Register Store Pages Custom Type
                register_post_type( 'store_page',$storepage_attributes);

                register_taxonomy(
                        'stores',
                        'store_page',
                        array (
                            'hierarchical'  => true,
                            'labels'        =>
                                array(
                                        'menu_name' => __('Categories','csa-slplus'),
                                        'name'      => __('Store Categories','csa-slplus'),
                                     )
                            )
                    );
            }

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
            $force_load = (
                        isset($slplus_plugin) ?
                        $slplus_plugin->settings->get_item('force_load_js',true) :
                        false
                    );

            //------------------------
            // Register our scripts for later enqueue when needed
            //
            if (get_option(SLPLUS_PREFIX.'-no_google_js','off') != 'on') {
                $api_key  = ((trim($slplus_plugin->driver_args['api_key']) == false)?'':'&key='.$slplus_plugin->driver_args['api_key']);
                $language = '&language='.$slplus_plugin->helper->getData('map_language','get_item',null,'en');
                wp_enqueue_script(
                        'google_maps',
                        'http'.(is_ssl()?'s':'').'://'.get_option('sl_google_map_domain','maps.google.com').'/maps/api/js?sensor=false' . $api_key . $language
                        );
            }

            $sslURL =
                (is_ssl()?
                preg_replace('/http:/','https:',SLPLUS_PLUGINURL) :
                SLPLUS_PLUGINURL
                );
            wp_enqueue_script(
                    'csl_script',
                    $sslURL.'/core/js/csl.js',
                    array('jquery'),
                    false,
                    !$force_load
            );

            $slplus_plugin->UI->localizeCSLScript();
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
