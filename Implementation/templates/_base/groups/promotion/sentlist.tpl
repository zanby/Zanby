
<h2>{t}Sent Invitations{/t}</h2>
{if $groups}
			{form from=$form id="send_form"}
			{if $order}
			  {assign var="orderPath" value='order/'|cat:$order|cat:'/direction/'|cat:$direction|cat:'/'}
			{else}
			  {assign var="orderPath" value=''}
			{/if}
				<div>{$paging}</div>
				<table class="prResult" cellspacing="0" cellpadding="0" border="0">
					<col width="5%" />
					<col width="45%" />
					<col width="25%" />
					<col width="25%" />
					<tr>
						<th><input type="checkbox" id="check" onchange="check_all_checkboxes(document.getElementById('send_form'), this); return false;" /></th>
						<th><div{if $order == 'name'} class="prRActive {if $direction == 'asc'}prRActive-top{else}prRActive-bottom{/if}"{/if}> <a {if $order == 'name'} class="freeColor1" href = "{$CurrentGroup->getGroupPath('invitelist')}folder/sent/{$orderPath}page/1" {else} class="freeColor2" href = "{$CurrentGroup->getGroupPath('invitelist')}folder/sent/order/name/direction/asc/page/1"{/if}>{t}To (group name){/t}</a></div></th>
						<th> <div{if $order == 'status'} class="prRActive {if $direction == 'asc'}prRActive-top{else}prRActive-bottom{/if}"{/if}> <a {if $order == 'status'} class="freeColor1" href = "{$CurrentGroup->getGroupPath('invitelist')}folder/sent/{$orderPath}page/1" {else} class="freeColor2" href = "{$CurrentGroup->getGroupPath('invitelist')}folder/sent/order/status/direction/asc/page/1"{/if}>{t}Status{/t}</a></div></th>
						<th> <div {if $order == 'date'} class="prRActive {if $direction == 'asc'}prRActive-top{else}prRActive-bottom{/if}"{/if}> <a {if $order == 'date'} class="freeColor2" href = "{$CurrentGroup->getGroupPath('invitelist')}folder/sent/{$orderPath}page/1" {else} class="freeColor2" href = "{$CurrentGroup->getGroupPath('invitelist')}folder/sent/order/date/direction/asc/page/1"{/if}>{t}Date sent{/t}</a> </div></th>
					</tr>
					{foreach item=group from=$groups name="groupList"}
					<tr>
						<td>{form_checkbox name="groups[]" value=$group.group->getId()}</td>
						<td><a href="{$group.group->getGroupPath('summary')}" style="font-size:13px; font-weight: bold;">{$group.group->getName()|escape:html}</a> </td>
						<td>{$group.status}</td>
						<td>{$group.creation_date|date_locale:'DATE_MEDIUM'}</td>
					</tr>
					{foreachelse}
					<tr>
						<td colspan="4" align="center">{t}folder Sent is empty.{/t}</td>
					</tr>
					{/foreach}
				</table>
				<div class="prIndentTop">{$paging}</div>
				<div class="prInnerTop prTCenter"> {t var="in_button"}Delete Selected Invitations{/t}{linkbutton name=$in_button onclick="xajax_groupsremove(xajax.getFormValues('send_form')); return false;"} </div>
				{/form}
{else}
		<div class="prTCenter prText2">{t}No sent invitations{/t}</div>
{/if}
<script type="text/javascript" src="/js/simple_checkboxes.js"></script>
