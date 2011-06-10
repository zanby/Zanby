<p  class="prText2 prTCenter">{t}{tparam value=$newhost}Are you sure you want to resign as owner and appoint %s as new owner?{/t}</p>
<div class="prInnerTop prTCenter">   
	{t var="in_button"}Yes{/t}
    {linkbutton id="Yes" name=$in_button link="#null" onclick=$onclick}
    <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a> </span>
</div>