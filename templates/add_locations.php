<?php
global $text_domain, $sl_upload_path;
ob_start();
include(SLPLUS_PLUGINDIR.'/templates/add_location_address.php');
$content = ob_get_contents();
ob_end_clean();
print $content;
