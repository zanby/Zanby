{*popup_item*}
<p class="prText2 prTCenter">{t}Are you sure you want to delete this venue?{/t}</p>
<div class="prInnerTop prTCenter">
{if $venue->getType() == 'worldwide'}
	<span class="prIndentLeftSmall">{t var="in_button"}Delete Venue{/t}{linkbutton name=$in_button onclick="xajax_deleteVenueDo("|cat:$venue->getId()|cat:", 'worldwide'); return false;"}</span>
{else}
	<span class="prIndentLeftSmall">{t var="in_button_2"}Delete Venue{/t}{linkbutton name=$in_button_2 onclick="xajax_deleteVenueDo("|cat:$venue->getId()|cat:", 'simple'); return false;"}</span>
{/if}
 <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}