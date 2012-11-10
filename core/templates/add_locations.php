<?php
global $slplus_plugin;
$slplus_plugin->addform = true;
print '<table style="clear:both;"><tr><td class="slp_locationinfoform_cell">';
print $slplus_plugin->AdminUI->createString_LocationInfoForm(array(),'', true);
print '</td></tr></table>';
