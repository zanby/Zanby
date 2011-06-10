<p class="prText2 prTCenter">{$question}</p>
<div class="prInnerTop prTCenter"> 
    {if $dnull}
		{t var="in_button"}Ok{/t}
    	{linkbutton name=$in_button onclick="popup_window.close(); return false;"}
    {else} 
		{t var="in_button_2"}Yes{/t}   
	    {linkbutton name=$in_button_2 onclick=$onclick}
	    <span class="prIndentLeftSmall">{t var="in_button_3"}No{/t} {linkbutton name=$in_button_3 onclick="popup_window.close(); return false;"}</span>
    {/if}   
</div>