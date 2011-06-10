{*popup_item*}
<input type="hidden" name="post_id" id="post_id" value="{$post->getId()}">
<p class="prText2">{t}Are you sure you want to report this message?<br /> A notification will be sent to the host and moderators of this discussion.{/t}</p>        
<!-- popup -->
<div class="prInnerSmallTop prTCenter">
	{t var="in_button"}Report Post{/t}
	{linkbutton name=$in_button onclick="report_post_do(); return false;"}
	<span class="prIEVerticalAling prIndentLeftSmall">
    {t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>	
</div>
<!-- /popup -->
{*popup_item*}