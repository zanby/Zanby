{*popup_item*}
{form from=$form id="form_remove_guest" onsubmit=$linkUrl}
<p class="prText2">{t}Select the guest(s) you wish to remove{/t}</p>
<div class="prIndentTopSmall prPopupHeight2">	
	<table class="prForm">
		{foreach from=$objEvent->getAttendee()->setFetchMode('object')->getList() item='attendee'}
			<tr>
				{if $attendee->getOwnerType() == 'user'}
					<td>
						{if $attendee->getOwner()->getId() == $objEvent->getCreator()->getId() || ($objEvent->getOwnerType() == 'user' && $objEvent->getOwnerId() == $attendee->getOwner()->getId() )}
							&nbsp;&nbsp;
						{else}
							{form_checkbox name="attendee[]"  value=$attendee->getId()}
						{/if}
					</td>
					{if null !== $attendee->getOwner()->getId()}
						<td><img src="{$attendee->getOwner()->getAvatar()->setWidth(40)->setHeight(40)->getImage()}" /></td>
						<td>
							{$attendee->getOwner()->getFirstname()|escape:html}&nbsp;{$attendee->getOwner()->getLastname()|escape:html}<br/>
							{t}Username :{/t} <a href="{$attendee->getOwner()->getUserPath('profile')}">{$attendee->getOwner()->getLogin()|escape:html}</a>
						</td>
						<td>
							{$attendee->getOwner()->getEmail()|truncate:45}
						</td>
					{else}
						<td><img src="{$attendee->getOwner()->getAvatar()->setWidth(40)->setHeight(40)->getImage()}" /></td>
						<td>{t}N/A{/t}</td>
						<td>{$attendee->getOwner()->getEmail()|truncate:45}</td>
					{/if}
				{elseif FACEBOOK_USED && $attendee->getOwnerType() == 'fbuser'}
					<td>
                        {form_checkbox name="attendee[]"  value=$attendee->getId()}
					</td>
						<td><fb:profile-pic uid="{$attendee->getOwnerId()}" facebook-logo="true" linked="false" /></td>
						<td>{$attendee->getName()|escape}</td>
						<td>&nbsp;</td>
				{else}
				{/if}
			</tr>
		{foreachelse}
			<tr>
				<td class="prTCenter">{t}No guest{/t}</td>
			</tr>
		{/foreach}
	</table>
</div>

<div class="prClr">
	<div class="prInnerTop prFloatLeft">
			<a href="#null" onclick="show_MessageBox(); return false;" id="showMessageBoxLink">{t}+ Send message to removed guests{/t}</a>
			<a href="#null" style="display:none" onclick="show_MessageBox(); return false;" id="hideMessageBoxLink">{t}- Close Message{/t}</a>
	</div>
	<div class="prInnerTop prFloatRight prButtonPanel" id="ButtonsSet">
	{t var='button_01'}Remove Guests{/t}
			{linkbutton color="blue" name=$button_01 onclick=$linkUrl} <span class="prIEVerticalAling">{t}or{/t} 
			<a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>
	</div>
</div>
	
	<div style="display:none;" id="AddSendMessageBox">
		<p class="prInnerTop prText2">{t}Send Message to the guest(s) you are removing{/t}</p>
		<table class="prForm">
			<col width="22%" />
			<col width="78%" />
			<tr>
				<td class="prTRight"><label>{t}From:{/t}</label></td>				
				<td>
					{$user->getEmail()}
					{form_hidden name="from" value=$user->getId()}
				</td>
			</tr>
			<tr>
				<td class="prTRight"><label for="message">{t}Message:{/t}</label></td>				
				<td>
					{form_textarea name="message" value=$all_data.message rows="5"}
				</td>
			</tr>
			<tr>
				<td colspan="2" class="prTRight">
				{t var='button_02'}Send Message and Remove Guests{/t}
					{linkbutton name=$button_02 onclick=$linkUrl} <span class="prIEVerticalAling">{t}or{/t} 
					<a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>
				</td>
			</tr>
		</table>
	</div>
{/form}
{*popup_item*}