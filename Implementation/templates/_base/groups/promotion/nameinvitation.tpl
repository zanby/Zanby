{form from=$form name="inForm" id="inForm"}
<table class="prForm">
	<tr>
		<th>{form_errors_summary}
		</th>
	</tr>
	<tr>
		<td><label for="inv_name">{t}Enter please name of current Invitation{/t}</label>
		<div>
		{form_text name="inv_name" value=$inv_name}
		</div>
		</td>
	</tr>
	<tr>
		<td class="prTCenter">
		{t var="in_button"}Ok{/t}
		{linkbutton name=$in_button onclick="xajax_nameInvitation(xajax.getFormValues('inForm')); return false;"}
		<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
		</td>
	</tr>
</table>
{/form}
