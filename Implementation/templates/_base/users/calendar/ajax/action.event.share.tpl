{*popup_item*}
<p class="prText2">{t}Select a the group with which you would like to share this event{/t}</p>
<p class="prMarkRequired prInnerSmallTop">{t}[<span class="prMarkRequired">
               Note:</span> Only groups which you host, or in which the
                host has granted you permission to create event will appear
                in this field. if you do not see the desired group, you must contact
                the host to get permission to share your event]{/t}
</p>       
{if $groupsList}
	<div class="prInnerSmallTop">
		<select name='group_id' id='group_id' class="prMiddleFormItem">
			{foreach key=id item=name from=$groupsList}
			<option value="{$id}">{$name|escape:"html"}</option>
			{/foreach}
		</select>
		{t var='button_01'}Share Event{/t}
		{linkbutton name=$button_01 link="javascript:viod(0)" onclick=$linkAjaxShareGroups}
	</div>
{else}
	<p class="prInnerTop prMarkRequired">
	{if $groupsCount == 0}
		{t}You haven't joined or started any groups yet{/t}
	{else}
		{t}There are no groups for share this event{/t}
	{/if}
	</p>
{/if}
<p>{t}or{/t}</p>
<p class="prText2">{t}Select a friend with which you would like to share this event{/t}</p>

{if $friendsList}
	<div class="prInnerSmallTop">
		<select name='friend_id' id='friend_id' class="prMiddleFormItem">
			{foreach item=f from=$friendsList}
			<option value="{$f->getFriend()->getId()}">{$f->getFriend()->getLogin()|escape:"html"}</option>
			{/foreach}
		</select>
		{t var='button_02'}Share Event{/t}
		{linkbutton name=$button_02 link="javascript:viod(0)" onclick=$linkAjaxShareUsers}
	</div>
{else}
    <p class="prInnerTop prMarkRequired">
        	{if $friendsCount == 0}
        		{t}You haven't made any friends yet  {/t}       
            {else}
        		{t}There are no friends for share this event{/t}
            {/if}
     </p>
    {/if}
<div class="prTCenter prInnerTop">
	{t var='button_03'}Cancel and Close Window{/t}
	{linkbutton name=$button_03 link="javascript:viod(0)" onclick="popup_window.close(); return false;"}
</div>
{*popup_item*}