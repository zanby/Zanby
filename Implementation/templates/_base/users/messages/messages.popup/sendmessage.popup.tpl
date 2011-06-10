{*popup_item*}
{form from=$form id="sendMessageForm"}
<div class="prClr3">
<table class="prForm">
	<col width="15%"/>
	<col width="85%"/>
	<tr>
		<th colspan="2">
		{form_errors_summary}
		{form_hidden id="userId" name="userId" value="$userId"}
		</th>
	</tr>
    {if $additionalText}
	<tr>
		<td class="prTCenter" colspan="2"><b>{$additionalText|escape:"html"}</b></td>
	</tr>
    {/if}
	<tr>
		<td class="prTRight"><label for="subject">{t}Subject:{/t}</label></td>
		<td>{form_text id="subject" name="subject" value=$subject|escape:"html"}</td>
	</tr>
	<tr>
		<td class="prTRight"><label for="message">{t}Text:{/t}</label> </td>
		<td>{form_textarea name="message" id="message" value=$message}</td>
	</tr>
	<tr>
		<td class="prTCenter" colspan="2">
        <input type="hidden" id="send_xlink" value='1' />
        <span class="prIndentLeftSmall">
		{t var='button'}Send{/t}
		{linkbutton onclick = "if(document.getElementById('send_xlink').value=='1')xajax_sendMessageDo(xajax.getFormValues('sendMessageForm'));document.getElementById('send_xlink').value='0';popup_window.close();return false;" id="SendMessageOK" name=$button}</span> 
		<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
		</td>
	</tr>
</table>
</div>
{/form}
{*popup_item*}
