{tab template="admin_subtabs" active='import_group'}
    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/importgroups/" name="import_group"}{t}Import groups{/t}{/tabitem}
{/tab}

 {form from=$form id="iuForm" name="iuForm" enctype="multipart/form-data"}

<div class="prDropBoxInner"> 
    {*<h2>{t}Import groups{/t}</h2>*}
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
				<label>{t}Group Category{/t}</label></td>
			<td> {form_select name="categoryId" options=$categories} </td>
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
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Who Can Join?{/t}</label></td>
			<td class="prTLeft"> {form_radio id="h1" name="hjoin" value="0" checked="0"}
				<label for="h1">{t}Anyone1{/t}</label>
				<br />
				{form_radio id="h2" name="hjoin" value="1" }
				<label for="h2">{t}Only those I approve{/t}</label>
				<br />
				{form_radio id="h3" name="hjoin" value="2" }
				<label for="h3">{t}Only those with a following code:{/t}</label>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight">&nbsp;</td>
			<td class="prTLeft"> {form_text name="jcode" value=""} </td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="prTLeft"> {form_checkbox id="is_private" name="is_private"}
				<label for="show_warnings">{t}Private content{/t}</label>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><label for="new_file">{t}Upload Groups list (CSV file):{/t}</label></td>
			<td class="prTLeft"><input id="new_file" name="new_file" type="file"/>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="prTLeft">{form_checkbox is="is_join_groups" name="is_join_groups" value=1 checked=$is_join_groups}
				<label for="is_join_groups">{t}Join Groups to Group Families{/t}</label>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><label>{t}Group Families:{/t}</label></td>
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
	<div class="prTCenter prIndentTop">{t var="in_submit"}Upload{/t}{form_submit name="form_upload" value=$in_submit}</div>
</div>
{/form}