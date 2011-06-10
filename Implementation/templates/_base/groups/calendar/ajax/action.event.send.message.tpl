{*popup_item*}
{form from=$form id="form_message_to_guest" onsubmit=$linkUrl}
<p class="prText2">{t}The following message will be sent along with the event details.{/t}</p>
{form_errors_summary}
<table class="prForm">
	<tr>
		<td class="prTRight"><label for="event_invitations_from">{t}From:{/t}</label></td>
		<td>
		<select name="event_invitations_from">
			<option value="{$user->getEmail()}" {if $formParams.event_invitations_from == $user->getEmail()}selected{/if}>{$user->getEmail()}</option>
			<option value="{$currentGroup->getGroupEmail()}" {if $formParams.event_invitations_from == $currentGroup->getGroupEmail()}selected{/if}>{$currentGroup->getGroupEmail()}</option>
		</select>
       </td>
	</tr>
	<tr>
		<td class="prTRight"><label>{t}To:{/t}</label></td>			
		<td>
		{form_radio name="to" value="ALL" id="to_ALL" checked=$formParams.to|default:'ALL'} <label for="to_ALL">{t}Entire Guest List{/t}</label>
		<div class="prIndentTopSmall">
		{form_radio name="to" value="YES" checked=$formParams.to id="to_YES"} <label for="to_YES">{t}Only Guests that have RSVP'd YES{/t}</label>
		</div>
		<div class="prIndentTopSmall">
		{form_radio name="to" value="YES_MAYBY" checked=$formParams.to id="to_YES_MAYBY"} <label for="to_YES_MAYBY">{t}Guests that have RSVP'd YES or MAYBE{/t}</label></div>
		<div class="prIndentTopSmall">
		{form_radio name="to" value="YES_MAYBY_NONE" checked=$formParams.to id="to_YES_MAYBY_NONE"} <label for="to_YES_MAYBY_NONE">{t}Guests that have RSVP'd YES, MAYBE or Have not responded{/t}</label></div>
		</td>
	</tr>
	<tr>
		<td valign="top" class="prTRight"><label for="message">{t}Message:{/t}</label></td>		
		<td>{form_textarea name="message" id="message" value=$formParams.message rows="10"}</td>
	</tr>	
	<tr>		
		<td class="prTRight" colspan="2">
			{t var="in_button"}Send Message{/t}
			{linkbutton name=$in_button onclick=$linkUrl}
			<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>            
		</td>
	</tr>
</table>
{/form}
{*popup_item*}