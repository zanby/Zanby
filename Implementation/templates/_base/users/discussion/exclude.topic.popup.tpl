{*popup_item*}
<input type="hidden" name="topic_id" id="topic_id" value="{$topic->getId()}">
<p  class="prText2 prTCenter">{t}Are you sure you want to remove this topic from my discussions?{/t}</p>
<div class="prInnerTop prTCenter"> 
	{t var='in_button'}Remove Topic{/t}   
    {linkbutton color="blue" name=$in_button onclick="exclude_topic_do(); return false;"}
	<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a> </span> 
</div>
{*popup_item*}