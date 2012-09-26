<?php
    global $slplus_plugin;

    echo CreateInputDiv(
            '_maxreturned',
            __('Max search results',SLPLUS_PREFIX),
            __('How many locations does a search return? Default is 25.',SLPLUS_PREFIX)
            );

    echo CreateInputDiv(
            'sl_starting_image',
            __('Starting Image',SLPLUS_PREFIX),
            __('If set, this image will be displayed until a search is performed.',SLPLUS_PREFIX),
            ''
            );
    
    echo CreateTextAreaDiv(
            '_map_center',
            __('Center Map At',SLPLUS_PREFIX),
            __('Enter an address to serve as the initial focus for the map. Default is the center of the country.',SLPLUS_PREFIX),
            ''
            );

    echo CreateCheckboxDiv(
        '_disable_initialdirectory',
        __('Disable Initial Directory',SLPLUS_PREFIX),
        __('Do not display the listings under the map when "immediately show locations" is checked.', SLPLUS_PREFIX)
        );

    echo CreateCheckboxDiv(
        '_show_tags',
        __('Show Tags In Output',SLPLUS_PREFIX),
        __('Show the tags in the location output table and bubble.', SLPLUS_PREFIX)
        );

    echo CreateCheckboxDiv(
        '_use_email_form',
        __('Use Email Form',SLPLUS_PREFIX),
        __('Use email form instead of mailto: link when showing email addresses.', SLPLUS_PREFIX)
        );

    echo '<p class="slp_admin_info"><strong>'.__('Map Settings',SLPLUS_PREFIX).'</strong></p>';

    echo CreateCheckboxDiv(
        'sl_map_overview_control',
        __('Show Map Inset Box',SLPLUS_PREFIX),
        __('When checked the map inset is shown.', SLPLUS_PREFIX),
        ''
        );

    echo CreateCheckboxDiv(
        '_disable_scrollwheel',
        __('Disable Scroll Wheel',SLPLUS_PREFIX),
        __('Disable the scrollwheel zoom on the maps interface.', SLPLUS_PREFIX)
        );

    echo CreateCheckboxDiv(
        '_disable_largemapcontrol3d',
        __('Hide map 3d control',SLPLUS_PREFIX),
        __('Turn the large map 3D control off.', SLPLUS_PREFIX)
        );
    
    echo CreateCheckboxDiv(
        '_disable_scalecontrol',
        __('Hide map scale',SLPLUS_PREFIX),
        __('Turn the map scale off.', SLPLUS_PREFIX)
        );
    
    echo CreateCheckboxDiv(
        '_disable_maptypecontrol',
        __('Hide map type',SLPLUS_PREFIX),
        __('Turn the map type selector off.', SLPLUS_PREFIX)
        );
