{*popup_item*}
<div class="prText2 prTCenter">{t}Clicking 'Unshare Event' below will unshare the event from your calendar.{/t}</div>
<div class="prIndentTop prTCenter">
	{t var='button'}Unshare Event{/t}
	{linkbutton name=$button color="blue" onclick="xajax_doClientUnshareEvent($id, $uid, true); return false;"}<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}