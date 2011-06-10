{*popup_item*}
<div class="prTCenter">
	<select name="discussion_id" id="discussion_id" class="prLargeFormItem">
		{foreach from=$dis item=discussion}
		<option value="{$discussion->getId()}"{if $discussion->getId() == $topic->getDiscussionId()} selected{/if}>{$discussion->getTitle()|escape:html}</option>
		{/foreach}
	</select>    
</div>  
<!-- popup -->
<div class="prInnerTop prTCenter">	
	{t var="in_button"}Move Topic{/t}
	{linkbutton color="blue" name=$in_button  onclick="move_topic_do("|cat:$topic->getId()|cat:"); return false;"}
	<span class="prIEVerticalAling">
    {t}or{/t} <a href="javascript:void(0)" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}