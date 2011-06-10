{*popup_item*}
<p class="prText2 prTCenter">{t}Are you sure you want to delete this WebBadge?{/t}</p>
<div class="prInnerTop prTCenter"> 
	{t var="in_button"}Delete{/t}
	{linkbutton name=$in_button onclick="xajax_webbadgeDeleteDo($webbadgeId); return false;"}
	<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}