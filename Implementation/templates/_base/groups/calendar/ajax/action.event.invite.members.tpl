{*popup_item*}
{form from=$form id="form_invite_members" onsubmit=$linkUrl}

<div class="prPopupHeight">
	{foreach from=$lstMembers item='member'}
	{assign var='checkIndex' value=$member->getId()}
	<div class="prInner">
		{form_checkbox name='event_invite_members[]' id='event_invite_members_'|cat:$member->getId() value=$member->getId() checked=$formCheckedItems[$checkIndex]}<label for="event_invite_members_{$member->getId()}"> {$member->getLogin()|escape:html}
		<span class="prTNormal">({$member->getFirstName()|escape:html}&nbsp;{$member->getLastName()|escape:html})</span></label>
		
	</div>
	{/foreach}
	
</div>
<div class="prIndentTop prTCenter">
	{if $lstMembers}
	<span class="prIndentRight">{t var="in_button"}Invite Checked Members{/t}{linkbutton name=$in_button onclick=$linkUrl}</span><span class="prIEVerticalAling">{t}or{/t} 
	{/if}
	<a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a>{if $lstMembers}</span>{/if}
</div>
{/form}
{*popup_item*}
