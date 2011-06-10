{literal}
<style>
    .yui-ac-bd { height: 150px; overflow:auto; text-align:left;}
    #acCity .yui-ac-content .yui-ac-bd { background-color:#FFFFFF; text-align:left; }
    #acZip .yui-ac-content .yui-ac-bd { background-color:#FFFFFF; text-align:left; }
</style>
{/literal}

<div class="prDropBoxInner">
	<h3 class="prTLeft">System settings:</h3>
 {form from=$form id="settingsForm"}
	{form_errors_summary}
	<div class="prText5 prTLeft">{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}</div>
	<table class="prFullWidth">
		<col width="50%" />
		<col width="50%" />
		<tr>
			<td align="left"><table width="100%" cellpadding="0" cellspacing="0" class="prForm">
					<col width="30%" />
					<col width="70%" />
					<tr>
						<td class="prTRight">
							<label>{t}Tracer code:{/t}</label></td>
						<td class="prTLeft">{form_textarea name="tracer_code" value=$tracer_code style="height:10em;"}</td>
					</tr>
				</table></td>
		</tr>
	</table>
	<div class="prTCenter prIndentTop">{t var="in_submit"}Save{/t}{form_submit name="form_save" value=$in_submit}</div>
	{/form} </div>
