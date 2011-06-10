<!-- result begin -->

<div class="prMessagesButtonBlock">
	{t var='button_01'}Delete selected{/t}
	{linkbutton name=$button_01 link="#" onclick="delete_selected(getMouseCoordinateX(event), getMouseCoordinateY(event));"}
</div>
<table cellspacing="0" cellpadding="0" class="prResult">
	<col width="2%" />
	<col width="30%" />
	<col width="55%" />
	<col width="13%" />
	<thead><tr>
		<th colspan="2"><div{if $fields.to.active} class="prRActive{if $fields.to.direction == 'up'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$redirectUrl}order/{$fields.to.order}/" class="{if $fields.to.active}freeClass1{else}freeClass2{/if}">{$fields.to.name|capitalize}</a></div></th>
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
			<tr class="{if $messageClass} {$messageClass}{/if}" id="mess_{$mid}">
				<td>
					{form_checkbox name="message_id[`$mid`]" id="message_`$mid`" checked="0" onchange="checkActive(this, '$mid', '$messageClass', 'freeClass3');"}
					{assign var="name" value=$m->getIsRead()}
					{form_hidden id="$mid" name=$name}
				</td>
				<td>
					<span class="prEllipsis prMessageName" title="{$m->getRecipientsStringName()|escape:html}"><span class="ellipsis_init">{$m->getRecipientsStringName()|escape:"html"}</span></span>
				</td>
				<td><span class="prMessageSubject"><a href='{$currentUser->getUserPath("messagecompose/load/draft/id/`$mid`")}' title='{$m->getSubject()|escape:"html"}'>{$m->getSubject()|escape:"html"}</a></span></td>
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
	{t var='button_02'}Delete selected{/t}
	{linkbutton name=$button_02 link="#" onclick="delete_selected(getMouseCoordinateX(event), getMouseCoordinateY(event));"}
</div>

<!-- result end -->
