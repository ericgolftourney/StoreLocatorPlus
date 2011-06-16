<?php
/****************************************************************************
 ** file: reporting.php
 **
 ** The reporting system
 ***************************************************************************/
 global $slplus_plugin;
 
 $slpReportSettings = new wpCSL_settings__slplus(
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
 
    $slpReportSettings->add_section(
        array(
                'name'          => __('Report Parameters',SLPLUS_PREFIX),
                'description'   => __('Use these settings to select which data to
                    report on.',SLPLUS_PREFIX),
                'auto'          => true
            )
     );

    // Start of date range to report on
    // default: 30 days ago
    //
    $slpReportStartDate = isset($_POST[SLPLUS_PREFIX.'-start_date']) ?
        $_POST[SLPLUS_PREFIX.'-start_date'] :
        date('Y-m-d',time() - (30 * 24 * 60 * 60));
    $slpReportSettings->add_item(
        'Report Parameters', 
        __('Start Date: ',SLPLUS_PREFIX),   
        'start_date',    
        'text',
        null,
        null,
        null,
        $slpReportStartDate
    ); 
 
    // Start of date range to report on
    // default: today
    //
    $slpReportEndDate = (isset($_POST[SLPLUS_PREFIX.'-end_date'])) ?
        $_POST[SLPLUS_PREFIX.'-end_date'] :
        date('Y-m-d',time());
    $slpReportSettings->add_item(
        'Report Parameters', 
        __('End Date: ',SLPLUS_PREFIX),   
        'end_date',    
        'text',
        null,
        null,
        null,
        $slpReportEndDate
    );     
?>

<?php     
    $slpReportSettings->render_settings_page();

?>
