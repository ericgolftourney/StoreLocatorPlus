<?php
/****************************************************************************
 ** file: reporting.php
 **
 ** The reporting system
 ***************************************************************************/
 
global $slplus_plugin, $wpdb;

// Data Settings
//
$slpQueryTable     = $wpdb->prefix . 'slp_rep_query';
$slpResultsTable   = $wpdb->prefix . 'slp_rep_query_results';
 
// Instantiate the form rendering object
//
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
 
//------------------------------------
// Create The Report Parameters Panel
//  
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
    date('Y-m-d',time()) . ' 23:59:59';
    if (!preg_match('/\d\d:\d\d$/',$slpReportEndDate)) {
        $slpReportEndDate .= ' 23:59:59';
    }
    
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

// How many detail records to report back
// default: 10
//
$slpReportLimit = isset($_POST[SLPLUS_PREFIX.'-report_limit']) ?
    $_POST[SLPLUS_PREFIX.'-report_limit'] :
    '10';
$slpReportSettings->add_item(
    'Report Parameters', 
    __('How many detail records? ',SLPLUS_PREFIX),   
    'report_limit',    
    'text',
    false,
    __('Determines how many detail records are reported. ' .
       'More records take longer to report. '.
       '(Default: 10, recommended maximum: 500)',
       SLPLUS_PREFIX
       ),
    null,
    $slpReportLimit
);

$slpReportSettings->add_item(
    'Report Parameters', 
    '',   
    'runreport',    
    'submit_button',
    null,
    null,
    null,
    __('Run Report',SLPLUS_PREFIX)
);     


//------------------------------------
// The Summary Graph Panel
//  
$slpReportSettings->add_section(
    array(
            'name'          => __('Store Locator Plus Usage',SLPLUS_PREFIX),
            'description'   => '<div id="chart_div"></div>',
            'auto'          => true
        )
 );

// Total results each day
// select 
//      count(*) as TheCount, 
//      sum((select count(*) from wp_slp_rep_query_results RES 
//                  where slp_repq_id = QRY2.slp_repq_id)) as TheResults,
//      DATE(slp_repq_time) as TheDate 
// from wp_slp_rep_query QRY2 group by TheDate;
//
$slpReportQuery = sprintf(
    "select count(*) as QueryCount," . 
        "sum((select count(*) from %s ". 
                    "where slp_repq_id = qry2.slp_repq_id)) as ResultCount," .
        "DATE(slp_repq_time) as TheDate " .        
        "FROM %s qry2 " .
        "WHERE slp_repq_time > '%s' AND " .
        "      slp_repq_time <= '%s' " .       
        "GROUP BY TheDate",
    $slpResultsTable,
    $slpQueryTable,
    $slpReportStartDate,
    $slpReportEndDate
    );
$slpReportDataset = $wpdb->get_results($slpReportQuery);

$slpGoogleChartRows = 0;
$slpGoogleChartData = '';
$slpRepTotalQueries = 0;
$slpRepTotalResults = 0;
foreach ($slpReportDataset as $slpReportDatapoint) {
    $slpGoogleChartData .= sprintf(
        "data.setValue(%d, 0, '%s');".
        "data.setValue(%d, 1, %d);".
        "data.setValue(%d, 2, %d);",
        $slpGoogleChartRows,
        $slpReportDatapoint->TheDate,
        $slpGoogleChartRows,
        $slpReportDatapoint->QueryCount,
        $slpGoogleChartRows,        
        $slpReportDatapoint->ResultCount
        );
    $slpGoogleChartRows++;        
    $slpRepTotalQueries += $slpReportDatapoint->QueryCount;
    $slpRepTotalResults += $slpReportDatapoint->ResultCount;
}    

$slpGoogleChartType = ($slpGoogleChartRows < 2)  ?
    'ColumnChart' :
    'AreaChart';


//------------------------------------
// The Summary Data Panel
//
// Get the total searches in this time span
//
// select count(*) from wp_slp_rep_query 
//      where slp_repq_time > '2011-05-17' and 
//            slp_repq_time <= '2011-06-16 23:59:59';
//
$slpReportQuery = sprintf(
    "SELECT count(*) FROM %s " .
        "WHERE slp_repq_time > '%s' AND " .
        "      slp_repq_time <= '%s' ",
    $slpQueryTable,
    $slpReportStartDate,
    $slpReportEndDate
    );
$slpReportDatapoint = $wpdb->get_var($slpReportQuery);

$slpSectionDesc = sprintf(
    '<div class="reportline total">' .
        __('Total searches: <strong>%s</strong>', SLPLUS_PREFIX). "<br/>" . 
        __('Total results: <strong>%s</strong>', SLPLUS_PREFIX). 
    '</div>',
     $slpRepTotalQueries,
     $slpRepTotalResults
    );    

$slpReportSettings->add_section(
    array(
            'name'          => __('Summary',SLPLUS_PREFIX),
            'description'   => $slpSectionDesc,
            'auto'          => true
        )
 );


//------------------------------------
// The Details Data Panel
//

//....
//
// What are the top addresses searched?
//
// SELECT slp_repq_address,count(*) as QueryCount 
//      FROM wp_slp_rep_query 
//      WHERE slp_repq_time > '%s' AND slp_repq_time <= '%s'
//      GROUP BY slp_repq_address 
//      ORDER BY QueryCount DESC;
//
//
$slpReportQuery = sprintf(
    "SELECT slp_repq_address, count(*)  as QueryCount FROM %s " .
        "WHERE slp_repq_time > '%s' AND " .
        "      slp_repq_time <= '%s' " .
        "GROUP BY slp_repq_address ".
        "ORDER BY QueryCount DESC " .
        "LIMIT %s"
        ,
    $slpQueryTable,
    $slpReportStartDate,
    $slpReportEndDate,
    $slpReportLimit
    );
$slpReportDataset = $wpdb->get_results($slpReportQuery);

$slpSectionDesc = 
    '<div id="rb_details" class="reportblock">' .
        '<h2>' . sprintf(__('Top %s Addresses Searched', SLPLUS_PREFIX),$slpReportLimit) . '</h2>' .
        '<table>' .
            '<thead>' .
                '<tr>' .
                    '<th>' . __('Address',SLPLUS_PREFIX)    . '</th>' .
                    '<th>' . __('Total',SLPLUS_PREFIX)      . '</th>' .
                '</tr>' .
            '</thead>' .
            '<tbody>'
            ;
                
foreach ($slpReportDataset as $slpReportDatapoint) {
    $slpSectionDesc .= sprintf(
        '<tr>' .
            '<td>%s</td>'.           
            '<td class="alignright">%s</td>'.           
        '</tr>'
        ,
         $slpReportDatapoint->slp_repq_address,
         $slpReportDatapoint->QueryCount
        );    
}

$slpSectionDesc .=
            '</tbody>' .
        '</table>'.
    '</div>';


$slpReportSettings->add_section(
    array(
            'name'          => __('Details',SLPLUS_PREFIX),
            'description'   => $slpSectionDesc,
            'auto'          => true
        )
 );

?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'Queries');
        data.addColumn('number', 'Results');
        data.addRows(<?php echo $slpGoogleChartRows; ?>);
        <?php echo $slpGoogleChartData; ?>
        var chart = new google.visualization.<?php echo $slpGoogleChartType; ?>(document.getElementById('chart_div'));
        chart.draw(data, {width: 800, height: 400, pointSize: 4});
      }
    </script>


<?php

//------------------------------------
// Render It 
//
$slpReportSettings->render_settings_page();

