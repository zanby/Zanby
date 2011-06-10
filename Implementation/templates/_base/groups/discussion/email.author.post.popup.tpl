{*popup_item*}
<form name="createTopicForm"  id="createTopicForm" method="post">
	<div class="prIndentBottom" style="display:none" id="ErrorMessageMain">
			<p>{t}<strong>ERROR:</strong> Enter please message{/t} </p>
	</div>
	<div class="prIndentBottom" style="display:none" id="ErrorMessageMainTooLong">
		<p>{t}<strong>ERROR:</strong> Message too long (max 4096){/t}</p>
	</div>
	<label for="replySubj">{t}Subject:{/t}</label>
	<input class="prLargeFormItem" type="text" id="replySubj" name="subject" value="Re:{$post->getTopic()->getSubject()|escape:html}" readonly="readonly">
	<div class="co-edit-pannel prInnerTop">	
		<input type="hidden" name="post_id" id="post_id" value="{$post->getId()}">
		<textarea name="content" id="content" style="height: 200px; width:99%;" rows="10" cols="80">{$post->getContent()|escape:html}</textarea>
	</div>
	<div class="prInnerTop prTCenter">
		{t var="in_button"}Send Message{/t}
		
        {if $discussion_mode == 'html'}{linkbutton name=$in_button onclick="tinyMCE.get('content').save(); email_author_do(); return false;"}
        {else}{linkbutton name=$in_button onclick="email_author_do(); return false;"}{/if}
         <span class="prIEVerticalAling">{t}or{/t} 
		<a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
	</div> 
</form>
{*popup_item*}