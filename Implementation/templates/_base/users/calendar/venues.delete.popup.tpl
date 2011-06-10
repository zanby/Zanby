{*popup_item*}
<p class="prText2 prTCenter">{t}Are you sure you want to delete this venue?{/t}</p>
<div class="prInnerTop prTCenter">
{if $venue->getType() == 'worldwide'}
<span class="prIndentLeftSmall">
{t var='button_01'}Delete Venue{/t}
{linkbutton color="blue" name=$button_01 onclick="xajax_deleteVenueDo("|cat:$venue->getId()|cat:", 'worldwide'); return false;"}</span>
{else}
<span class="prIndentLeftSmall">
{t var='button_02'}Delete Venue{/t}
{linkbutton color="blue" name=$button_02 onclick="xajax_deleteVenueDo("|cat:$venue->getId()|cat:", 'simple'); return false;"}</span>
{/if}
 <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}
