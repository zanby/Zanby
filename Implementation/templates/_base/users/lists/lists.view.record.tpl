<div class="prIndentTopLarge prClr3">
	<div id="display_index_{$record->getId()}" class="prFloatLeft"><label>{$record->displayIndex} </label></div>
	<div class="prListDescrRight">
		<div>
			<a href="#" onclick="lock_content(); xajax_list_view_expand('{$record->getId()}'); return false;" class="prText2">{$record->getTitle()|wordwrap:30:"\n":true|escape}</a> {if $record->getExtraTitleStr()}<span>{$record->getExtraTitleStr()}</span>{/if}
			{if $Warecorp_List_AccessManager->canManageRecord($record, $currentUser, $user)}
				<span>
				| <a href="#" onclick="lock_content(); xajax_list_view_expand('{$record->getId()}', 'edit'); return false;">{t}Edit{/t}</a> 
				| <a href="#" onclick="confirmDeleteRecord('{$record->getId()}'); return false;">{t}Delete{/t}</a>
				</span>
			{/if}
		</div>
		{if $list->getCreator()->getId() != $record->getCreator()->getId()}
			<p class="prInnerSmallTop">{t}{tparam value=$record->getCreationDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'}{tparam value=$TIMEZONE}{tparam value=$record->getCreator()->getLogin()}Posted on %s %s by %s{/t}</p>
		{/if}
		{if $list->getRanking()}
			<div class="prIndentTop" id="record_rank_{$record->getId()}">
				{include file="users/lists/lists.view.record.rank.tpl"}
			</div>
		{/if}
		{if $record->getEntry()}
			<p class="prInnerSmallTop">
				{$record->getEntry()|wordwrap:30:"\n":true|escape}
				<a href="#" onclick="lock_content(); xajax_list_view_expand('{$record->getId()}'); return false;">{t}Read More{/t}</a>
			</p>            
		{/if}
		<p class="prInnerSmallTop">
		<a href="#" onclick="lock_content(); xajax_list_view_expand({$record->getId()}); return false;">{if $record->getCommentsCount()!=1}
			{t}{tparam value=$record->getCommentsCount()} %s comments{/t}
		{else}
			{t}{tparam value=$record->getCommentsCount()} %s comment{/t}
		{/if}</a>
		</p>   
	</div> 
</div>