<?php
print "<table width='100%' cellpadding='5px' cellspacing='0' style='border:solid silver 1px' id='rightnow' class='widefat'>
<thead><tr>
<td style='/*background-color:#000;*/ width:20%'><input class='button' type='button' value='".__("Delete Selected", $text_domain)."' onclick=\"if(confirm('".__("You sure", $text_domain)."?')){LF=document.forms['locationForm'];LF.act.value='delete';LF.submit();}else{return false;}\"></td>";
print "<td style='/*background-color:#000;*/ width:73%; text-align:right; color:white'>";
print "<strong>".__("Tags", $text_domain)."</strong>&nbsp;<input name='sl_tags'>&nbsp;<input class='button' type='button' value='".__("Tag Selected", $text_domain)."' onclick=\"LF=document.forms['locationForm'];LF.act.value='add_tag';LF.submit();\">&nbsp;<input class='button' type='button' value='".__("Remove Tag From Selected", $text_domain)."' onclick=\"if(confirm('".__("You sure", $text_domain)."?')){LF=document.forms['locationForm'];LF.act.value='remove_tag';LF.submit();}else{return false;}\">";
print "</td></tr></thead></table>
";
