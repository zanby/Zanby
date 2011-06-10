{*popup_item*}
<div id="shareFilePanelSharedGroups" class="prClr3">
	{foreach item=g from=$sharedGroupsList}
        {if $Warecorp_List_AccessManager->canUnshareList($list, $CurrentGroup, $user)}
            {assign var="checkId" value='family_'|cat:$g->getId()}
            <div id="groupShare_{$g->getId()}_{$list->getId()}" class="prClr3 prIndentTopSmall">
                <div class="prFloatLeft prIndentRight">{if $familySharedWithAliases[$checkId]}{t}{tparam value=$familySharedWithAliases[$checkId]|escape:"html"}Shared with %s{/t}{else}{t}{tparam value=$g->getName()|escape:"html"}Shared with %s{/t}{/if}</div>
                {if $familySharedWithAliases[$checkId]}
                    <a class="prFloatLeft" href="javascript:void(0)" onclick="xajax_list_unshare('{$list->getId()}', 'group', '{$checkId}'); return false;">{t}Unshare{/t}</a>
                {else}
                    <a class="prFloatLeft" href="javascript:void(0)" onclick="xajax_list_unshare('{$list->getId()}', 'group', '{$g->getId()}'); return false;">{t}Unshare{/t}</a>
                {/if}
            </div>
        {/if}
	{/foreach}
</div>
<div id="shareFilePanelSharedUsers" class="prClr3">
	{foreach item=u from=$sharedFriendsList}
	{if $Warecorp_List_AccessManager->canUnshareList($list, $CurrentGroup, $user)}
	<div id="userShare_{$u->getId()}_{$list->getId()}" class="prClr3 prIndentTopSmall">
		<div class="prFloatLeft prIndentRight">{t}{tparam value=$u->getLogin()|escape:"html"}Shared with %s{/t}</div>
		<a class="prFloatLeft" href="javascript:void(0)" onclick="xajax_list_unshare({$list->getId()}, 'user',{$u->getId()}); return false;">{t}Unshare{/t}</a>
	</div>
	{/if}
	{/foreach}
</div>
<p class="prMarkRequired">{t}Note:{/t}
	{t}Only groups which you host, or in which the
	host has granted you permission to create lists will appear
	in this field. if you do not see the desired group, you must contact
	the host to get permission to share your list   {/t}         
</p>
{if $groupsList}
<div class="prInnerBottom">
	<label for="share_group_id">{t}Select a group to share this list:{/t}</label>
	<div class="prInnerSmallTop">
	<select name='group_id' id='group_id' class="prMiddleFormItem">
		{foreach key=id item=name from=$groupsList}
			 <option value="{$id}">{$name|escape:"html"}</option>
		{/foreach}
	</select>
	{t var="in_button_2"}Share List{/t}
	{linkbutton name=$in_button_2 link="javascript:void(0)" onclick="xajax_list_share("|cat:$list->getId()|cat:", 'group', document.getElementById('group_id').options[document.getElementById('group_id').selectedIndex].value); return false;"}
	</div>
</div>     
{else}
<p class="prMarkRequired">{t}Note:{/t}{t}There are no groups for share this list{/t}</p>
{/if}
{if $friendsList}
<div class="prInnerBottom">
	<label for="friend_id">{t}Select a friend to share this list:{/t}</label>
	<div class="prInnerSmallTop">
	<select name='friend_id' id='friend_id' class="prMiddleFormItem">
	  {foreach key=id item=name from=$friendsList}
		<option value="{$id}">{$name|escape:"html"}</option>
	  {/foreach}
	</select>
	{t var="in_button_6"}Share List{/t}
	{linkbutton name=$in_button_6 link="javascript:void(0)" onclick="xajax_list_share("|cat:$list->getId()|cat:", 'user', document.getElementById('friend_id').options[document.getElementById('friend_id').selectedIndex].value); return false;"}
	</div>
</div>
{else}
<p class="prMarkRequired">{t}Note:{/t}{t}There are no friends for share this list{/t}</p>
{/if}
{*popup_item*}