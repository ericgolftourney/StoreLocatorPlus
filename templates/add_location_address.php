<?php ?>
<table>
    <tr>
        <td><div class="add_location_form">
            <label  for='sl_store'><?php _e('Name of Location', $text_domain);?></label>
            <input name='sl_store'><br/>

            <label  for='sl_address'><?php _e('Street - Line 1', $text_domain);?></label>
            <input name='sl_address'><br/>

		    <label  for='sl_address2'><?php _e('Street - Line 2', $text_domain);?></label>
            <input name='sl_address2'><br/>

		    <label  for='sl_city'><?php _e('City, State, ZIP', $text_domain);?></label>
            <input name='sl_city'   style='width: 21.4em; margin-right: 1em;'>
            <input name='sl_state'  style='width: 7em; margin-right: 1em;'>
            <input name='sl_zip'    style='width: 7em;'>
            <br/>

		    <label  for='sl_country'><?php _e('Country', $text_domain);?></label>
            <input name='sl_country'><br/>
            </div>
        </td>
    </tr>
</table>
