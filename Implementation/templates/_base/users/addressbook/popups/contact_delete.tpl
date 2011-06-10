{*popup_item*}
<p class="prTCenter prText2">{$deleteQuestion}</p>
<div class="prInnerTop prTCenter">
{t var='button'}Yes{/t}    
    {linkbutton color="blue" name=$button onclick="xajax_addressbookDeleteContact('$maillistId','$listContacts','showed','$isContact'); return false;"}
	<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}