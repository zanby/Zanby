<script type="text/javascript">
{literal}
	function mark_as_read()
	{
		var mes_ids = document.getElementById('checked_mes_ids').value.split(',');
		if (mes_ids[0] == "") mes_ids.shift();
		if (mes_ids.length > 0)
			xajax_messages_markasread(mes_ids, '{/literal}{$redirectUrl}{literal}')
	}
	function mark_as_unread()
	{
		var mes_ids = document.getElementById('checked_mes_ids').value.split(',');
		if (mes_ids[0] == "") mes_ids.shift();
		if (mes_ids.length > 0)
			xajax_messages_markasunread(mes_ids, '{/literal}{$redirectUrl}{literal}')
	}
{/literal}
</script>

<!-- result begin -->

<div class="prMessagesButtonBlock">
	<div class="prFloatLeft">
	{t var='button_01'}Delete selected{/t}
	{linkbutton name=$button_01 link="#" onclick="delete_selected(getMouseCoordinateX(event), getMouseCoordinateY(event));"}</div>
	<div class="prFloatRight">
		{t var='button_02'}Mark as Read{/t}
		{linkbutton name=$button_02 link="#" onclick="javascript:mark_as_read(); return false;" link="#"}&nbsp;
		{t var='button_03'}Mark as Unread{/t}
		{linkbutton name=$button_03 link="#" onclick="javascript:mark_as_unread(); return false;" link="#"}
	</div>	
</div>
<table cellspacing="0" cellpadding="0" class="prResult prIndentTopSmall">
	<col width="2%" />
	<col width="30%" />
	<col width="55%" />
	<col width="13%" />
	<thead><tr>
		<th colspan="2"><div{if $fields.from.active} class="prRActive{if $fields.from.direction == 'up'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$redirectUrl}order/{$fields.from.order}/" class="{if $fields.from.active}freeClass1{else}freeClass2{/if}">{$fields.from.name|capitalize}</a></div></th>
		<th><div{if $fields.subject.active} class="prRActive{if $fields.subject.direction == 'up'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$redirectUrl}order/{$fields.subject.order}/" class="{if $fields.subject.active}freeClass1{else}freeClass2{/if}">{$fields.subject.name|capitalize}</a></div></th>
		<th><div{if $fields.date.active} class="prRActive{if $fields.date.direction == 'up'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$redirectUrl}order/{$fields.date.order}/" class="{if $fields.date.active}freeClass1{else}freeClass2{/if}">{$fields.date.name|capitalize}</a></div></th>
	</tr></thead>
	<tbody>
	{foreach item=m from=$messagelist name=loop_messagelist}
		{assign var="mid" value=$m->getId()}
		{assign var="sender" value=$m->getSender()}
		{if $m->getIsRead() == 0}
			{if ($smarty.foreach.loop_messagelist.iteration % 2) == 0}
				{assign var="messageClass" value="prTBold prEvenBg"}
			{else}
				{assign var="messageClass" value="prTBold prOddBg"}
			{/if}		
		{else}
			{if ($smarty.foreach.loop_messagelist.iteration % 2) == 0}
				{assign var="messageClass" value="prEvenBg"}
			{else}
				{assign var="messageClass" value="prOddBg"}
			{/if}
		{/if}
			<tr  class="{if $messageClass} {$messageClass}{/if}" id="mess_{$mid}">
				<td>
					{form_checkbox name="message_id[`$mid`]" id="message_`$mid`" checked="0" onchange="checkActive(this, '$mid', '$messageClass', 'freeClass3');"}
					{assign var="name" value=$m->getIsRead()}
					{form_hidden id="$mid" name=$name}
				</td>
				<td>
				<span class="prEllipsis prMessageName" title='{if $m->getSenderType() == 1}{if $sender->getId() eq ""}Event guest{else}{$m->getSender()->getLogin()|escape:"html"}{/if}{elseif $m->getSenderType() == 2}{$m->getSender()->getName()|escape:"html"} (Group){/if}'>
					{if $m->getSenderType() == 1}
                        {if $sender->getId() eq ""}
                            Event guest
                        {else}
                            <a class="ellipsis_init" href="{$m->getSender()->getUserPath('profile')}">{$m->getSender()->getLogin()|escape:"html"}</a>
                        {/if}
					{elseif $m->getSenderType() == 2}
							<a class="ellipsis_init" href="{$m->getSender()->getGroupPath('summary')}">{$m->getSender()->getName()|escape:"html"}</a><br />(Group)
					{/if}
					</span>
				</td>
				<td>
				<span class="prMessageSubject"><a href='{$currentUser->getUserPath("messageview/order/`$order`/id/`$mid`")}' title='{$m->getSubject()|escape:"html"}'>{$m->getSubject()|escape:"html"}</a></span></td>
				<td>{$m->getCreateDate()|date_locale:'DATE_MEDIUM'}</td>
			</tr>
		{foreachelse}
			<tr>
				<td colspan="4">
						{t}you have no messages{/t}
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>
<div class="prMessagesButtonBlock">
	<div class="prFloatLeft">
	{t var='button_04'}Delete selected{/t}
	{linkbutton name=$button_04 link="#" onclick="delete_selected(getMouseCoordinateX(event), getMouseCoordinateY(event));"}</div>
	<div class="prFloatRight">
		{t var='button_05'}Mark as Read{/t}
		{linkbutton name=$button_05 link="#" onclick="javascript:mark_as_read(); return false;" }&nbsp;
		{t var='button_06'}Mark as Unread{/t}
		{linkbutton name=$button_06 link="#" onclick="javascript:mark_as_unread(); return false;"}
	</div>	
</div>
<!-- result end -->
