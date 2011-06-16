<?php
/****************************************************************************
 ** file: reporting.php
 **
 ** The reporting system
 ***************************************************************************/
 global $slplus_plugin;
 
 $slp_report_settings = new wpCSL_settings__slplus(
        array(
                'no_license'        => true,
                'prefix'            => $slplus_plugin->prefix,
                'url'               => $slplus_plugin->url,
                'name'              => $slplus_plugin->name . ' Reporting',
                'plugin_url'        => $slplus_plugin->plugin_url,
                'render_csl_blocks' => false,
                'form_action'       => '/wp-admin/admin.php?page='.SLPLUS_PLUGINDIR.'reporting.php',
            )
     ); 
 
    $slp_report_settings->add_section(
        array(
                'name'          => __('Report Parameters',SLPLUS_PREFIX),
                'description'   => __('Use these settings to select which data to
                    report on.',SLPLUS_PREFIX),
                'auto'          => true
            )
     );
 
    $slp_report_settings->add_item(
        'Report Parameters', 
        __('Start Date: ',SLPLUS_PREFIX),   
        'start_date',    
        'text',
        null,
        null,
        null,
        $_POST[SLPLUS_PREFIX.'-start_date']
    ); 
 
    $slp_report_settings->add_item(
        'Report Parameters', 
        __('End Date: ',SLPLUS_PREFIX),   
        'end_date',    
        'text',
        null,
        null,
        null,
        $_POST[SLPLUS_PREFIX.'-end_date']
    );     
?>

<?php     
    $slp_report_settings->render_settings_page(); 
?>
