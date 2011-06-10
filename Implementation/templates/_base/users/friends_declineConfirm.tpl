{*popup_item*}
<div class="prInnerTop"><p class="prTCenter prText2">{t}Are you sure?{/t}</p></div>
<div class="prTCenter prInner">
	{t var='button_01'}Yes{/t}
	{linkbutton color="blue" name=$button_01 onclick="xajax_declineFriendRequest($requestId $redirect); return false;"}
	<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span></div>
{*popup_item*}