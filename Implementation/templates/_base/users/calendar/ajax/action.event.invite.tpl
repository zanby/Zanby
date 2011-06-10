{*popup_item*}
{form from=$form id="form_invite_more_people" onsubmit=$linkUrl}
{form_hidden name="form_submit" value="1"}
{form_errors_summary}
<table class="prForm" id="invite-guests-options-full">
	<col width="20%" />
	<col width="80%" />
	<tr>
		<td class="prTRight"><label>{t}From:{/t}</label></td>
		<td>
			{if $Warecorp_ICal_AccessManager->canManageEvent($objEvent, $currentUser, $user)}
				<span>{$user->getEmail()}</span>
				{form_hidden name="event_invitations_from" value=$user->getId()}

			{else}
				<span>{$user->getEmail()}</span>
				{form_hidden name="event_invitations_from" value=$user->getEmail()}

			{/if}
		</td>
	</tr>
    {if FACEBOOK_USED}
        <tr class="prInnerTop">  
            <td></td>
            <td>
            <a href="javascript:void(0)" onclick="FBApplication.oninvite_friends_toevent('external'); return false;">
                {t}Invite Friend from Facebook{/t}
            </a>
            <img alt="" src="{$AppTheme->images}/decorators/icons/icoFB_small.gif" class="prIndentTop" />
            </td>
        </tr>
    {/if}
    {if FACEBOOK_USED}
        <tr class="prInnerTop" style="{if !$formParams.event_invitations_fbfriends}display:none{/if}" id="EventInviteFBFriendsObjects">
            {include file="facebook/invitefriends.template.invited.tpl"}
        </tr>
    {/if}
	<tr>
		<td class="prTRight"><label for="event_invitations_emails">{t}To:{/t}</label></td>
		<td>
			<div class="prText5">{t}{tparam value=$SITE_NAME_AS_STRING}Enter an email address or a %s username, separated by a comma.{/t}</div>
			{form_textarea name="event_invitations_emails" value=$formParams.event_invitations_emails|escape id="event_invitations_emails"}
		</td>
	</tr>
	<tr>
		<td class="prTRight"><label for="event_invitations_subject">{t}Subject:{/t}</label></td>
		<td>{form_text name="event_invitations_subject" value=$formParams.event_invitations_subject|escape}</td>
	</tr>
	<tr>
		<td class="prTRight"><label for="event_invitations_message">{t}Message:{/t}</label></td>
		<td>{form_textarea name="event_invitations_message" value=$formParams.event_invitations_message|escape rows="9" id="event_invitations_message"}</td>
	</tr>
	<tr>
		<td colspan="2" class="prTRight">
		{t var='button'}Send message{/t}
		{linkbutton color="blue" name=$button onclick=$linkUrl} <span class="prIEVerticalAling">{t}or{/t} 
			<a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>
			
		</td>
	</tr>
</table>
{/form}
{*popup_item*}