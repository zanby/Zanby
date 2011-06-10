{*popup_item*}
<label for="group_id">{t}Select a group with which you would like to unshare this list{/t}</label>
{if $groupsList}
	<select name='group_id' id='group_id' class=" prInnerSmallTop">
		{foreach item=g from=$groupsList}
		<option value="{$g->getId()}">{$g->getName()|escape:"html"}</option>
		{/foreach}
	</select>
	<span class="prIndentLeftSmall prInnerSmallTop">
	{t var='button_01'}Unshare List{/t}
    {linkbutton name=$button_01 link="#" onclick="xajax_list_unshare("|cat:$list->getId()|cat:", 'group', document.getElementById('group_id').options[document.getElementById('group_id').selectedIndex].value); return false;"}</span>
{else}
   	<p>{t}There are no groups for unshare this list{/t}</p>
{/if}
<p>
{t}or{/t}
</p>

<label for="user_id">
{t}Select a friend with which you would like to unshare this list{/t}
</label>

{if $friendsList}
	
	<select name='user_id' id='user_id' class=" prInnerSmallTop">
		{foreach item=u from=$friendsList}
		<option value="{$u->getId()}">{$u->getLogin()|escape:"html"}</option>
		{/foreach}
	</select>
	<span class="prIndentLeftSmall prInnerSmallTop">
	{t var='button_02'}Unshare List{/t}
		{linkbutton name=$button_02 link="#" onclick="xajax_list_unshare("|cat:$list->getId()|cat:", 'user', document.getElementById('user_id').options[document.getElementById('user_id').selectedIndex].value); return false;"}</span>
	
{else}
	<p>{t}There are no friends for unshare this list{/t}</p>    
{/if}
 
<p class="prTRight"><a href="#" onclick="xajax_list_unshare_popup_close(); return false;">{t}Cancel and Close Window{/t}</a>
</p>
{*popup_item*}