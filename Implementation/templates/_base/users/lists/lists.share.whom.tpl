{if $loadFromSession}
{assign var="friendsList" value=$smarty.session.list_edit.canshareusers}
{assign var="groupsList" value=$smarty.session.list_edit.cansharegroups}
{/if}
<select name="share" id="shareId" style="width: 260px;" class="prFloatLeft prIndentRightSmall">
	<option value="">{t}Select{/t}</option>
	{if $friendsList}
	<optgroup label="Friends">
		{foreach item=u key=id from=$friendsList}
		<option value="u_{$id}">{$u|escape:"html"}</option>
		{/foreach}
	</optgroup>
	{/if}
	{if $groupsList}
	<optgroup label="Groups">
		{foreach item=g key=id from=$groupsList}
		<option value="g_{$id}">{$g|escape:"html"}</option>
		{/foreach}
	</optgroup>
	{/if}
</select>


