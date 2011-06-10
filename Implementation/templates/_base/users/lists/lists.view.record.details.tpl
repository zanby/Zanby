{*popup_item*}
<div class="prListBox prInnerBottom prIndentBottom">
<div class="prDropHeader prIndentTop">
    <h3><span id="display_index_{$record->getId()}">{$record->displayIndex}		</span></h3>
    <div class="prHeaderTools"><a href="#null" onclick="lock_content(); xajax_list_view_collapse({$record->getId()}); return false;">{t}Close{/t}</a></div> 
</div>
		<div>
				<span class="prText2">{$record_view}</span>
			<p>{t} Tags:{/t}
				{foreach item=t name=tags from=$record->getTagsList()}<a {*href="{$BASE_URL}/{$LOCALE}/tags/view/tag/{$t->id}/"*}>{$t->name|wordwrap:30:"\n":true|escape}</a>{if !$smarty.foreach.tags.last}, {/if}{foreachelse}{t}No Tags{/t}{/foreach}	
			</p>			
			{if $list->getRanking()}
				<div class="prIndentTop" id="record_rank_{$record->getId()}">
				{include file="users/lists/lists.view.record.rank.tpl"}
				</div>
			{/if}
			<h4 class="prInnerSmallTop">{$record->getTitle()|wordwrap:30:"\n":true|escape}</h4>
			<p>
			{if $record->getEntry()}
				{$record->getEntry()|wordwrap:30:"\n":true|escape}
			{/if}
			</p>
			{if $record->getCommentsCount()}
			<div class="prInnerTop">
				<h4 class="prInnerTop">{t}{tparam value=$record->getTitle()|wordwrap:30:"\n":true|escape}Comments on <strong>%s</strong>{/t}</h4>
				{foreach item=comment from=$record->getCommentsList()}
				<div class="prClr prInnerTop">
					<div class="prFloatLeft prIndentRightSmall">
						<img src="{$comment->getCreator()->getAvatar()->setWidth(46)->setHeight(46)->setBorder(1)->getImage()}" alt="" title="" />
					</div>
					<div>
							<strong><!--<a href="{$comment->getCreator()->getUserPath('profile')}">{$comment->getCreator()->getLogin()|wordwrap:30:"\n":true|escape}</a>--> 
						{displaylogin href=$comment->getCreator()->getUserPath('profile') user=$comment->getCreator()}{t}says:{/t}</strong>
							<div id="commentBody{$comment->id}">
								<p id="commentText{$comment->id}">
								{$comment->content|wordwrap:30:"\n":true|escape}
								</p>
								<span class="prText4">{$comment->creationDate|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}</span> 
								{if $Warecorp_List_AccessManager->canManageComment($comment, $comment->getCreator(), $user)
                                || $Warecorp_List_AccessManager->canManageComment($comment, $list->getCreator(), $user)}
								[ <a href="javascript:void(0)" onclick="editComment({$comment->id}); return false;">{t}Edit{/t}</a> ]
								[ <a href="javascript:void(0)" onclick="confirmDeleteComment({$comment->id}); return false;">{t}Delete{/t}</a> ]
								{/if}
								
							</div>
					</div>
				</div>
				{if $Warecorp_List_AccessManager->canManageComment($comment, $comment->getCreator(), $user)
				|| $Warecorp_List_AccessManager->canManageComment($comment, $list->getCreator(), $user)}
					<div id="commentEdit{$comment->id}" style="display: none;" class="prInnerSmallTop">
						
						<textarea id="commentContent{$comment->id}" class="prFullWidth" rows="4">{$comment->content|escape}</textarea>
						<div class="prTRight prIndentTopSmall prButtonPanel">
						{t var='button_01'}Save Changes{/t}
						{linkbutton name=$button_01 onclick="saveComment('`$comment->id`'); return false;"}
						<span class="prIEVerticalAling prIndentLeftSmall">
						 {t}or{/t} <a href="#"  onclick="editCommentCancel(`$comment->id`); return false;">{t}Cancel{/t}</a>
						</span>
						</div>
					</div>
				{/if}
				{/foreach}
			</div>
			{/if}
			<div>
				{form from=$form_comment id="formComment"}
					<div class="prIndentBottom">{form_errors_summary}</div>
				{form_hidden name=record_id value=$record->getId()}
				<div class="prInnerTop">
				{form_textarea rows=5 class="prFullWidth" name=comment id="commentNew"}
				</div>
				<div class="prInnerTop prTRight">
				{t var='button_02'}Post Comment{/t}
				{linkbutton name=$button_02 onclick="saveComment(''); return false;"}
				</div>
			</div>
			{/form}
						
		</div>
</div>
<div id="deleteCommentPanel" style="display: none;">
	<p class="prText2 prTCenter">{t}Do you really want to delete this comment?{/t}</p>
	<input type="hidden" id="deleteCommentId" value="">	
	<div class="prInnerTop prTCenter">
	{t var='button_03'}Delete Comment{/t} 
	        {linkbutton name=$button_03 onclick="xajax_list_view_delete_comment(YAHOO.util.Dom.get('deleteCommentId').value); return false;"}
			<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
	</div>
</div>
{*popup_item*}