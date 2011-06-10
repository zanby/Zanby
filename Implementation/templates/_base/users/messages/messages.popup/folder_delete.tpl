{*popup_item*}
<p  class="prTCenter prText2">{t}Are you sure you want to empty this folder?{/t}</p>
<div class="prInnerTop prTCenter">
	{t var='button'}Empty Folder{/t} 
    {linkbutton color="blue" name=$button onclick="xajax_deleteMessageDo('$messageId'); return false;"}
	<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a href="#" class="prIndentLeftSmall" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}