<h2>{t}Draft Invitations{/t}</h2>
{if $invitations}
	{form from=$form id="draft_form" name="draft_form"}
		{if $order}
		  {assign var="orderPath" value='order/'|cat:$order|cat:'/direction/'|cat:$direction|cat:'/'}
		{else}
		  {assign var="orderPath" value=''}
		{/if}
			<div>{$paging}</div>
			<table class="prResult" cellspacing="0" cellpadding="0" border="0">
				<col width="5%" />
				<col width="45%" />
				<col width="50%" />
				<tr>
								<th>
								<input type="checkbox" id="check" onchange="check_all_checkboxes(document.getElementById('draft_form'), this); return false;" class="prNoBorder" /></th>
								<!--th><a href="#null">Host</a></th-->
								<th> <div{if $order == 'name'} class="prRActive {if $direction == 'asc'}prRActive-top {else}prRActive-bottom {/if}"{/if}> <a {if $order == 'name'} href = "{$CurrentGroup->getGroupPath('invitelist')}folder/draft/{$orderPath}page/1" {else} href = "{$CurrentGroup->getGroupPath('invitelist')}folder/draft/order/name/direction/asc/page/1"{/if}>{t}Invitation name{/t}</a>
						</div>
					</th>
								<!--th nowrap="nowrap"> <a href="#null">Founded</a> </th-->
								<th> <div{if $order == 'date'} class="prRActive {if $direction == 'asc'}prRActive-top {else}prRActive-bottom" {/if}{/if}> <a {if $order == 'date'} href = "{$CurrentGroup->getGroupPath('invitelist')}folder/draft/{$orderPath}page/1" {else} href = "{$CurrentGroup->getGroupPath('invitelist')}folder/draft/order/date/direction/asc/page/1"{/if}>{t}Date saved{/t}</a>
						</div>
					</th>
							</tr>
				{foreach item=invitation from=$invitations name="invitationList"}
				<tr>
								<td>{form_checkbox name="invitations[]" value=$invitation->getId()}</td>
								<td><a href="{$currentGroup->getGroupPath('invitecompose')}id/{$invitation->getId()}/">{$invitation->getName()}</a> </td>
								<td>{$invitation->getCreationDate()|date_locale:'DATE_MEDIUM'}</td>
							</tr>
				{/foreach}
			</table>
			<div class="prInnerTop">{$paging}</div>
			<div class="prInnerTop prTCenter"> {t var="in_button"}Delete Selected Draft Invites{/t}{linkbutton name=$in_button onclick="xajax_invitationsremove(xajax.getFormValues('draft_form')); return false;"} </div>
			{/form}
 
	{else}
			<div class="prText2 prTCenter">{t}No saved draft invitations{/t}</div>
			{/if}
			<script type="text/javascript" src="/js/simple_checkboxes.js"></script>
