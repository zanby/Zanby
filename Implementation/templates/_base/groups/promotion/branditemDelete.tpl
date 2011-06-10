<p class="prText2 prTCenter">{t}Are you sure you want to delete this Brand Item?{/t}</p>
<div class="prInnerTop prTCenter"> 
	{t var="in_button"}Delete{/t}
	{linkbutton name=$in_button onclick="xajax_branditemDeleteDo($branditemId); return false;"}
	<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
