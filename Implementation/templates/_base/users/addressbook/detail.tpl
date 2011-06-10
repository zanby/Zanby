<div class=" tab2_header">{t}My Messages{/t}</div>
<table width="100%">
	<tr>
		<td>
			{t}Detail{/t} - {$detail->firstName} {$detail->lastName}
		</td>
		<td align="right">
			<input type="button" value="Add Contact" onclick="javascript: window.location.href = '{$user->getUserPath('addressbook/addcontact')}';" />&nbsp;
			<input type="button" value="Add Mailing List" onclick="javascript: window.location.href = '{$user->getUserPath('addressbook/addmaillist')}';"/>
		</td>
	</tr>
	<tr>
		<td><a href="{$user->getUserPath('addressbook')}">&laquo; {t}Back to Addressbook{/t}</a></td>
		<td></td>
	</tr>
</table>
<div class='prAddressBookLeft'>
{form from=$formEditContact}
{form_hidden name="item[id]" value=$detail->id|escape:"html"}
<table width="100%">
<tr><td><table width="50%" border="0" cellpadding="0" cellspacing="3">
			<tr><th>{t}Name / Email addresses{/t}</th></tr>
			<tr><td><table width="100%" cellpadding="0" cellspacing="3">
						<tr><td>{t}First{/t}</td><td>{t}Last{/t}</td>
						</tr>
						<tr><td>{form_text name="item[firstName]" value=$detail->firstName|escape:"html"}</td>
							<td>{form_text name="item[lastName]" value=$detail->lastName|escape:"html"}</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td>{t}Email Address{/t}</td></tr>
			<tr><td>{form_text name="item[email]" value=$detail->email|escape:"html"}</td></tr>
			<tr><td>{t}Secondary Email Address{/t}</td></tr>
			<tr><td>{form_text name="item[email2]" value=$detail->email2|escape:"html"}</td></tr>
		</table>
	</td>
</tr>
<tr><td><table width="50%" border="0" cellpadding="0" cellspacing="3">
			<tr><th>{t}Phones{/t}</th></tr>
			<tr><td>{t}Home{/t}</td></tr>
			<tr><td>{form_text name="item[phoneHome]" value=$detail->phoneHome|escape:"html" }</td></tr>
			<tr><td>{t}Business{/t}</td></tr>
			<tr><td>{form_text name="item[phoneBusiness]" value=$detail->phoneBusiness|escape:"html" }</td></tr>
			<tr><td>{t}Mobile{/t}</td></tr>
			<tr><td>{form_text name="item[phoneMobile]" value=$detail->phoneMobile|escape:"html" }</td></tr>
		</table> 
	 </td>
</tr>
<tr>
	<td><table width="50%" border="0" cellpadding="0" cellspacing="3">
			<tr><th>{t}Address{/t}</th></tr>
			<tr><td>{t}Street{/t}</td></tr>
			<tr><td>{form_text name="item[street]" value=$detail->street|escape:"html" }</td></tr>
			<tr><td><table width="100%" cellpadding="0" cellspacing="3">
						<tr><td>{t}City{/t}</td><td>{t}State{/t}</td><td>{t}Zip{/t}</td>
						</tr>
						<tr><td>{form_text name="item[city]" value=$detail->city|escape:"html" }</td>
							<td>{form_text name="item[state]" value=$detail->state|escape:"html" }</td>
							<td>{form_text name="item[zip]" value=$detail->zip|escape:"html" }</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td>{t}Country{/t}</td></tr>
			<tr><td>{form_text name="item[country]" value=$detail->country|escape:"html" }</td></tr>
		</table>
	</td>
</tr>
<tr>
	<td><table width="50%" border="0" cellpadding="0" cellspacing="3">
			<tr><th>{t}Notes{/t}</th></tr>
			<tr><td>{t}Notes{/t}</td></tr>
			<tr><td>{form_textarea  name="item[notes]" value=$detail->notes|escape:"html" }</td></tr>
		</table> 
	</td>
</tr>
<tr>
	<td>
		{t var='button'}Save Changes{/t}
		{form_submit value=$button name="submit"}
	</td>
</tr>
</table>
{/form}
</div>
<div class='prAddressBookRight'>
{include file="users/addressbook/mailinglist.tpl"}
</div>    