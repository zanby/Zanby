{*popup_item*}
<p class="prText2 prTCenter">{t}Are you sure you want to delete this friend?{/t}</p>
<div class="prInnerTop prTCenter">
	<span class="prIndentLeftSmall">
	{t var='button_01'}Delete Friend{/t}
	{linkbutton color="blue" name=$button_01 onclick="xajax_deleteFriendDo($friendId); return false;"}</span>
	<span class="prIEVerticalAling">{t}or{/t} <a href="#"  onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}