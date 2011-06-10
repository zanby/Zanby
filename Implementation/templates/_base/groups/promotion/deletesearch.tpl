{*popup_item*}
<p class="prText2 prTCenter">{t}Are you sure you want to delete this search?{/t}</p>
<div class="prInnerTop prTCenter"> 
	{t var="in_button"}Yes{/t}
    {linkbutton name=$in_button link=$url}
    <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}