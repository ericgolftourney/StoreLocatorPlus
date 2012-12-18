<?php

/***********************************************************************
* Class: SLPlus_AdminUI_MapSettings
*
* The Store Locator Plus admin UI Map Settings class.
*
* Provides various UI functions when someone is an admin on the WP site.
*
************************************************************************/

if (! class_exists('SLPlus_AdminUI_MapSettings')) {
    class SLPlus_AdminUI_MapSettings {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        public $parent = null;
        public $settings = null;

        /**
         * Called when this object is created.
         *
         * @param type $params
         */
        function __construct($params=null) {
            if (!$this->setParent()) {
                die('could not set parent');
                return;
                }

            $this->settings = new wpCSL_settings__slplus(
                array(
                        'no_license'        => true,
                        'prefix'            => $this->parent->prefix,
                        'url'               => $this->parent->url,
                        'name'              => $this->parent->name . ' - Map Settings',
                        'plugin_url'        => $this->parent->plugin_url,
                        'render_csl_blocks' => false,
                        'form_action'       => SLPLUS_ADMINPAGE.'map-designer.php',
                        'save_text'         => __('Save Settings','csl-slplus')
                    )
             );
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
          * Add the map panel to the map settings page on the admin UI.
          *
          */
         function map_settings() {
            global $slplus_plugin;
            $slpDescription = $slplus_plugin->helper->get_string_from_phpexec(SLPLUS_COREDIR.'/templates/settings_mapform.php');
            $this->settings->add_section(
                array(
                        'name'          => __('Map',SLPLUS_PREFIX),
                        'description'   => $slpDescription,
                        'auto'          => true
                    )
             );
         }

         /**
          *Render the HTML for the map size units pulldown (%,px,em,pt)
          *
          * @param string $unit
          * @param string $input_name
          * @return string HTML for the pulldown
          */
        function render_unit_selector($unit, $input_name) {
            $unit_arr     = array('%','px','em','pt');
            $select_field = "<select name='$input_name'>";
            foreach ($unit_arr as $sl_value) {
                $selected=($sl_value=="$unit")? " selected='selected' " : "" ;
                $select_field.="\n<option value='$sl_value' $selected>$sl_value</option>";
            }
            $select_field.="</select>";
            echo  $select_field;
        }

         /**
          * Return the list of Google map domains.
          * 
          * @return named array - list of domains, key is the name, value is the Google URL
          */
         function get_map_domains() {
             return apply_filters(
                     'slp_map_domains',
                    array(
                        __('United States' )=>'maps.google.com',
                        __('Argentina'     )=>'maps.google.com.ar',
                        __('Australia'     )=>'maps.google.com.au',
                        __('Austria'       )=>'maps.google.at',
                        __('Belgium'       )=>'maps.google.be',
                        __('Brazil'        )=>'maps.google.com.br',
                        __('Canada'        )=>'maps.google.ca',
                        __('Chile'         )=>'maps.google.cl',
                        __('China'         )=>'ditu.google.com',
                        __('Czech Republic')=>'maps.google.cz',
                        __('Denmark'       )=>'maps.google.dk',
                        __('Estonia'       )=> 'maps.google.ee',
                        __('Finland'       )=>'maps.google.fi',
                        __('France'        )=>'maps.google.fr',
                        __('Germany'       )=>'maps.google.de',
                        __('Greece'        )=>'maps.google.gr',
                        __('Hong Kong'     )=>'maps.google.com.hk',
                        __('Hungary'       )=>'maps.google.hu',
                        __('India'         )=>'maps.google.co.in',
                        __('Republic of Ireland')=>'maps.google.ie',
                        __('Italy'         )=>'maps.google.it',
                        __('Japan'         )=>'maps.google.co.jp',
                        __('Liechtenstein' )=>'maps.google.li',
                        __('Mexico'        )=>'maps.google.com.mx',
                        __('Netherlands'   )=>'maps.google.nl',
                        __('New Zealand'   )=>'maps.google.co.nz',
                        __('Norway'        )=>'maps.google.no',
                        __('Poland'        )=>'maps.google.pl',
                        __('Portugal'      )=>'maps.google.pt',
                        __('Russia'        )=>'maps.google.ru',
                        __('Singapore'     )=>'maps.google.com.sg',
                        __('South Africa'  )=>'maps.google.co.za',
                        __('South Korea'   )=>'maps.google.co.kr',
                        __('Spain'         )=>'maps.google.es',
                        __('Sweden'        )=>'maps.google.se',
                        __('Switzerland'   )=>'maps.google.ch',
                        __('Taiwan'        )=>'maps.google.com.tw',
                        __('United Kingdom')=>'maps.google.co.uk',
                        )
                     );
         }

         /**
          * Return the list of Google map character encodings.
          *
          * @return named array - list of encodings, key is the name, value is the Google encoding notation

          */
         function get_map_encodings() {
             return apply_filters(
                     'slp_map_encodings',
                        array(
                        'Default (UTF-8)'=>'utf-8',
                        'Western European (ISO-8859-1)'=>'iso-8859-1',
                        'Western/Central European (ISO-8859-2)'=>'iso-8859-2',
                        'Western/Southern European (ISO-8859-3)'=>'iso-8859-3',
                        'Western European/Baltic Countries (ISO-8859-4)'=>'iso-8859-4',
                        'Russian (Cyrillic)'=>'iso-8859-5',
                        'Arabic (ISO-8859-6)'=>'iso-8859-6',
                        'Greek (ISO-8859-7)'=>'iso-8859-7',
                        'Hebrew (ISO-8859-8)'=>'iso-8859-8',
                        'Western European w/amended Turkish (ISO-8859-9)'=>'iso-8859-9',
                        'Western European w/Nordic characters (ISO-8859-10)'=>'iso-8859-10',
                        'Thai (ISO-8859-11)'=>'iso-8859-11',
                        'Baltic languages & Polish (ISO-8859-13)'=>'iso-8859-13',
                        'Celtic languages (ISO-8859-14)'=>'iso-8859-14',
                        'Japanese (Shift JIS)'=>'shift_jis',
                        'Simplified Chinese (China)(GB 2312)'=>'gb2312',
                        'Traditional Chinese (Taiwan)(Big 5)'=>'big5',
                        'Hong Kong (HKSCS)'=>'hkscs',
                        'Korea (EUS-KR)'=>'eus-kr',
                        )
                    );
         }

         /**
          * Create the results settings panel
          *
          */
         function results_settings() {
            $slplus_message = ($this->parent->license->packages['Pro Pack']->isenabled) ?
                __('',SLPLUS_PREFIX) :
                __('Extended settings are available in the <a href="%s">%s</a> premium add-on.',SLPLUS_PREFIX)
                ;


            // ===== Location Info
            //
            // -- Search Results
            //
            $slpDescription =
                    '<h2>' . __('Location Info',SLPLUS_PREFIX).'</h2>'.
                    '<p class="slp_admin_info" style="clear:both;"><strong>'.__('Search Results',SLPLUS_PREFIX).'</strong></p>' .
                    '<p>'.sprintf($slplus_message,$this->parent->purchase_url,'Pro Pack').'</p>'
                    ;
            $slpDescription .= CreateInputDiv(
                        '_maxreturned',
                        __('Max search results',SLPLUS_PREFIX),
                        __('How many locations does a search return? Default is 25.',SLPLUS_PREFIX)
                        );

            //--------
            // Pro Pack : Search Results Settings
            //
            if ($this->parent->license->packages['Pro Pack']->isenabled) {
                $slpDescription .= CreateCheckboxDiv(
                    '_show_tags',
                    __('Show Tags In Output',SLPLUS_PREFIX),
                    __('Show the tags in the location output table and bubble.', SLPLUS_PREFIX)
                    );

                $slpDescription .= CreateCheckboxDiv(
                    '_use_email_form',
                    __('Use Email Form',SLPLUS_PREFIX),
                    __('Use email form instead of mailto: link when showing email addresses.', SLPLUS_PREFIX)
                    );
            }

            // Filter on Results : Search Output Box
            //
            $slpDescription = apply_filters('slp_add_results_settings',$slpDescription);
            $slpDescription =
                "<div class='section_column'>".
                    "<div class='map_designer_settings'>".
                    $slpDescription .
                    "</div>" .
                "</div>"
                ;

            // ===== Icons
            //
            $slpDescription .=
                "<div class='section_column'>".
                    "<div class='map_designer_settings'>".
                        "<h2>".__('Icons', SLPLUS_PREFIX)."</h2>".
                        $this->parent->data['iconNotice'] .
                        "<div class='form_entry'>".
                            "<label for='icon'>".__('Home Icon', SLPLUS_PREFIX)."</label>".
                            "<input id='icon' name='icon' dir='rtl' size='45' value='".$this->parent->data['homeicon']."' ".
                                    'onchange="document.getElementById(\'prev\').src=this.value">'.
                            "<img id='prev' src='".$this->parent->data['homeicon']."' align='top'><br/>".
                            $this->parent->data['homeIconPicker'].
                        "</div>".
                        "<div class='form_entry'>".
                            "<label for='icon2'>".__('Destination Icon', SLPLUS_PREFIX)."</label>".
                            "<input id='icon2' name='icon2' dir='rtl' size='45' value='".$this->parent->data['endicon']."' ".
                                'onchange="document.getElementById(\'prev2\').src=this.value">'.
                            "<img id='prev2' src='".$this->parent->data['endicon']."'align='top'><br/>".
                            $this->parent->data['endIconPicker'].
                        "</div>".
                    "</div>".
                "</div>"
                ;

            // ===== Labels
            //
            $slpDescription .=
                "<div class='section_column'>" .
                    '<h2>'.__('Labels', 'csl-slplus') . '</h2>' .
                    CreateInputDiv(
                       'sl_website_label',
                       __('Website URL', 'csl-slplus'),
                       __('Search results text for the website link.','csl-slplus'),
                       '',
                       'website'
                       ) .
                   CreateInputDiv(
                       '_label_hours',
                       __('Hours', SLPLUS_PREFIX),
                       __('Hours label.',SLPLUS_PREFIX),
                       SLPLUS_PREFIX,
                       'Hours: '
                       ) .
                   CreateInputDiv(
                       '_label_phone',
                       __('Phone', SLPLUS_PREFIX),
                       __('Phone label.',SLPLUS_PREFIX),
                       SLPLUS_PREFIX,
                       'Phone: '
                       ) .
                   CreateInputDiv(
                       '_label_fax',
                       __('Fax', SLPLUS_PREFIX),
                       __('Fax label.',SLPLUS_PREFIX),
                       SLPLUS_PREFIX,
                       'Fax: '
                       ) .
                   CreateInputDiv(
                       '_label_directions',
                       __('Directions', SLPLUS_PREFIX),
                       __('Directions label.',SLPLUS_PREFIX),
                       SLPLUS_PREFIX,
                       'Directions'
                       ) .
                   CreateInputDiv(
                       'sl_instruction_message',
                       __('Instructions', SLPLUS_PREFIX),
                       __('Search results instructions shown if immediately show locations is not selected.',SLPLUS_PREFIX),
                       '',
                       __('Enter an address or zip code and click the find locations button.',SLPLUS_PREFIX)
                       )
                       ;

            if ($this->parent->license->packages['Pro Pack']->isenabled) {
                $slpDescription .= CreateInputDiv(
                        '_message_noresultsfound',
                        __('No Results Message', SLPLUS_PREFIX),
                        __('No results found message that appears under the map.',SLPLUS_PREFIX),
                        SLPLUS_PREFIX,
                        __('Results not found.',SLPLUS_PREFIX)
                        );
            }
            $slpDescription .= '</div>';


            // Render the results setting
            //
            $this->settings->add_section(
                array(
                        'name'          => __('Results',SLPLUS_PREFIX),
                        'description'   => $slpDescription,
                        'auto'          => true
                    )
             );
         }

        /**
         * Add the search form panel to the map settings page on the admin UI.
         *
         */
         function search_form_settings() {
            global  $sl_city_checked, $sl_country_checked, $sl_show_tag_checked, $sl_show_any_checked,
                $sl_radius_label, $sl_website_label,$sl_the_distance_unit;

            $sl_the_distance_unit[__("Kilometers", SLPLUS_PREFIX)]="km";
            $sl_the_distance_unit[__("Miles", SLPLUS_PREFIX)]="miles";
            $ppFeatureMsg = (!$this->parent->license->packages['Pro Pack']->isenabled ?
                    sprintf(
                            __(' This is a <a href="%s" target="csa">Pro Pack</a> feature.', SLPLUS_PREFIX),
                            $this->parent->purchase_url
                            ) :
                    ''
                 );
            $slplus_message = ($this->parent->license->packages['Pro Pack']->isenabled) ?
                __('',SLPLUS_PREFIX) :
                __('Tag features are available in the <a href="%s">%s</a> premium add-on.',SLPLUS_PREFIX)
                ;

            $slpDescription =
                "<div id='search_settings'>" .
                    "<div class='section_column'>" .
                        "<h2>".__('Features', SLPLUS_PREFIX)."</h2>"
                ;
            $slpDescription .= CreateInputDiv(
                    'sl_map_radii',
                    __('Radii Options', SLPLUS_PREFIX),
                    __('Separate each number with a comma ",". Put parenthesis "( )" around the default.',SLPLUS_PREFIX),
                    '',
                    '10,25,50,100,(200),500'
                    );
            $slpDescription .=
                "<div class='form_entry'>" .
                    "<label for='sl_distance_unit'>".__('Distance Unit', 'csl-slplus').':</label>' .
                        "<select name='sl_distance_unit'>"
                ;

            foreach ($sl_the_distance_unit as $key=>$sl_value) {
                $selected=(get_option('sl_distance_unit')==$sl_value)?" selected " : "";
                $slpDescription .= "<option value='$sl_value' $selected>$key</option>\n";
            }
            $slpDescription .=
                    '</select>'.
                '</div>'
                ;

            $slpDescription .=CreateCheckboxDiv(
                '_hide_radius_selections',
                __('Hide radius selection',SLPLUS_PREFIX),
                __('Hides the radius selection from the user, the default radius will be used.', SLPLUS_PREFIX) . $ppFeatureMsg,
                SLPLUS_PREFIX,
                !$this->parent->license->packages['Pro Pack']->isenabled
                );

            $slpDescription .=CreateCheckboxDiv(
                '_show_search_by_name',
                __('Show search by name box', SLPLUS_PREFIX),
                __('Shows the name search entry box to the user.', SLPLUS_PREFIX) . $ppFeatureMsg,
                SLPLUS_PREFIX,
                !$this->parent->license->packages['Pro Pack']->isenabled

                );

            $slpDescription .=CreateCheckboxDiv(
                '_hide_address_entry',
                __('Hide address entry box',SLPLUS_PREFIX),
                __('Hides the address entry box from the user.', SLPLUS_PREFIX) . $ppFeatureMsg,
                SLPLUS_PREFIX,
                !$this->parent->license->packages['Pro Pack']->isenabled
                );

            $slpDescription .=CreateCheckboxDiv(
                '_use_location_sensor',
                __('Use location sensor', SLPLUS_PREFIX),
                __('This turns on the location sensor (GPS) to set the default search address.  This can be slow to load and customers are prompted whether or not to allow location sensing.', SLPLUS_PREFIX) . $ppFeatureMsg,
                SLPLUS_PREFIX,
                !$this->parent->license->packages['Pro Pack']->isenabled
                );

            $slpDescription .=CreateCheckboxDiv(
                'sl_use_city_search',
                __('Show City Pulldown',SLPLUS_PREFIX),
                __('Displays the city pulldown on the search form. It is built from the unique city names in your location list.',SLPLUS_PREFIX) . $ppFeatureMsg,
                '',
                !$this->parent->license->packages['Pro Pack']->isenabled
                );

            $slpDescription .=CreateCheckboxDiv(
                'sl_use_country_search',
                __('Show Country Pulldown',SLPLUS_PREFIX),
                __('Displays the country pulldown on the search form. It is built from the unique country names in your location list.',SLPLUS_PREFIX) . $ppFeatureMsg,
                '',
                !$this->parent->license->packages['Pro Pack']->isenabled
                );

            $slpDescription .=CreateCheckboxDiv(
                'slplus_show_state_pd',
                __('Show State Pulldown',SLPLUS_PREFIX),
                __('Displays the state pulldown on the search form. It is built from the unique state names in your location list.',SLPLUS_PREFIX) . $ppFeatureMsg,
                '',
                !$this->parent->license->packages['Pro Pack']->isenabled
                );

            $slpDescription .=CreateCheckboxDiv(
                '_disable_search',
                __('Hide Find Locations button',SLPLUS_PREFIX),
                __('Remove the "Find Locations" button from the search form.', SLPLUS_PREFIX) . $ppFeatureMsg,
                SLPLUS_PREFIX,
                !$this->parent->license->packages['Pro Pack']->isenabled
                );

            $slpDescription .=CreateCheckboxDiv(
                '_disable_find_image',
                __('Use Find Location Text Button',SLPLUS_PREFIX),
                __('Use a standard text button for "Find Locations" instead of the provided button images.', SLPLUS_PREFIX) . $ppFeatureMsg,
                SLPLUS_PREFIX
                );

            ob_start();
            do_action('slp_add_search_form_features_setting');
            $slpDescription .= ob_get_clean();

            $slpDescription .= '</div>';

            /**
             * Tags Section
             */
            $slpDescription .= "<div class='section_column'>";
            $slpDescription .= '<h2>'.__('Tags', 'csl-slplus').'</h2>';
            $slpDescription .= '<div class="section_column_content">';
            $slpDescription .= '<p>'.sprintf($slplus_message,$this->parent->purchase_url,'Pro Pack').'</p>';

            //----------------------------------------------------------------------
            // Pro Pack Enabled
            //
            if ($this->parent->license->packages['Pro Pack']->isenabled) {
                $slpDescription .= CreateCheckboxDiv(
                    '_show_tag_search',
                    __('Tag Input',SLPLUS_PREFIX),
                    __('Show the tag entry box on the search form.', SLPLUS_PREFIX)
                    );
                $slpDescription .= CreateInputDiv(
                        '_tag_search_selections',
                        __('Preselected Tag Searches', SLPLUS_PREFIX),
                        __("Enter a comma (,) separated list of tags to show in the search pulldown, mark the default selection with parenthesis '( )'. This is a default setting that can be overriden on each page within the shortcode.",SLPLUS_PREFIX)
                        );

                $slpDescription .= CreateCheckboxDiv(
                    '_show_tag_any',
                    __('Add "any" to tags pulldown',SLPLUS_PREFIX),
                    __('Add an "any" selection on the tag pulldown list thus allowing the user to show all locations in the area, not just those matching a selected tag.', SLPLUS_PREFIX)
                    );
            }


            ob_start();
            do_action('slp_add_search_form_tag_setting');
            $slpDescription .= ob_get_clean();

            $slpDescription .= '</div></div>';

            // Search Form Labels
            //
            $slpDescription .= "<div class='section_column'>" .
                 '<h2>'.__('Labels', 'csl-slplus') . '</h2>' .
                CreateInputDiv(
                    'sl_search_label',
                    __('Address', SLPLUS_PREFIX),
                    __('Search form address label.',SLPLUS_PREFIX),
                    '',
                    'Address / Zip'
                    ) .
                CreateInputDiv(
                    'sl_name_label',
                    __('Name', SLPLUS_PREFIX),
                    __('Search form name label.',SLPLUS_PREFIX),
                    '',
                    'Name'
                    ) .
                CreateInputDiv(
                    'sl_radius_label',
                    __('Radius', SLPLUS_PREFIX),
                    __('Search form radius label.',SLPLUS_PREFIX),
                    '',
                    'Within'
                    )
                ;

            //----------------------------------------------------------------------
            // Pro Pack Enabled
            //
            if ($this->parent->license->packages['Pro Pack']->isenabled) {
                $slpDescription .= CreateInputDiv(
                        '_search_tag_label',
                        __('Tags', 'csl-slplus'),
                        __('Search form label to prefix the tag selector.','csl-slplus')
                        );
                $slpDescription .= CreateInputDiv(
                        '_state_pd_label',
                        __('State Label', 'csl-slplus'),
                        __('Search form label to prefix the state selector.','csl-slplus')
                        );
                $slpDescription .= CreateInputDiv(
                        '_find_button_label',
                        __('Find Button', 'csl-slplus'),
                        __('The label on the find button, if text mode is selected.','csl-slplus'),
                        SLPLUS_PREFIX,
                        __('Find Locations','csl-slplus')
                        );
            }

            ob_start();
            do_action('slp_add_search_form_label_setting');
            $slpDescription .= ob_get_clean();
            
            $slpDescription .=  "</div></div>";

            $this->settings->add_section(
                array(
                        'div_id'        => 'csa_mapsettings_searchform',
                        'name'          => __('Search Form',SLPLUS_PREFIX),
                        'description'   => $slpDescription,
                        'auto'          => true
                    )
             );
         }

    }
}        
     

