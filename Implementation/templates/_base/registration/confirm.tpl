<div class="prInner">
	{form from=$form}
		<table cellpadding="0" cellspacing="0" border="0" class="prForm">
			<col width="35%" />
			<col width="30%" />
			<col width="35%" />
			<thead>
				<tr><th colspan="3">{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}</th></tr>
				<tr><th colspan="3">{form_errors_summary space_after=10}</th></tr>
			</thead>
			<tbody>
			<tr>
				<td class="prTRight"><span class="prMarkRequired">*</span> <label>{t}Username:{/t}</label></td>
				<td>{form_text name="name" value=$data.name|escape:html}</td>
				<td>&#160;</td>
			</tr>
			<tr class="prInnerBottom">
				<td class="prTRight"><span class="prMarkRequired">*</span> <label>{t}Password:{/t}</label></td>
				<td>{form_password name="pass" value=$data.pass|escape:"html"}</td>
				<td>&#160;</td>
			</tr>
			<tr>
				<td colspan="3" class="prTCenter prInnerTop prText4">{t}If you want to recieve confirmation mail to another email address please fill this:{/t}</td>
			</tr>
			<tr>
				<td class="prTRight"><label>{t}Email:{/t}</label></td>
				<td>{form_text name="email" value=$data.email|escape:"html"}</td>
				<td>&#160;</td>
			</tr>
			<tr>
				<td>&#160;</td>
				<td class="prInnerTop prTRight">
				{t var='button'}Submit{/t}
				{form_submit name="form_submit" value=$button}</td>
				<td>&#160;</td>
			</tr>
			<tbody>
		</table>
	{/form}
</div>