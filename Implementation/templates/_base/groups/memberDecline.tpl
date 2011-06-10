<p class="prText2 prTCenter">{if $all == true}
{t}Are you sure you want to decline all members?{/t}
{else}
{t}Are you sure you want to decline this member?{/t}
{/if}
</p>
<div class="prInnerTop prTCenter">
	{t var="in_button"}Yes{/t}
    {linkbutton id="Yes" name=$in_button link=$link}
    <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>   
</div>