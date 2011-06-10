{*popup_item*}
<p class="prTCenter prText2">{t}Are you sure you want to delete this search?{/t}</p>
<div class="prInnerTop prTCenter">
{t var="button"}Yes{/t}
    {linkbutton name=$button onclick="document.getElementById('searchtodel').value = $searchId; document.formRemember.submit(); return false;"}
    <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}