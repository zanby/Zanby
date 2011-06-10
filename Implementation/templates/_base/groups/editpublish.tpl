<div class="prTCenter"> <a href="{$CurrentGroup->getGroupPath('publish')}">{t}Publishing settings{/t}</a> | <a href="{$CurrentGroup->getGroupPath('editpublishstatus')}">{t}Publishing Status{/t}</a> </div>
{form from=$form}
	{if $save_status}
		<h3>{t}Settings Saved{/t}</h3>
	{/if}
	<p class="prInnerTop">{t}Publish your Summary page to an additional url.  Select the check box and complete the fields below then  click &ldquo;Save Settings.&rdquo; When you save changes to your group settings, or new content is introduced, your summary page will be published to the specified URL. To simply publish the summary page, please go to Publishing Status, and click &ldquo;Publish.&rdquo;{/t}</p>
		<table class="prForm">
			<col width="25%" />
			<col width="60%" />
			<col width="15%" />
			<tr>
				<th></th>
				<th>{form_errors_summary}
				</th>
				<th></th>
			</tr>
			<tr>
				<td></td>
				<td>
				{form_checkbox name="publishnow" id="publishnow" value="1" checked=$values.publishnow}<label for="publishnow"> {t}Publish my Group Summary to the URL below{/t}</label>
				</td>
				<td></td>
			</tr>
			<tr>
				<td class="prTRight"><label for="ftp_server">{t}FTP Server:{/t}</label>
				</td>
				<td> {form_text name="ftp_server" value=$values.ftp_server|escape:"html"}
					<div class="prIndentTopSmall">
					{form_checkbox name="ftp_mode" checked=$values.ftp_mode value=1} <label for="ftp_mode"> {t}Use passive mode{/t}</label>
					</div>
					<p class="prTip">Example: yourwebsite.com</p>
				</td>
			</tr>
			<tr>
				<td class="prTRight"><label for="ftp_folder">{t}FTP Folder:{/t}</label>
				</td>
				<td> {form_text name="ftp_folder" value=$values.ftp_folder|escape:"html"}
					<p class="prTip">{t}Example: htdocs/other {/t}</p>
				</td>
			</tr>
			<tr>
				<td class="prTRight">
				<label for="desturl1" class="">{t}Destination URL:{/t}</label>
				</td>
				<td>
					<p class="prTip">{t}The web address where this blog is viewable. This should include http://{/t}</p>
					<div class="prIndentTopSmall">
					{form_text name="desturl1" value=$values.desturl1|escape:"html"}
					</div>
				</td>
			</tr>
			<tr>
				<td class="prTRight"><label for="filename">{t}Filename:{/t}</label>
				</td>
				<td> {form_text name="filename" value=$values.filename|escape:"html"}
				<p class="prTip">{t}Example: Summary.html{/t} </p>
				<p class="prInnerSmallTop">{t}Warning: If this file already exists on your server in the path entered above, it will be OVERWRITTEN. Be sure to back it up. {/t}</p>
				</td>
			</tr>
			<tr>
				<td class="prTRight"><label for="ftp_username">{t}FTP Username:{/t}</label>
				</td>
				<td> {form_text name="ftp_username" value=$values.ftp_username|escape:"html"}
				</td> 
			</tr>
			<tr>
				<td class="prTRight"><label for="ftp_password">{t}FTP Password:{/t}</label>
				</td>
				<td>
				{form_password name="ftp_password" value=$values.ftp_password|escape:"html"}
				</td> 
			</tr>      	
			<tr>
				<td class="prTRight" colspan="2"> {t var="in_submit"}Save Settings{/t}{form_submit name="Save" value=$in_submit}</td>
			</tr>
		</table>
{/form}