{*popup_item*}
<div class="prText2 prTCenter">{t}Are you sure you want to remove yourself from event guest list?{/t}</div>
<div class="prTCenter prIndentTop">
	{t var='button'}Remove Me{/t}
	{linkbutton name=$button color="blue" onclick="$linkUrl"}
	<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}