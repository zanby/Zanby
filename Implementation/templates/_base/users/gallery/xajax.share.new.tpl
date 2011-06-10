{*popup_item*}
<div id="shareFilePanelSharedGroups" class="prClr3">
	{foreach item=g key=id from=$groupsSharedWith}
	<div id="groupShare_{$id}_{$gallery->getId()}" class="prClr3 prInnerSmallTop">
		<div class="prFloatLeft prIndentRightSmall">{t}{tparam value=$g|escape:"html"}Shared with %s{/t}</div>
		<a class="prFloatRight" href="javascript:void(0)" onclick="xajax_unshare_group_do('{$gallery->getId()}', '{$id}','{$JsApplication}'); return false;">{t}Unshare{/t}</a>
	</div>
	{/foreach}
</div>
<div id="shareFilePanelSharedUsers" class="prClr3">
	{foreach item=u key=id from=$usersSharedWith}
	<div id="userShare_{$id}_{$gallery->getId()}" class="prClr3 prInnerSmallTop">
		<div class="prFloatLeft prIndentRightSmall">{t}{tparam value=$u|escape:"html"}Shared with %s{/t}</div>
		<a class="prFloatRight" href="javascript:void(0)" onclick="xajax_unshare_friend_do('{$gallery->getId()}', '{$id}','{$JsApplication}'); return false;">{t}Unshare{/t}</a>
	</div>
	{/foreach}
</div>

<p class="prMarkRequired">{t}Note:{/t}
	{t}Only groups which you host, or in which the host has granted you 
	permission to upload photos will appear in this field. 
	If you do not see the desired group, you must contact the 
	host to get permission to share your photo.{/t}</p>
<div class="prIndentBottom">
{foreach key=id item=g from=$groupsNotSharedWith}
	{if $nonegroups != true}
		<label for="share_group_id">{t}Select a group to share this gallery:{/t}</label>
	   <div class="prInnerSmallTop">
		<select name='group_id' id='group_id' class='prMiddleFormItem'>        
		
	{/if}
	{assign var="nonegroups" value=true}
	<option value="{$id}" {if $selectedgroup == $id}selected{/if}>{$g|escape:"html"}</option>
  {/foreach}
  {if $nonegroups == true}  
	</select>	
	{t var='button_01'}Share Gallery{/t}	
	{linkbutton name=$button_01 link="#" onclick="xajax_share_group_do("|cat:$gallery->getId()|cat:", document.getElementById('group_id').options[document.getElementById('group_id').selectedIndex].value, '"|cat:$JsApplication|cat:"'); return false;"} 
	</div>       
  {/if}        
{if $nonegroups != true}
<span class="prMarkRequired">{t}Note:{/t}{t}There are no groups for share this gallery{/t}</span>
{/if}
</div>

<div class="prIndentBottom">
  {foreach key=id item=u name=friends from=$usersNotSharedWith}
	{if $nonefriends != true}
		<label for="friend_id">{t}Select a friend to share this gallery:{/t}</label>
		 <div class="prInnerSmallTop">
		<select name='friend_id' id='friend_id' class="prMiddleFormItem">          	
	{/if}
	{assign var="nonefriends" value=true}
	<option value="{$u->getId()}">{$u->getLogin()|escape:"html"}</option>
  {/foreach}
{if $nonefriends == true}
</select>
{t var='button_03'}Share Gallery{/t}
{linkbutton name=$button_03 link="#" onclick="xajax_share_friend_do("|cat:$gallery->getId()|cat:", document.getElementById('friend_id').options[document.getElementById('friend_id').selectedIndex].value, '"|cat:$JsApplication|cat:"'); return false;"}
</div>
{/if} 
</div>       
{if $nonefriends != true}
<span class="prMarkRequired">{t}Note:{/t}{t}There are no friends for share this gallery{/t}</span>
{/if}
<div class="prInnerTop">
{t var='button_04'}Close{/t}
{linkbutton name=$button_04 link="#null" onclick="popup_window.close();"}
</div>
{*popup_item*}