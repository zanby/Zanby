{*popup_item*}
{form from=$form id="form_message_to_organizer" onsubmit=$linkUrl}
<table class="prForm">
	<tr>
		<th colspan="2">
		{form_errors_summary}
		</th>
	</tr>    
    <tr>
        <td>
		<label class="prTBold" for="message">{t}Message:{/t}</label>
		<div class="prIndentTopSmall">
		{form_textarea name="message" id="message" value=$formParams.message rows="10"}
		</div>
		</td>
    </tr>    
    <tr>				
		<td class="prTCenter" colspan="2">	
			{t var="in_button"}Send Message{/t}			
			{linkbutton name=$in_button onclick=$linkUrl}                        
				<span class="prIEVerticalAling prIndentLeft">{t}or{/t} <a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>
		</td>
    </tr>
</table>
{/form}
{*popup_item*}