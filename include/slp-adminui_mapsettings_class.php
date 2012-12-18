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

        /**
         * Called when this object is created.
         *
         * @param type $params
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
          * Add the map panel to the map settings page on the admin UI.
          *
          * @global type $slpMapSettings
          */
         function map_settings() {
            global $slpMapSettings;
            $slpDescription = get_string_from_phpexec(SLPLUS_COREDIR.'/templates/settings_mapform.php');
            $slpMapSettings->add_section(
                array(
                        'name'          => __('Map',SLPLUS_PREFIX),
                        'description'   => $slpDescription,
                        'auto'          => true
                    )
             );
         }


         /**
          * Create the results settings panel
          *
          * @global type $slpMapSettings - a wpCSL settings panel object
          */
         function results_settings() {
            global $slpMapSettings, $slplus_plugin;
            $slplus_message = ($slplus_plugin->license->packages['Pro Pack']->isenabled) ?
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
                    '<p>'.sprintf($slplus_message,$slplus_plugin->purchase_url,'Pro Pack').'</p>'
                    ;
            $slpDescription .= CreateInputDiv(
                        '_maxreturned',
                        __('Max search results',SLPLUS_PREFIX),
                        __('How many locations does a search return? Default is 25.',SLPLUS_PREFIX)
                        );

            //--------
            // Pro Pack : Search Results Settings
            //
            if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {
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
                        $slplus_plugin->data['iconNotice'] .
                        "<div class='form_entry'>".
                            "<label for='icon'>".__('Home Icon', SLPLUS_PREFIX)."</label>".
                            "<input id='icon' name='icon' dir='rtl' size='45' value='".$slplus_plugin->data['homeicon']."' ".
                                    'onchange="document.getElementById(\'prev\').src=this.value">'.
                            "<img id='prev' src='".$slplus_plugin->data['homeicon']."' align='top'><br/>".
                            $slplus_plugin->data['homeIconPicker'].
                        "</div>".
                        "<div class='form_entry'>".
                            "<label for='icon2'>".__('Destination Icon', SLPLUS_PREFIX)."</label>".
                            "<input id='icon2' name='icon2' dir='rtl' size='45' value='".$slplus_plugin->data['endicon']."' ".
                                'onchange="document.getElementById(\'prev2\').src=this.value">'.
                            "<img id='prev2' src='".$slplus_plugin->data['endicon']."'align='top'><br/>".
                            $slplus_plugin->data['endIconPicker'].
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

            if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {
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
            $slpMapSettings->add_section(
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
         * @global type $slpMapSettings
         */
         function search_form_settings() {
            global $slpMapSettings;
            $slpDescription = get_string_from_phpexec(SLPLUS_COREDIR.'/templates/settings_searchform.php');
            $slpMapSettings->add_section(
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
     

