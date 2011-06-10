{assign var="GroupName" value=$CurrentGroup->getName()}
<!-- tabs2 slave area begin -->
<h2>{t}Invite Groups to join family (Step 1 of 2){/t}</h2>
<div class="prDropBox prDropBoxInner">
	<div class="prDropHeader">
		<h2>{t}Step 1: Select groups to invite{/t}</h2>
	</div>
	<div class="prHeaderHelper">{t}This tool will search all groups not currently a member of your family<br />
		OR <a href="{$CurrentGroup->getGroupPath('invitelist/folder/sent')}">groups you have already invited</a>.{/t}</div>
	<div class="prInnerTop"> {include file="groups/promotion/search.form.tpl"} </div>
	{include file="groups/promotion/search.list.tpl"}
	</div>
	{if $groupsList}
	<div class="prTRight prIndentTop"> {t var="in_button"}Add Selected Groups to Invite List{/t}{linkbutton name=$in_button onclick="xajax_addgroups(xajax.getFormValues('add_selected_groups_form')); return false;"} </div>
	{/if}                
	{if $selectedGroups}	
	{form from=$inviteForm}
	<div class="prDropBox prDropBoxInner prIndentTop">
	<div class="prDropHeader">
	<h2 class="prIndentBottom">{t}Invite List{/t}</h2>
	</div>
	<div class="prDropBox prDropBoxInner">
		<ul class="prInnerSmall">
			{foreach item="item" from=$selectedGroups}
			<li class="prInnerSmall prGrayBorder prIndentTopSmall"><a href="{$item->getGroupPath('summary')}">{$item->getName()}</a><a class="prClose" href="{$currentGroup->getGroupPath('invitesearch')}remove/{$item->getId()}">&nbsp;</a> </li>
			{/foreach}
		</ul>
	</div>
	</div>
	<div class="prTRight prInnerTop"> {t var="in_button_2"}Continue to Step 2 &raquo;{/t}{linkbutton  name=$in_button_2 onclick="document.forms['invite_list_form'].submit(); return false;"} </div>
	{/form}			
	{else}
	{/if} 
