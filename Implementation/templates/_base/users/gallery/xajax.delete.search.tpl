{*popup_item*}
<p>{t}Are you sure you want to delete this search?{/t}</p>
<div class="prInnerTop prTCenter">
	{t var='button_01'}Yes{/t}
    {linkbutton name="Yes" onclick="document.getElementById('searchtodel').value = $searchId; document.formRemember.submit(); return false;"}
    <span class="prIndentLeftSmall">
	{t var='button_02'}No{/t}
	{linkbutton name=$button_02 onclick="popup_window.close(); return false;"}</span>
</div>
{*popup_item*}