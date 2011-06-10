{*popup_item*}
{form from=$form id="form_message_to_organizer"}
{form_errors_summary}
<table class="prForm">
    <tr>
        <td>
		<label for="message">{t}Message:{/t}</label>
		<div class="prIndentTopSmall">
		{form_textarea name="message" id="message" value=$formParams.message rows="10"}
		</div>
		</td>
    </tr>   
   <tr>				
		<td colspan="2" class="prTCenter">
		{t var='button'}Send Message{/t}	
         {linkbutton name=$button onclick=$linkUrl}
		 <span class="prIndentLeftSmall prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>
        </td>
    </tr>
</table>
{/form}
{*popup_item*}