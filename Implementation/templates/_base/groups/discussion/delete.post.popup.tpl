{*popup_item*}
<input type="hidden" name="post_id" id="post_id" value="{$post->getId()}">
<p class="prText2 prTCenter">{t}Are you sure you want to delete this message?{/t}</p>
        
<!-- popup -->
<div class="prInnerTop prTCenter">
	{t var="in_button"}Remove Post{/t}
	{linkbutton color="blue" name=$in_button onclick="delete_post_do(); return false;"}
	<span class="prIEVerticalAling prIndentLeftSmall">
    {t}or{/t} <a href="#" onclick="popup_window.close(); return false;"}>{t}Cancel{/t}</a></span>
</div>
<!-- /popup -->
{*popup_item*}