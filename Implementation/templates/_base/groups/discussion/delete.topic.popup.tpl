{*popup_item*}
<input type="hidden" name="topic_id" id="topic_id" value="{$topic->getId()}">
<p class="prText2 prTCenter">{t}Are you sure you want to delete this topic?{/t}</p>
<!-- popup -->
<div class="prInnerTop prTCenter">
	{t var="in_button"}Remove Topic{/t}
	{linkbutton color="blue" name=$in_button  onclick="remove_topic_do(); return false;"}
	<span class="prIndentLeftSmall"> {t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
<!-- /popup -->
{*popup_item*}