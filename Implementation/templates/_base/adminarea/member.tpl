<link type="text/css" rel="stylesheet" href="{$AppTheme->css}/yui-autocomplete.css">
<script type="text/javascript" src="/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/js/yui/connection/connection-min.js"></script>
<script type="text/javascript" src="/js/yui/autocomplete/autocomplete-min.js"></script>
<script type="text/javascript" src="{$AppTheme->common->js}/modules/adminarea/member.details.js"></script>
{literal}
<style>
    .yui-ac-bd { height: 150px; overflow:auto; text-align:left;}
    #acCity .yui-ac-content .yui-ac-bd { background-color:#FFFFFF; text-align:left; }
    #acZip .yui-ac-content .yui-ac-bd { background-color:#FFFFFF; text-align:left; }
</style>
{/literal}

{tab template="admin_subtabs" active='member_details'}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/members/id/`$memberID`/" name="member_details"}{t}User details{/t}{/tabitem}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/memberGroupMembership/id/`$memberID`/" name="member_group_membership"}{t}Group membership{/t}{/tabitem}
{/tab}
				
<div class="prDropBoxInner">
	<h3 class="prTLeft">User Username details:</h3>
 {form from=$form id="udForm"}
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
						<td class="prTRight"><span class="prMarkRequired">*</span>
							<label>{t}ID in DataBase:{/t}</label></td>
						<td class="prTLeft">{$user->getId()|escape:"html"}</td>
					</tr>
					<tr>
						<td class="prTRight"><span class="prMarkRequired">*</span>
							<label>{t}First Name:{/t}</label></td>
						<td class="prTLeft">{form_text name="firstname" value=$user->getFirstname()|escape:"html"}</td>
					</tr>
					<tr>
						<td class="prTRight"><span class="prMarkRequired">*</span>
							<label>{t}Last Name:{/t}</label></td>
						<td class="prTLeft">{form_text name="lastname" value=$user->getLastname()|escape:"html"}</td>
					</tr>
					<tr>
						<td class="prTRight"><label>{t}Gender:{/t}</label>
						</td>
						<td> {form_select name="gender" options=$genderArray selected=$user->getGender()} </td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="left">{form_checkbox id="is_gender_private" name="is_gender_private" value=1 checked=$user->getIsGenderPrivate()}
							<label for="is_gender_private">{t}Keep gender private{/t}</label>
						</td>
					</tr>
					<tr>
						<td class="prTRight"><label>{t}Birthday:{/t}</label></td>
						<td> {form_select_date start_year="1900" end_year="2000" prefix="date_" field_array="birthday" time=$user->getBirthday()} </td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="left">{form_checkbox id="is_birthday_private" name="is_birthday_private" value="1" checked=$user->getIsBirthdayPrivate()}
							<label for="is_birthday_private">{t}Keep birthday private{/t}</label></td>
					</tr>
					
					
			        {* LOCATION *}              
			        <tr>
			            <td class="prTRight"><span class="prMarkRequired">*</span>
			                <label>{t}Country:{/t}</label></td>
			            <td class="prTLeft"> {form_select id="countryId" name="countryId" selected=$countryId options=$countries onchange="xajax_detectCountry(this.options[this.selectedIndex].value);"} </td>
			        </tr>
			        <tr id="LocationTrZip" {if !$countryId || ($countryId != 1 && $countryId != 38)} style="display:none;"{/if}>        
			            <td class="prTRight"><span class="prMarkRequired">*</span><label for="zipId">{t}Zip code:{/t}</label></td>
			            <td>
			                <div class=" yui-skin-sam">
			                    <div class="yui-ac"> {form_text name="zipcode" id="zipId"  value=$zipStr|escape:"html"}
			                        <div id="acZip"></div>
			                    </div>
			                </div>
			            </td>
			        </tr>
			        <tr id="LocationTrCity" {if !$countryId || $countryId == 1 || $countryId == 38} style="display:none;"{/if}>     
			            <td class="prTRight">
			                <span class="prMarkRequired">*</span><label for="city">{t}City:{/t}</label>
			            </td>
			            <td>
			                <div class=" yui-skin-sam">
			                    <div class="yui-ac"> {form_text name="city" id="city"  value=$cityStr|escape:"html"}
			                        <div id="acCity"></div>
			                    </div>
			                </div>
			            </td>
			        </tr>       
			        {* LOCATION *}
			        {*
					<tr>
						<td class="prTRight"><span class="prMarkRequired">*</span>
							<label>{t}Country:{/t}</label></td>
						<td> {form_select id="countryId" name="country" options=$countries onchange="xajax_changeCountry(this.options[this.selectedIndex].value);" selected=$country} </td>
					</tr>
					<tr>
						<td class="prTRight"><span class="prMarkRequired">*</span>
							<label>{t}State\Province:{/t}</label></td>
						<td> {form_select id="stateId" name="state" options=$states onchange="xajax_changeState(this.options[this.selectedIndex].value);" selected=$state} </td>
					</tr>
					<tr>
						<td class="prTRight"><span class="prMarkRequired">*</span>
							<label>{t}City:{/t}</label></td>
						<td>{form_select id="cityId" name="city" options=$cities  selected=$city} </td>
					</tr>
					<tr>
						<td class="prTRight"><label>{t}Zip code/Postal code:{/t}</label></td>
						<td align="left">{form_text id=zipId name="zipcode" value=$user->getZipcode()|escape:"html"}</td>
					</tr>
					*}
					
					<tr>
						<td class="prTRight"><label>{t}Timezone{/t}</label></td>
						<td>{form_select name="timezone" selected=$user->getTimezone() options=$time_zones} </td>
					</tr>
				</table></td>
			<td valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="prForm">
					<col width="30%" />
					<col width="70%" />
					<tr>
						<td colspan="2"><div style="font-size: 80%;">&nbsp;</div></td>
					</tr>
					<tr>
						<td class="prTRight"><span class="prMarkRequired">*</span>
							<label>{t}User ID:{/t}</label></td>
						<td class="prTLeft">{form_text name="login" value=$user->getLogin()|escape:"html"}</td>
					</tr>
					<tr>
						<td class="prTRight"><label>{t}New Password:{/t}</label></td>
						<td class="prTLeft"> {form_text id="new_pass" name="new_pass" value=$new_pass|escape:"html"} </td>
					</tr>
					<tr>
						<td class="prTRight"><span class="prMarkRequired">*</span>
							<label>{t}Email Address:{/t}</label></td>
						<td class="prTLeft"> {form_text name="email" value=$user->getEmail()|escape:"html"} </td>
					</tr>
					<tr>
						<td class="prTRight"><label>{t}Status:{/t}</label></td>
						<td class="prTLeft"> {form_select id="status" name="status" options=$statuses selected=$user->getStatus()} </td>
					</tr>
					<tr>
						<td class="prTRight"><label>{t}User/admin:{/t}</label></td>
						<td class="prTLeft"> {form_select id="admin_status" name="admin_status" options=$adminstatuses selected=$useradmin->getStatus()} </td>
					</tr>
					{if $useradmin->getStatus()=="admin"}
					<tr>
						<td class="prTRight"><label>{t}Admin role:{/t}</label></td>
						<td class="prTLeft"> {form_select id="admin_role" name="admin_role" options=$adminroles selected=$useradmin->getRole()} </td>
					</tr>
					{/if}
				</table></td>
		</tr>
	</table>
	<div class="prTCenter prIndentTop">{t var="in_submit"}Save{/t}{form_submit name="form_save" value=$in_submit}</div>
	{/form} </div>
