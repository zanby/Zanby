{*popup_item*}
<p class="prIndentBottom">{t}{tparam value=$SITE_NAME_AS_STRING}Please enter the %s Username or the email address of the person who will become the organizer of this event.{/t}</p>
<p class="prIndentBottom">{t}{tparam value=$SITE_NAME_AS_STRING}Please discuss the proposed change with the person who will be the organizer of this event before you send this email. %s will send a message that explains the proposed transition.{/t}</p>
<p class="prIndentBottom">{t}If there is no response to the email, you will continue to be the host of this event until you cancel the event.{/t}</p>	
{form from=$form id="form_change_host" onsubmit=$linkUrl}
{form_errors_summary}
<label for="username">{t}Username:{/t}</label>	</td>
{form_text class="prMiddleFormItem" name="username" id="username" value=$formParams.username|escape:html}
<div class="prTCenter prIndentTop">
			{t var='button_01'}Send message{/t}
			{linkbutton color="blue" name=$button_01 onclick=$linkUrl}&#160;
			<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>
</div>			
{/form}
{*popup_item*}