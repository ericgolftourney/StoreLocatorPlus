<?php

/**
 * The plugin object.
 * 
 * @var SLPlus $slplus_plugin
 */
global $slplus_plugin;

global $slp_SearchDivs;

//------------------------------------------------
// Show State Pulldown Is Enabled
//
$slplus_state_options   = (isset($slplus_plugin->ProPack) ? $slplus_plugin->ProPack->create_state_pd()   : '');
if ($slplus_state_options != '') {
ob_start();
?>
<div id='addy_in_state'>
    <label for='addressInputState'><?php 
        print get_option(SLPLUS_PREFIX.'_state_pd_label');
        ?></label>
    <select id='addressInputState' onchange='aI=document.getElementById("searchForm").addressInput;if(this.value!=""){oldvalue=aI.value;aI.value=this.value;}else{aI.value=oldvalue;}'>
        <option value=''><?php print get_option(SLPLUS_PREFIX.'_search_by_state_pd_label',__('--Search By State--','csa-slplus')); ?></option>
        <?php echo $slplus_state_options?>
    </select>
</div>

<?php
  global $slp_thishtml_20;
  $slp_thishtml_20 = ob_get_clean();
  add_filter('slp_search_form_divs',array($slp_SearchDivs,'buildDiv20'),20);
}
