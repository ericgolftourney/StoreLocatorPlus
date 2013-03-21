/*****************************************************************
 * file: reporting.js
 *
 *****************************************************************/

jQuery(document).ready(
    function($) {
        // Make tables sortable
         var tstts = $("#topsearches_table").tablesorter( {sortList: [[1,1]]} );
         var trtts = $("#topresults_table").tablesorter( {sortList: [[5,1]]} );

         jQuery('#export_results').click(
            function(e) {
               var data = {
                  action: 'slp_download_report_csv',
                  filename: 'topresults',
                  query: jQuery("[name=topresults]").val(),
                  sort: trtts[0].config.sortList.toString(),
                  all: jQuery("[name=export_all]").is(':checked')
               };
               jQuery.post(ajaxurl, data, function(response) { alert('Got server answer:'+response); });
            }
         );

        // Export Results Button Click
        //
        jQuery("#export_results_OFFLINE").click(
            function(e) {
                jQuery('<form action="'+slp_pro.plugin_url+'/slp-pro/downloadcsv.php" method="post">'+
                        '<input type="hidden" name="filename" value="topresults">' +
                        '<input type="hidden" name="query" value="' + jQuery("[name=topresults]").val() + '">' +
                        '<input type="hidden" name="sort"  value="' + trtts[0].config.sortList.toString() + '">' +
                        '<input type="hidden" name="all"   value="' + jQuery("[name=export_all]").is(':checked') + '">' +
                        '</form>'
                        ).appendTo('body').submit().remove();
            }
        );

        // Export Searches Button Click
        //
        jQuery("#export_searches").click(
            function(e) {
                jQuery('<form action="'+slp_pro.plugin_url+'/slp-pro/downloadcsv.php" method="post">'+
                        '<input type="hidden" name="filename" value="topsearches">' +
                        '<input type="hidden" name="query" value="' + jQuery("[name=topsearches]").val() + '">' +
                        '<input type="hidden" name="sort"  value="' + tstts[0].config.sortList.toString() + '">' +
                        '<input type="hidden" name="all"   value="' + jQuery("[name=export_all]").is(':checked') + '">' +
                        '</form>'
                        ).appendTo('body').submit().remove();
            }
        );

    }
);
