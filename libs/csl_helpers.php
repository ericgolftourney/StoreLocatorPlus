<?php
/****************************************************************************
 ** file: csl_helpers.php
 **
 ** Generic helper functions.  May live in WPCSL-Generic soon.
 ***************************************************************************/

/**************************************
 ** function: execute_and_output_template()
 ** 
 ** Executes the included php (or html) file and prints out the results.
 ** Makes for easy include templates that depend on processing logic to be
 ** dumped mid-stream into a WordPress page.  A plugin in a plugin sorta.
 **
 ** Parameters:
 **  $file (string, required) - name of the file in the plugin/templates dir
 **/
function execute_and_output_template($file) {
    $file = SLPLUS_PLUGINDIR.'/templates/'.$file;
    if (file_exists($file)) {
        ob_start();
        include($file);
        $content = ob_get_contents();
        ob_end_clean();
        print $content;
    }
}
