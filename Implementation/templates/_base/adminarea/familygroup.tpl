{tab template="admin_subtabs" active='group_details'}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groups/id/`$groupID`/" name="group_details"}{t}Group details{/t}{/tabitem}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groupMembers/id/`$groupID`/" name="group_members"}{t}Group members{/t}{/tabitem}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groupFamilyMembership/id/`$groupID`/" name="group_family_membership"}{t}Group Family membership{/t}{/tabitem}
{/tab}

<div class="prDropBoxInner">
{form from=$form id="gdForm" name="gdForm"}
{form_errors_summary}
<div class="prTLeft prText5 prIndentBottom">{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}</div>
<table cellpadding="0" cellspacing="0" border="0" class="prForm">
	<col width="30%" />
	<col width="40%" />
	<col width="30%" />
	<tr>
		<td class="prTRight"><label>{t}Group ID:{/t}</label></td>
		<td class="prTLeft">{$group->getId()|escape:"html"}</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}Group Name:{/t}</label></td>
		<td class="prTLeft"> {form_text name="gname" value=$group->getName()|escape:"html"} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}Group Address:{/t}</label></td>
		<td class="prTLeft"> {form_text id="gemail" name="gemail" value=$group->getPath()} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}Group Category:{/t}</label></td>
		<td class="prTLeft"> {form_select name="categoryId" selected=$group->getCategoryId() options=$categories} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}Description:{/t}</label></td>
		<td class="prTLeft"> {form_textarea name="description" value=$group->getDescription()|escape:"html" style="height:10em;"} </td>
		<td class="prTLeft prText5">{t}2000 Characters Avaible{/t}</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}Tags:{/t}</label></td>
		<td class="prTLeft"> {form_text name="tags" value=$tags|escape:"html"} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><label>{t}Company:{/t}</label></td>
		<td class="prTLeft"> {form_text name="company" value=$group->getCompany()|escape:"html"} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><label>{t}Position:{/t}</label></td>
		<td class="prTLeft"> {form_text name="position" value=$group->getPosition()|escape:"html"} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}Address1:{/t}</label></td>
		<td class="prTLeft"> {form_text name="address1" value=$group->getAddress1()|escape:"html"} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><label>{t}Address2:{/t}</label></td>
		<td class="prTLeft"> {form_text name="address2" value=$group->getAddress2()|escape:"html"} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>Country:</label></td>
		<td class="prTLeft"> {form_select id="countryId" name="country" selected=$country options=$countries onchange="xajax_changeCountry(this.options[this.selectedIndex].value);"} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}State/Province:{/t}</label></td>
		<td class="prTLeft"> {form_select id="stateId" name="state" selected=$state options=$states onchange="xajax_changeState(this.options[this.selectedIndex].value);"} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}City:{/t}</label></td>
		<td class="prTLeft"> {form_select id="cityId" name="city" selected=$city options=$cities} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><label>{t}Zip Postal Code:{/t}</label></td>
		<td class="prTLeft"> {form_text id=zipId name="zipId" value=$group->getZipCode()|escape:"html"} </td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}Who can join?{/t}</label></td>
		<td class="prTLeft"> {form_radio id="h1" name="hjoin" value="0" checked=$group->getJoinMode()|default:"0"}
			<label for="h1">{t}Anyone{/t}</label>
			<br />
			{form_radio id="h2" name="hjoin" value="1" checked=$group->getJoinMode()}
			<label for="h2">{t}Only those I approve{/t}</label>
			<br />
			{form_radio id="h3" name="hjoin" value="2" checked=$group->getJoinMode()}
			<label for="h3">{t}Only those with a following code:{/t}</label>
			<div>{form_text name="jcode" value=$group->getJoinCode()|escape:"html"}</div></td>
		<td>&nbsp;</td>
	</tr>
</table>
<div class="prTCenter prIndentTop">{t var="in_submit"}Save Changes{/t}{form_submit name="form_save" value=$in_submit}</div>
{/form}
</div>