<p class="prTCenter prText2">{t}{tparam value=$newhost}Are you sure you want to resign as host and appoint %s as new host?{/t}</p>
<div class="prInnerTop prTCenter">
	{t var="in_button"}Yes{/t}
    {linkbutton id="Yes" name=$in_button link="#null" onclick=$onclick}
     <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span> 
</div>