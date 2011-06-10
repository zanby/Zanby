{*popup_item*}
<form name="createTopicForm"  id="createTopicForm" method="post">
	<input type="hidden" name="currentPage" id="currentPage" value="{$currentPage}" />
	<input type="hidden" name="sortmode" id="sortmode" value="{$sortmode}" />
	<div class="prIndentBottom" style="display:none" id="ErrorMessageMain">
		<p>{t}<strong>ERROR:</strong> Enter please message{/t}</p>
	</div>
	<div class="prIndentBottom" style="display:none" id="ErrorMessageMainTooLong">
		<p>{t}<strong>ERROR:</strong> Message too long (max 4096){/t}</p>
	</div>
	<label for="replySubj">{t}Subject:{/t}</label>
	<input class="prMiddleFormItem" type="text" id="replySubj" name="subject" value="Re:{$post->getTopic()->getSubject()|escape:html}" readonly="readonly"><a href="#full-window" onclick="save_post_reply({$post->getId()})" class="prInnerSmall">{t}open full window{/t}</a><a href="{$CurrentGroup->getGroupPath('replytopic/topicid')|cat:$post->getTopicId()|cat:'/'}"><img src="{$AppTheme->images}/decorators/btn_openfull.gif" alt="" title="" class="prIndentLeftSmall" /></a>
	
	{if $discussion_mode == 'html'}{else}<div class="co-edit-pannel prInnerTop">{include file="groups/discussion/template.bbcode.panel.tpl"}</div>{/if}
	
	<input type="hidden" name="post_id" id="post_id" value="{$post->getId()}">	
	<div class="prInnerTop"><textarea name="content" id="content" class="prInnerSmall" rows="10" cols="80" style="width: 97%;"></textarea></div>
	<div class="prInnerTop prButtonPanel">
		<div class="prTCenter">
			{t var="in_button"}Post Message{/t}
			{if $discussion_mode == 'html'}{linkbutton color="blue" name=$in_button  onclick="tinyMCE.get('content').save();reply_post_do(); return false;"}
			{else}{linkbutton color="blue" name=$in_button  onclick="reply_post_do(); return false;"}{/if}
			<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
		</div>
	</div>
</form>
{*popup_item*}