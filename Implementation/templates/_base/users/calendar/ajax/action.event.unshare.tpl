{*popup_item*}
<p class="prText2">{t}Select a the group with which you would like to unshare this event{/t}</p>
{if $groupsList}
	<div class="prInnerSmallTop">
		<select name='group_id' id='group_id' class="prMiddleFormItem">
			{foreach item=g from=$groupsList}
				{assign var="checkId" value="family_"|cat:$g->getId()}
                {if $familySharedWith[$checkId]}
                    <option value="{$checkId}">{$familySharedWith[$checkId]|escape:"html"}</option>
                {else}
                    <option value="{$g->getId()}">{$g->getName()|escape:"html"}</option>
                {/if}
			{/foreach}
		</select>
		{t var='button_01'}Unshare Event{/t}
		{linkbutton name=$button_01 link="javascript:void(0)" onclick=$linkAjaxShareGroups}
	</div>
{else}
    <p class="prInnerTop prMarkRequired">{t}There are no groups for unshare this event{/t}</p>
    
{/if}
<p>{t}or{/t}</p>
<p class="prText2">{t}Select a friend with which you would like to unshare this event{/t}</p>        
    
{if $friendsList}
<div class="prInnerSmallTop">
	<select name='user_id' id='user_id' class="prMiddleFormItem">
		{foreach item=u from=$friendsList}
		<option value="{$u->getId()}">{$u->getLogin()|escape:"html"}</option>
		{/foreach}
	</select>
	{t var='button_02'}Unshare Event{/t}
	{linkbutton name=$button_02 link="javascript:void(0)" onclick=$linkAjaxShareUsers}
</div>
{else}
	<p class="prInnerTop prMarkRequired">{t}There are no friends for unshare this event{/t}</p>   
{/if}
<div class="prTCenter prInnerTop">
	{t var='button_03'}Cancel and Close Window{/t}
	{linkbutton name=$button_03 link="javascript:void(0)" onclick="popup_window.close(); return false;"}
</div>
{*popup_item*}