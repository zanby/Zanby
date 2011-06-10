{if $keywords}<h2>{t}{tparam value=$SITE_NAME_AS_STRING} %s All Results About {/t} {$keywords|wordwrap:25:"\n":true|escape}</h2>{/if}
{if $searchTitle}<h2>{$searchTitle|escape}</h2>{/if}
{if $rssUrl}
{assign var='thisrss' value=0}{/if}
{if $searchTitle}<h2>{$searchTitle}</h2>{/if}
{if $groupsList}
	{$paging}
	<table cellpadding="0" cellspacing="0" border="0" class="prResult">
		<col width="12%" />
		<col width="31%" />
		<col width="21%" />
		<col width="17%" />
		<col width="19%" />
		<tr>
			<th class="prNoRBorder">&nbsp;</th>
			<th>
			</th>
		</tr>
		{foreach item=group from=$groupsList}
		<tr>
			{if $group->EntityTypeName == 'group'}
				{view_factory entity='group' object=$group}
			{elseif $group->EntityTypeName == 'family'}
				{view_factory entity='group' object=$group}
			{elseif $group->EntityTypeName == 'user'}
				{view_factory entity='user' object=$group}
			{elseif $group->EntityTypeName == 'list'}
				{view_factory entity='list' object=$group}
			{elseif $group->EntityTypeName == 'video'}
				{view_factory entity='video' object=$group}
			{elseif $group->EntityTypeName == 'photo'}
				{view_factory entity='photo' object=$group}
			{elseif $group->EntityTypeName == 'document'}
				{view_factory entity='document' object=$group}
			{elseif $group->EntityTypeName == 'event'}
				{view_factory entity='event' object=$group}
			{else}
				{view_factory entity='discussion' object=$group}
			{/if}
		</tr>
		{/foreach}
	</table>
	<div class="prIndentTop">{$paging}</div>
{else}
	{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}{tparam value=$BASE_URL}{tparam value=$LOCALE}
	There are no groups in search results<br />
	Use the right utility to search again. Or you can seize the day and<br />
	<a href="%s/%s/newgroup/">Start a Group</a><br />
	If you have a special question, please email <a href="%s/%s/info/contactus/">Contact Us.</a>{/t}
{/if}
{include file="groups/search.form.tpl"}