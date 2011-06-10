{*popup_item*}
<p class="prTCenter prText2">{t}Are you sure you want to delete this message?{/t}</p>
<div class="prInnerTop prTCenter">
	{t var='button'}Yes{/t}  
    {linkbutton name=$button onclick="xajax_deleteMessageDo('`$messageId`'.split(',')); return false;"}
	<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}