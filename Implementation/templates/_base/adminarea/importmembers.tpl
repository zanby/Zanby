{tab template="admin_subtabs" active='import_members'}
    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/importmembers/" name="import_members"}{t}Import members{/t}{/tabitem}
{/tab}

{form from=$form id="iuForm" name="iuForm" enctype="multipart/form-data"}
<div class="prDropBoxInner"> 
    {* <h2>{t}Import members{/t}</h2> *}
    {form_errors_summary}
	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="prForm">
		<col width="35%" />
		<col width="30%" />
		<col width="35%" />
		<tr>
			<td>&nbsp;</td>
			<td class="prTLeft">{form_checkbox id="is_gender_private" name="is_gender_private" value=1 checked=$defaultSet->getIsGenderPrivate()}
				<label for="is_gender_private">{t}Keep gender private{/t}</label>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="prTLeft">{form_checkbox id="is_birthday_private" name="is_birthday_private" value="1" checked=$defaultSet->getIsBirthdayPrivate()}
				<label for="is_birthday_private">{t}Keep birthday private{/t}</label></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Country:{/t}</label></td>
			<td> {form_select id="countryId" name="country" options=$countries onchange="xajax_changeCountry(this.options[this.selectedIndex].value);" selected=$country } </td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}State\Province:{/t}</label></td>
			<td> {form_select id="stateId" name="state" options=$states onchange="xajax_changeState(this.options[this.selectedIndex].value);" selected=$state } </td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}City:{/t}</label></td>
			<td>{form_select id="cityId" name="city" options=$cities  selected=$city } </td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><label>{t}Zip code/Postal code:{/t}</label></td>
			<td class="prTLeft">{form_text id=zipId name="zipcode" value=$defaultSet->getZipcode()|escape:"html" }</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><label>{t}Timezone{/t}</label></td>
			<td>{form_select name="timezone" selected=$defaultSet->getTimezone() options=$time_zones } </td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><label for="new_file">{t}Upload members list (CSV file):{/t}</label></td>
			<td class="prTLeft"><input id="new_file" name="new_file" type="file" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="prTLeft">{form_checkbox is="is_join_col" name="is_join_col" value=1 checked="0"}
				<label for="is_join_col">{t}Join members to header specified groups by a matrix rule{/t}</label>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="prTLeft">{form_checkbox is="is_join_groups" name="is_join_groups" value=1 checked=$is_join_groups}
				<label for="is_join_groups">{t}Join all members also to these groups:{/t}</label>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><label>{t}Groups names:{/t}</label></td>
			<td align="left">{form_text id="group_names" name="group_names" value=$group_names|escape:"html"}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="prTLeft">{form_checkbox id="is_show_warnings" name="is_show_warnings" value=1 checked=$is_show_warnings}
				<label for="show_warnings">{t}Show all warnings{/t}</label>
			</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<div class="prIndentTop prIndentBottom">{t var="in_submit"}Upload{/t}{form_submit name="form_upload" value=$in_submit}</div>
	<h3>{t}Latest imported lists{/t}</h3>
	<table cellpadding="0" cellspacing="0" border="0" class="prForm">
		<col width="20%" />
		<col width="20%" />
		<col width="20%" />
		<col width="20%" />
		<col width="20%" />
		<tr>
			<th>&nbsp;</th>
			<th class="prText2">{t}Transactions date & time{/t}</th>
			<th class="prText2">{t}Number{/t}</th>
			<th class="prText2">{t}Allowed action{/t}</th>
			<th>&nbsp;</th>
		</tr>
		{foreach item=t from=$transactions}
		<tr>
			<td>&nbsp;</td>
			<td class="prTCenter">{$t.imported_user}</td>
			<td class="prTCenter">{$t.users_count} user(s)</td>
			<td class="prTCenter"><a href="{$admin->getAdminPath('deleteimportedusers/id/')}{$t.imported_user}/">{t}Delete transaction{/t}</a></td>
			<td>&nbsp;</td>
		</tr>
		{/foreach}
	</table>
</div>
{/form} 