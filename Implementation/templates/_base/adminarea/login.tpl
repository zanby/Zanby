<div class="prAdminLoginBlock">
<h2>{t}Adminarea Login{/t}</h2>
	{form from=$form id="loginForm"}
		<table class="prForm">
			<col width="25%" />
			<col width="75%" />		
			<thead>
				<tr><th colspan="3">
					{capture name="form_error"}{form_errors_summary}{/capture}
					{if $smarty.capture.form_error}
						{$smarty.capture.form_error}
					{/if}
				</th></tr>
			</thead>
			<tbody>
				<tr>
					<td class="prTRight"><label>{t}Username:{/t}</label></td>
					<td>{form_text name="login" value=$login|escape:"html" style="position: static;"}</td>				
				</tr>
				<tr>
					<td class="prTRight"><label>{t}Password:{/t}</label></td>
					<td>{form_password name="password"}</td>
				</tr>
				<tr>
					<td colspan="2" class="prTCenter">
						{t var="in_submit"}Login{/t}{form_submit value=$in_submit name="form_login"}
					</td>
				</tr>            
			</tbody>
		</table>
	{/form}	
</div>