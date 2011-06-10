<div class="prIndentTop prClr3">
	{if $record->getXmlFieldValue('limit')<=$record->getVolunteersCount() && $record->getXmlFieldValue('limit')>0}
		<div class="prFloatLeft" id="display_index_{$record->getId()}">{$record->displayIndex}</div>
	{else}
		<div class="prFloatLeft" id="display_index_{$record->getId()}">{$record->displayIndex}</div>
	{/if} 
	<div class="prFloatLeft prIndentLeft prBoxVolunteer">
		<div class="prIndentBottom">{$list->getListTypeName()}
		   
				{$record->getTitle()|escape|wordwrap:30:"\n":true}
				{$XSLTProcessor->transformToXml($record->domXml)}
                {if !$record->getRelatedEventId()}
                    {if $Warecorp_List_AccessManager->canManageRecord($record, $CurrentGroup, $user)}
                     <span> | <a href="#" onclick="lock_content(); xajax_list_view_expand('{$record->getId()}', 'edit'); return false;">{t}Edit{/t}</a>
                        | <a href="#" onclick="confirmDeleteRecord('{$record->getId()}'); return false;">{t}Delete{/t}</a>
                    {/if}
                    </span>
                {/if}
		</div>
		{capture name=voluntersNeed}
			{if $record->getXmlFieldValue('limit') == 0}
				{t}As many volunteers as possible {/t}
			{elseif $record->getXmlFieldValue('limit')>$record->getVolunteersCount()}
				
			{capture name=voluntersCntNeed}{math equation="x - y" x=$record->getXmlFieldValue('limit') y=$record->getVolunteersCount()}{/capture}
				{$smarty.capture.voluntersCntNeed}
				{if $smarty.capture.voluntersCntNeed!=1}
					{t}more volunteers needed{/t}
				{else}
					{t}more volunteer needed{/t}
				{/if}
			{else}
				{t}No more volunteers needed{/t}
			{/if}
		{/capture}
		 <div class="prClr3 prInnerSmallTop">        
		{if $record->isUserVolunteer()}
			<div class="prFloatLeft prIndentRight">{t}You Volunteered{/t}</div>
			<div class="prFloatLeft prText3">	
			{$smarty.capture.voluntersNeed}
			</div>
		{elseif $smarty.capture.voluntersCntNeed || $record->getXmlFieldValue('limit') == 0}
			<div class="prFloatLeft prIndentRight prButtonPanel">
			{t var="in_button"}I Will!{/t}
			{linkbutton name=$in_button onclick="xajax_list_volunteer_popup_show('"|cat:$record->getId()|cat:"'); return false;"}
			</div>
			<div class="prFloatLeft prText3"> 
			{$smarty.capture.voluntersNeed}
			</div>
		{else}
			<div class="prFloatLeft prText3">
			{$smarty.capture.voluntersNeed}
			</div>
		{/if}
		</div>
		{if $list->getCreator()->getId() != $record->getCreator()->getId()}
		  <p class="prInnerSmallTop">
			{t}{tparam value=$record->getCreationDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'}{tparam value=$TIMEZONE}{tparam value=$record->getCreator()->getLogin()}Posted on %s %s by %s{/t}
			</p>
		{/if}
		
		{if $list->getRanking()}
			<div class="prIndentTop" id="record_rank_{$record->getId()}">
			{include file="groups/lists/lists.view.record.rank.tpl"}
			</div>            
		{/if}
		
		{if $record->getXmlFieldValue('description')}
			<p class="prInnerSmallTop">
            {if $record->getRelatedEventId()}
                {$record->getXmlFieldValue('description')}
            {else}
                {$record->getXmlFieldValue('description')|wordwrap:30:"\n":true|escape}
            {/if}
			</p>
		{/if}
	{if $record->getVolunteersCount()}
	<div id="volunteers_list_{$record->getId()}">
		{foreach from=$record->getVolunteersList() item=u name=VolunteersList}
			<div class="prClr2 prInnerSmallTop">
			<div id="volunteer_{$u->volunteerId}" class="prFloatLeft prIndentRight">
				<img src="{$u->getAvatar()->setWidth(26)->setHeight(26)->setBorder(1)->getImage()}" alt="" title="" align="middle" /> 
				<a href="{$u->getUserPath('profile')}">{$u->getLogin()|escape|wordwrap:30:"\n":true}</a> 
				{$u->getComment()|escape|wordwrap:30:"\n":true}
			</div>
			{if $Warecorp_List_AccessManager->canDeleteVolunteer($u->volunteerId, $record, $CurrentGroup, $user)}
				{if $u->getId() == $user->getId()}
					<div class="prFloatLeft">
					{t var="in_button_2"}Change your mind{/t}
					{linkbutton name=$in_button_2 onclick="lock_content(); xajax_list_volunteer_delete('"|cat:$record->getId()|cat:"','"|cat:$u->volunteerId|cat:"'); return false;"}
					</div>
				{else}
				<div class="prButtonVolunteer">
					<div class="prFloatLeft">
					{t var="in_button_3"}Delete Volunteer{/t}
					{linkbutton name=$in_button_3 onclick="lock_content(); xajax_list_volunteer_delete('"|cat:$record->getId()|cat:"','"|cat:$u->volunteerId|cat:"'); return false;"}</div></div>
				{/if}
			{/if}
		</div>
		{/foreach}
	</div>

	{/if}   
	</div>
</div>