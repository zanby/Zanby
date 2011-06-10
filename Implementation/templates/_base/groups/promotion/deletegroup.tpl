<p>{$question}</p>
<div class="prInnerTop prTCenter"> 
    {if $dnull}
		{t var="in_button"}Ok{/t}
    	{linkbutton name=$in_button onclick="popup_window.close(); return false;"}
    {else}
		{t var="in_button_2"}Yes{/t}
	    {linkbutton name=$in_button_2 onclick=$onclick}
	    <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
	{/if}    
</div>