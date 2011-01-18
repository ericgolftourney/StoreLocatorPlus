<?php
  global $search_label, $width, $height, $width_units, $height_units, $hide,
      $sl_radius, $sl_radius_label, $text_domain, $r_options, $button_style,
      $sl_instruction_message, $cs_options, $country_options;
?>
<div id='sl_div'>
  <form onsubmit='searchLocations(); return false;' id='searchForm' action=''>
    <table border='0' cellpadding='3px' class='sl_header'><tr>
	<td valign='top'>
	    <div id='address_search'>

              
            <?php if ($cs_options != '') { ?>
            <div id='addy_in_city'>
                <select id='addressInput2' 
                    onchange='aI=document.getElementById("searchForm").addressInput;if(this.value!=""){oldvalue=aI.value;aI.value=this.value;}else{aI.value=oldvalue;}'>
                    <option value=''>--Search By City--</option>
                    <?=$cs_options?>
                </select>
            </div>
            <?php } ?>
            
            
            <?php if ($country_options != '') { ?>
            <div id='addy_in_country'>
                <select id='addressInput3' onchange='aI=document.getElementById("searchForm").addressInput;if(this.value!=""){oldvalue=aI.value;aI.value=this.value;}else{aI.value=oldvalue;}'>
                <option value=''>--Search By Country--</option>
                <?=$country_options?>
                </select>
            </div>
            <?php } ?>

            <div id='addy_in_address'>
                <label for="addressInput"><?=$search_label?></label>
                <input type='text' id='addressInput' size='50' />
           </div>
            
	        <div id='addy_in_radius'>
	            <label for='radiusSelect'><?php _e($sl_radius_label, $text_domain);?></label>
	            <select id='radiusSelect'><?=$r_options?></select>
            </div>
            
            <div id='radius_in_submit'>
                <input <?=$button_style?> value='Search Locations' id='addressSubmit'/>
            </div>
        </div>
	  </td>
	</tr></table>
	<table width='100%' cellspacing='0px' cellpadding='0px'> 
     <tr>
        <td width='100%' valign='top'>
<?php
$sl_starting_image=get_option('sl_starting_image');
if ($sl_starting_image != '') {    
?>
            <div id='map_box_image'>      
                <img src='<?php echo SLPLUS_PLUGINURL."$sl_starting_image"; ?>'>
            </div>
            <div id='map_box_map'>
<?php
}
?>
                <div id='map' style='width:<?=$width?><?=$width_units?>; height:<?=$height?><?=$height_units?>'></div>
                <table cellpadding='0px' class='sl_footer' width='<?=$width?><?=$width_units?>;' <?=$hide?>>
                <tr>
                    <td class='sl_footer_left_column'>
                        <a href='http://www.cybersprocket.com/products/store-locator-plus/' target='_blank'>Store Locator Plus</a>
                    </td>
                    <td class='sl_footer_right_column'>
                        <a href='http://www.cybersprocket.com' target='_blank' title='by Cyber Sprocket Labs'>by Cyber Sprocket Labs</a>
                    </td>
                </tr>                
                </table>
<?php
if ($sl_starting_image != '') {    
?>
            </div>
<?php
}
?>
		</td>
      </tr>
	  <tr id='cm_mapTR'>
        <td width='' valign='top' id='map_sidebar_td'>
            <div id='map_sidebar' style='width:<?=$width?><?=$width_units?>;'>
                <div class='text_below_map'><?=$sl_instruction_message?></div>
            </div>
        </td>
    </tr>
  </table></form>
<p><script type="text/javascript">if (document.getElementById("map")){setTimeout("sl_load()",1000);}</script></p>
</div>


