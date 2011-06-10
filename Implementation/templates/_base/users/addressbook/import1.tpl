{if $action == "findfriends"}
	{t var="title"}My Friends{/t}
{else}
	{t var="title"}My Messages{/t}
{/if}
    {if $action=="findfriends"}
    <h3>{t}{tparam value=$BASE_HTTP_HOST}{tparam value=$LOCALE}To search for friends who are already members, <a href="http://%s/%s/users/">click here</a>{/t}</h3>
    {/if}

	<h3>{t}Import contacts from your web email{/t}</h3>
	<p>{t}{tparam value=$SITE_NAME_AS_STRING} Enter your email login and password below and we will tell you which of your contacts are already using %s.{/t}</p>
	{form from=$formWsLogin id="form1"}
		<table class="prForm">
			<col width="25%" />
			<col width="40%" />
			<col width="35%" />
			<thead>
				<tr><th colspan="3">
					{form_errors_summary}
				</th></tr>
			</thead>
			<tbody>
				<tr>
					<td class="prTRight"><label for="email">{t}Your Email:{/t}</label></td>
					<td>{form_text name="email" id="email"  value=$email|escape:"html"}</td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td class="prTRight"><label for="password">{t}Password:{/t}</label></td>
					<td>{form_password name="password" value=$password|escape:"html"}					
					</td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td class="prTRight"></td>
					<td class="prTip">
						{t}We won't store your login or password or email anyone without your permission.{/t}
					</td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td class="prTRight"></td>
					<td>
						{t var='buttom_01'}Find Friends{/t}
						{form_submit name="submit1" value=$buttom_01}
					</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	{/form}
	<h3 class="prInnerTop">{t}Import your contacts from your email client <small>(Outlook, Thunderbird, etc.)</small>{/t}</h3>
	{literal}
		<script type="text/javascript">
			function closeInstructions() {
				document.getElementById('instructions').style.display = 'none';
			}
		</script>
	{/literal}
	<p>{t}{tparam value=$SITE_NAME_AS_STRING}Upload a contacts file and we will tell you which of your contacts are already using %s. For instructions on creating a contacts file from your email client, <a href="#null" onclick="xajax_addressbook_instruction('outlook');">click here</a>.{/t}</p>
	{form from=$formFile enctype="multipart/form-data"}
		<div id="file_import_instruction"></div>
		<table class="prForm prClr2">
			<col width="25%" />
			<col width="40%" />
			<col width="35%" />
			<thead>
				<tr><th colspan="3">
					{form_errors_summary}
				</th></tr>
			</thead>
			<tbody>
				<tr>
					<td class="prTRight"><label for="file_type">{t}File type:{/t}</label></td>
					<td>{form_select name="file_type" options=$fileTypes selected="$file_type"}</td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td class="prTRight"><label for="copntacts_file">{t}Contacts File:{/t}</label></td>
					<td>{form_file name="copntacts_file"}</td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td class="prTRight"></td>
					<td class="prTip">
						{t}We won't  email anyone without<br />
						your permission.{/t}
					</td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td class="prTRight"></td>
					<td>
					{t var='button_02'}Find Friends{/t}
					{form_submit name="submit2" value=$button_02}</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	{/form}
