<?php
/**
 * Plugin Name: Store Locator Plus : Store Pages
 * Plugin URI: http://www.charlestonsw.com/product/store-locator-plus-store-pages/
 * Description: A premium add-on pack for Store Locator Plus that creates custom pages for your locations.
 * Version: 3.8.15
 * Author: Charleston Software Associates
 * Author URI: http://charlestonsw.com/
 * Requires at least: 3.3
 * Test up to : 3.5.1
 *
 * Text Domain: csa-slp-pages
 * Domain Path: /languages/
 *
 * @package StoreLocatorPlus
 * @subpackage StorePages
 * @category UserInterfaces
 * @author Charleston Software Associates
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// No SLP? Get out...
//
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( !function_exists('is_plugin_active') ||  !is_plugin_active( 'store-locator-le/store-locator-le.php')) {
    return;
}

// If we have not been here before, let's get started...
//
if ( ! class_exists( 'SLPPages' ) ) {

    /**
     * Main SLP Pages Class
     */
    class SLPPages {

        private $dir;
        private $metadata = null;
        private $slug = null;
        private $url;
        private $adminMode = false;
        private $settingsSlug = 'slp_storepages';

        /**
         * Public Properties
         *
         * @property wpCSL_settings__slplus $Settings   the Store Pages settings object (public).
         * @property wpCSL_plugin__slplus   $plugin     the parent wpCSL plugin object
         */
        public $plugin   = null;
        public $Settings = null;

        /**
         * Constructor
         */
        function __construct() {
            $this->url  = plugins_url('',__FILE__);
            $this->dir  = plugin_dir_path(__FILE__);
            $this->slug = plugin_basename(__FILE__);

            // WordPress Actions & Filters
            //
            add_action('admin_menu'                     ,array($this,'admin_menu')                              );

            // SLP Actions & Filters
            //
            add_filter('slp_storepage_attributes'       ,array($this,'modify_storepage_attributes')             );
        }

        //====================================================
        // WordPress Admin Actions
        //====================================================

        /**
         * WordPress admin_init hook for Tagalong.
         */
        function admin_init(){
            
            // WordPress Update Checker - if this plugin is active
            //
            if (is_plugin_active($this->slug)) {
                $this->metadata = get_plugin_data(__FILE__, false, false);
                $this->Updates = new SLPlus_Updates(
                        $this->metadata['Version'],
                        $this->plugin->updater_url,
                        $this->slug
                        );
            }

            // SLP Action Hooks & Filters (admin UI only)
            //
            add_filter('slp_action_boxes'               ,array($this,'manage_locations_actionbar' )             );
            add_filter('slp_manage_location_columns'    ,array($this,'add_manage_locations_columns' )           );
            add_filter('slp_manage_locations_actions'   ,array($this,'add_manage_locations_actionbuttons'),10,2 );
        }

        /**
         * WordPress admin_menu hook.
         */
        function admin_menu(){
            if (!$this->setPlugin()) { return ''; }
            $this->adminMode = true;
            $slugPrefix = 'store-locator-plus_page_';

           // Admin Styles
            //
            add_action(
                    'admin_print_styles-' . 'store-locator-plus_page_'.$this->settingsSlug,
                    array($this,'enqueue_admin_stylesheet')
                    );

            // Admin Actions
            //
            add_action('admin_init' ,
                    array($this,'admin_init')
                    );

            add_filter('slp_menu_items',
                    array($this,'add_menu_items'),
                    90
                    );
        }

        //====================================================
        // Helpers
        //====================================================

        /**
         * Add Store Pages to the SLP universe.
         *
         * @global wpCSL_plugin__slplus $slplus_plugin
         */
        function add_package() {
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


        /**
         * Debug for action hooks.
         * 
         * @param type $tagname
         * @param type $parm1
         * @param type $parm2
         */
        function debug($tagname,$parm1=null,$parm2=null) {
            print "$tagname<br/>\n".
                  "<pre>".print_r($parm1,true)."</pre>".
                  "<pre>".print_r($parm2,true)."</pre>"
                    ;
            die($this->slug . ' debug hooked.');
        }

        /**
         * Enqueue the style sheet when needed.
         */
        function enqueue_admin_stylesheet() {
            wp_enqueue_style('slp_storepages_style');
            wp_enqueue_style($this->plugin->AdminUI->styleHandle);
        }        

        /**
         * Set the plugin property to point to the primary plugin object.
         *
         * Returns false if we can't get to the main plugin object or
         * STORE PAGES IS NOT LICENSED
         *
         * @TODO REMOVE the Store Pages license check when this becomes an independent plugin.
         *
         * @global wpCSL_plugin__slplus $slplus_plugin
         * @return type boolean true if plugin property is valid
         */
        function setPlugin() {
            if (!isset($this->plugin) || ($this->plugin == null)) {
                global $slplus_plugin;
                $this->plugin = $slplus_plugin;
            }
            return (
                isset($this->plugin)    &&
                ($this->plugin != null) &&
                $this->plugin->license->packages['Store Pages']->isenabled
                );
        }


        //====================================================
        // Store Pages Custom Methods
        //====================================================

        /**
         * Add a location action button.
         *
         * @param string $theHTML - the HTML of the original buttons in place
         * @param array $locationValues
         * @return string - the augmented HTML
         */
        function add_manage_locations_actionbuttons($theHTML,$locationValues) {
            if (!$this->setPlugin())                { return $theHTML;  }
            if (!isset($locationValues['sl_id']))   { return $theHTML;  }
            if ($locationValues['sl_id'] < 0)       { return $theHTML;  }

            // Set the URL
            //
            $shortSPurl = preg_replace('/^.*?store_page=/','',$locationValues['sl_pages_url']);
            $locationValues['sl_pages_url'] = "<a href='$locationValues[sl_pages_url]' target='cybersprocket'>$shortSPurl</a>";

            $pageClass = (($locationValues['sl_linked_postid']>0)?'haspage_icon' : 'createpage_icon');
            $pageURL  = preg_replace(
                            '/&createpage=/'.(isset($_GET['createpage'])?$_GET['createpage']:''),
                            '',
                            $_SERVER['REQUEST_URI']
                            ).
                         '&act=createpage'.
                         '&sl_id='.$locationValues['sl_id'].
                         '&slp_pageid='.$locationValues['sl_linked_postid'].
                         '#a'.$locationValues['sl_id']
                    ;
            return $theHTML .
                   "<a  class='action_icon $pageClass' ".
                        "alt='".__('create page','csa-slplus')."' ".
                        "title='".__('create page','csa-slplus')."' ".
                        "href='$pageURL'></a>"
                    ;
        }

        /**
         * Add the Store Pages URL column.
         * 
         * @param array $theColumns - the array of column data/titles
         * @return array - modified columns array
         */
        function add_manage_locations_columns($theColumns) {
            if (!$this->setPlugin()) { return $theColumns; }
            return array_merge($theColumns,
                    array(
                        'sl_pages_url'      => __('Pages URL'          ,'csa-slplus'),
                    )
                );
        }

        /**
         * Add the Store Pages Menu Item
         *
         * @param type $menuItems
         * @return type
         */
        function add_menu_items($menuItems) {
            if (!$this->setPlugin()) { return $menuItems; }
            return array_merge(
                        $menuItems,
                        array(
                            array(
                            'label' => __('Store Pages',SLPLUS_PREFIX),
                            'slug'              => 'slp_storepages',
                            'class'             => $this,
                            'function'          => 'render_SettingsPage'
                            )
                        )
                    );
        }

        /**
         * Create a new store pages page.
         *
         * @global wpCSL_plugin__slplus $slplus_plugin
         * @global type $wpdb
         * @param type $locationID
         * @return type
         */
         function CreatePage($locationID=-1, $keepContent = false, $post_status = 'publish')  {
            if (!$this->setPlugin()) { return ''; }

            // If incorrect location ID get out of here
            //
            if ($locationID < 0) {
                return -1;
            }

            // Get The Store Data
            //
            global $wpdb;
            if ($store=$wpdb->get_row('SELECT * FROM '.$wpdb->prefix."store_locator WHERE sl_id = $locationID", ARRAY_A)) {

                $slpStorePage = get_post($store['sl_linked_postid']);
                if (empty($slpStorePage->ID)) {
                    $store['sl_linked_postid'] = -1;
                }
                
                // Update the row
                //
                $wpdb->update($wpdb->prefix."store_locator", $store, array('sl_id' => $locationID));

                // Prior Post Status
                // If new post, use 'draft' as status
                // otherwise keep the current publication state.
                //
                if ($post_status === 'prior') {
                    $post_status =
                        (empty($slpStorePage->ID))      ?
                        'draft'                         :
                        $slpStorePage->post_status
                        ;
                }


                // Create the page
                //
                $slpNewListing = array(
                    'ID'            => (($store['sl_linked_postid'] > 0)?$store['sl_linked_postid']:''),
                    'post_type'     => 'store_page',
                    'post_status'   => $post_status,
                    'post_title'    => $store['sl_store'],
                    'post_content' =>
                        ($keepContent) ?
                            (empty($slpStorePage->ID) ?
                                '' 
                                : 
                                $slpStorePage->post_content
                            ):
                            $this->CreatePageContent($store)
                    );

                // Apply Third Party Filters
                //
                $slpNewListing = apply_filters('slp_pages_insert_post',$slpNewListing);

                return wp_insert_post($slpNewListing);
             }
         }

         /**
          * Create the content for a Store Page.
          *
          * Creates the content for the page.  If plus pack is installed
          * it uses the plus template file, otherwise we use the hard-coded
          * layout.
          *
          * @param type $store
          * @return string
          */
         function CreatePageContent($store) {
             $content = '';

             // Default Content
             //
             $content .= "<span class='storename'>".$store['sl_store']."</span>\n";
             if ($store['sl_image']         !='') {
                 $content .= '<img class="alignright size-full" title="'.$store['sl_store'].'" src="'.$store['sl_image'].'"/>'."\n";
             }
             if ($store['sl_address']       !='') { $content .= $store['sl_address'] . "\n"; }
             if ($store['sl_address2']      !='') { $content .= $store['sl_address2'] . "\n"; }

             if ($store['sl_city']          !='') {
                $content .= $store['sl_city'];
                if ($store['sl_state'] !='') { $content .= ', '; }
             }
             if ($store['sl_state']         !='') { $content .= $store['sl_state']; }
             if ($store['sl_zip']           !='') { $content .= " ".$store['sl_zip']."\n"; }
             if ($store['sl_country']       !='') { $content .= " ".$store['sl_country']."\n"; }
             if ($store['sl_description']   !='') { $content .= "<h1>Description</h1>\n<p>". html_entity_decode($store['sl_description']) ."</p>\n"; }

             $slpContactInfo = '';
             if ($store['sl_phone'] !='') { $slpContactInfo .= __('Phone: ',SLPLUS_PREFIX).$store['sl_phone'] . "\n"; }
             if ($store['sl_fax'] !='') { $slpContactInfo .= __('Fax: ',SLPLUS_PREFIX).$store['sl_fax'] . "\n"; }
             if ($store['sl_email'] !='') { $slpContactInfo .= '<a href="mailto:'.$store['sl_email'].'">'.$store['sl_email']."</a>\n"; }
             if ($store['sl_url']   !='') { $slpContactInfo .= '<a href="'.$store['sl_url'].'">'.$store['sl_url']."</a>\n"; }
             if ($slpContactInfo    != '') {
                $content .= "<h1>Contact Info</h1>\n<p>".$slpContactInfo."</p>\n";
             }

             return apply_filters('slp_pages_content',$content);
         }

         /**
          * Add Stor Pages action buttons to the action bar
          *
          * @param array $actionBoxes - the existing action boxes, 'A'.. each named array element is an array of HTML strings
          * @return string
          */
         function manage_locations_actionbar($actionBoxes) {
                if (!$this->setPlugin()) { return $actionBoxes; }
                $actionBoxes['C'][] =
                        '<p class="centerbutton">' .
                            "<a class='like-a-button' href='#' "            .
                                    "onclick=\"doAction('createpage','"     .
                                        __('Create Pages?',SLPLUS_PREFIX)   .
                                        "')\" name='createpage_selected'>"  .
                                        __('Create Pages', SLPLUS_PREFIX)   .
                             '</a>'                                         .
                        '</p>'
                ;
                return $actionBoxes;
         }

         /**
          * Modify the default store pages attributes.
          *
          * Basically turns on/off store pages.
          *
          * @param type $attributes
          * @return type
          */
         function modify_storepage_attributes($attributes) {
            if (!$this->setPlugin()) { return $attributes; }
            return array_merge(
                    $attributes,
                    array(
                        'public' => true
                    )
                    );
         }

         // Render the settings page
         //
         function render_SettingsPage() {
            if (!$this->setPlugin()) { return __('Store Pages has not been activated.','csa-slp-pages'); }

            // If we are updating settings...
            //
            if (isset($_REQUEST['action']) && ($_REQUEST['action']==='update')) {
                $this->updateSettings();
            }

            // Setup and render settings page
            //
            $this->Settings = new wpCSL_settings__slplus(
                array(
                        'no_license'        => true,
                        'prefix'            => $this->plugin->prefix,
                        'css_prefix'        => $this->plugin->prefix,
                        'url'               => $this->plugin->url,
                        'name'              => $this->plugin->name . ' - Store Pages',
                        'plugin_url'        => $this->plugin->plugin_url,
                        'render_csl_blocks' => true,
                        'form_action'       => admin_url().'admin.php?page='.$this->settingsSlug
                    )
             );

            //-------------------------
            // Navbar Section
            //-------------------------
            $this->Settings->add_section(
                array(
                    'name'          => 'Navigation',
                    'div_id'        => 'slplus_navbar',
                    'description'   => $this->plugin->AdminUI->create_Navbar(),
                    'is_topmenu'    => true,
                    'auto'          => false,
                    'headerbar'     => false
                )
            );

            //-------------------------
            // General Settings
            //-------------------------
            $sectName = __('General Settings','csa-slp-pages');
            $this->Settings->add_section(
                array(
                        'name'          => $sectName,
                        'description'   => '',
                        'auto'          => true
                    )
             );
            $this->Settings->add_item(
                $sectName,
                __('Pages Replace Websites', 'csa-slp-pages'),
                'use_pages_links',
                'checkbox',
                false,
                __('Use the Store Pages local URL in place of the website URL on the map results list.', 'csa-slp-pages')
            );
            $this->Settings->add_item(
                $sectName,
                __('Prevent New Window', 'csa-slp-pages'),
                'use_same_window',
                'checkbox',
                false,
                __('Prevent Store Pages web links from opening in a new window.', 'csa-slp-pages')
            );

            //------------------------------------------
            // RENDER
            //------------------------------------------
            $this->Settings->render_settings_page();
         }

         /**
          * Update Store Pages settings
          */
         function updateSettings() {
            if (!isset($_REQUEST['page']) || ($_REQUEST['page']!=$this->settingsSlug)) { return; }
            if (!isset($_REQUEST['_wpnonce'])) { return; }
         }
    }

    // Instantiate ourselves as an object
    //
    global$slplus_plugin;
    $slplus_plugin->StorePages = new SLPPages();
}