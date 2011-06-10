{*popup_item*}
<p>
	{if $mode == 'ROW'}{t var="nameOfAction"}Cancel Event{/t}
	{elseif $mode == 'FUTURE'}{t var="nameOfAction"}Cancel Future Events{/t} 
	{elseif $mode == 'COPY'}{t var="nameOfAction"}Cancel Current Event{/t}
	{/if}
	{t}{tparam value=$nameOfAction}Clicking '%s' below will remove the event and all associated data from your calendar and all calendars containing the event.{/t}
</p>
<div class="prTCenter prInnerTop">
	{if $mode == 'ROW'}
		<span class="prIndentLeftSmall">{t var="in_button"}Cancel Event{/t}{linkbutton name=$in_button onclick="xajax_doCancelEvent('$mode', $event_id, $uid, '$view', $year, $month, $day, 1); return false;"}</span>
	{elseif $mode == 'FUTURE'}
		<span class="prIndentLeftSmall">{t var="in_button_2"}Cancel Future Events{/t}{linkbutton name=$in_button_2 onclick="xajax_doCancelEvent('$mode', $event_id, $uid, '$view', $year, $month, $day, 1); return false;"}</span>
	{elseif $mode == 'COPY'}
		<span class="prIndentLeftSmall">{t var="in_button_3"}Cancel Current Event{/t}{linkbutton name=$in_button_3 onclick="xajax_doCancelEvent('$mode', $event_id, $uid, '$view', $year, $month, $day, 1); return false;"}</span>
	{/if}
	{t var="in_button_4"}Go Back{/t}{linkbutton name=$in_button_4 onclick="popup_window.close(); return false;"}
</div>
{*popup_item*}