<?php 
    global $value; 
?>
<table>
    <tr>
        <td><div class="add_location_form">
            <label  for='store-<?=$value[sl_id]?>'><?php _e('Name of Location', $text_domain);?></label>
            <input name='store-<?=$value[sl_id]?>' value='<?=$value[sl_store]?>'><br/>

            <label  for='address-<?=$value[sl_id]?>'><?php _e('Street - Line 1', $text_domain);?></label>
            <input name='address-<?=$value[sl_id]?>' value='<?=$value[sl_address]?>'><br/>

		    <label  for='address2-<?=$value[sl_id]?>'><?php _e('Street - Line 2', $text_domain);?></label>
            <input name='address2-<?=$value[sl_id]?>' value='<?=$value[sl_address2]?>'><br/>

		    <label  for='city-<?=$value[sl_id]?>'><?php _e('City, State, ZIP', $text_domain);?></label>
            <input name='city-<?=$value[sl_id]?>'    value='<?=$value[sl_city]?>'     style='width: 21.4em; margin-right: 1em;'>
            <input name='state-<?=$value[sl_id]?>'   value='<?=$value[sl_state]?>'    style='width: 7em; margin-right: 1em;'>
            <input name='zip-<?=$value[sl_id]?>'     value='<?=$value[sl_zip]?>'      style='width: 7em;'><br/>

		    <label  for='country-<?=$value[sl_id]?>'><?php _e('Country', $text_domain);?></label>
            <input name='country-<?=$value[sl_id]?>' value='<?=$value[sl_country]?>'  style='width: 40em;'><br/>
            </div>
        </td>
    </tr>
</table>
