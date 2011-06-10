{*popup_item*}
<p class="prTCenter prText2">{t}Are you sure you want to empty trash?{/t}</p>
<div class="prInnerTop prTCenter">
	{t var='button'}Empty Trash{/t}  
    {linkbutton color="blue" name=$button onclick="xajax_deleteMessage('$messageId', 'true', getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;"}
	<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}