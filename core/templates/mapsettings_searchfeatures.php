<?php
	global $slplus_plugin;

    echo CreateCheckboxDiv(
        '_hide_radius_selections',
        __('Hide radius selection',SLPLUS_PREFIX),
        __('Hides the radius selection from the user, the default radius will be used.', SLPLUS_PREFIX)
        );

    echo CreateCheckboxDiv(
        '_show_search_by_name',
        __('Show search by name box', SLPLUS_PREFIX),
        __('Shows the name search entry box to the user.', SLPLUS_PREFIX)
        );

    echo CreateCheckboxDiv(
        '_hide_address_entry',
        __('Hide address entry box',SLPLUS_PREFIX),
        __('Hides the address entry box from the user.', SLPLUS_PREFIX)
        );

    echo CreateCheckboxDiv(
        '_use_location_sensor',
        __('Use location sensor', SLPLUS_PREFIX),
        __('This turns on the location sensor for your customers so they can easily get accurate results')
    );    

    echo CreateCheckboxDiv(
            'sl_use_city_search',
            __('Show City Pulldown',SLPLUS_PREFIX),
            __('Displays the city pulldown on the search form. It is built from the unique city names in your location list.',SLPLUS_PREFIX),
            ''
            );
    
    echo CreateCheckboxDiv(
        'sl_use_country_search',
        __('Show Country Pulldown',SLPLUS_PREFIX),
        __('Displays the country pulldown on the search form. It is built from the unique country names in your location list.',SLPLUS_PREFIX),
        ''
        );

    echo CreateCheckboxDiv(
        'slplus_show_state_pd',
        __('Show State Pulldown',SLPLUS_PREFIX),
        __('Displays the state pulldown on the search form. It is built from the unique state names in your location list.',SLPLUS_PREFIX),
        ''
        );


    echo CreateCheckboxDiv(
        '_disable_search',
        __('Hide Find Locations button',SLPLUS_PREFIX),
        __('Remove the "Find Locations" button from the search form.', SLPLUS_PREFIX)
        );
